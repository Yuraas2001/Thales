<?php
session_start();
include("../Database/base.php");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit;
}
$currentUsername = $_SESSION['username'];// Get the current username from session

// Prepare and execute the query to get user details
$stmt = $bd->prepare("SELECT NomUtilisateur, TypeUtilisateur, Bloque FROM Utilisateurs");
$stmt->execute();

$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Move the current user to the beginning of the users array
foreach ($users as $index => $user) {
    if ($user['NomUtilisateur'] === $currentUsername) {
        unset($users[$index]);
        array_unshift($users, $user);
        break;
    }
}

$usernameToModify = isset($_GET['modify']) ? $_GET['modify'] : null;

// Prepare and execute the query to get distinct program names
$programStmt = $bd->prepare("SELECT DISTINCT NomProgramme FROM Programmes");
$programStmt->execute();
$programs = $programStmt->fetchAll(PDO::FETCH_COLUMN);

// Handle form submission for actions like restore and permanent delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];
    // Restore the best practice
    if ($action === 'restore') {
        $query = $bd->prepare("UPDATE BonnesPratiques SET Etat = 0 WHERE IDBonnePratique = :id");
        $query->execute([':id' => $id]);
    } elseif ($action === 'permanent_delete') {
      // Permanently delete the best practice
        $query = $bd->prepare("DELETE FROM BonnesPratiques WHERE IDBonnePratique = :id");
        $query->execute([':id' => $id]);
    }

    // Redirect to the same page to refresh the data
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/Styles/user.css">
  <link rel="stylesheet" href="/Styles/admin.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  <title>Rebooters Search</title>
</head>
<body>

<nav>
    <div class="logo">
        <img src="/Images/logo.svg" alt="REBOOTERS Logo" height="200">
    </div>
    <div>
        <div class="user-menu">
            <a href="./superadmin_home.php" class="menu-button"><?php echo htmlspecialchars($currentUsername); ?></a>
            <button class="user-button">☰</button>
            <div class="user-dropdown">
            <a href="./superadmin_changepassword.php">Modifier le mot de passe</a>
                <a href="../Database/deconnex.php">Se déconnecter</a>

            </div>
        </div>
    </div>
</nav>
<div class="menu">
    <a href="superadmin_users_list.php">Listes des utilisateurs</a>
    <a href="superadmin_banned_users.php">Modifier paramètres mot de passe</a>
    <a href="superadmin_bp.php">Gestion des bonnes pratiques</a>
    <a href="superadmin_editprog.php">Modifier un programme</a>
    <a href="superadmin_addbp.php">Ajouter une bonne pratique</a>
</div>

<div class="search-container">
  <form action="superadmin_home.php" method="get">
    <input type="text" name="keyword" placeholder="Entrer un mot clé...">
    <select name="program">
        <option value="">Programme</option>
        <?php foreach ($programs as $program): ?>
            <option value="<?php echo htmlspecialchars($program); ?>"><?php echo htmlspecialchars($program); ?></option>
        <?php endforeach; ?>
    </select>
    <select name="phase">
        <option value="">Phase</option>
        <option value="codage">Codage</option>
        <option value="exécution">Exécution</option>
        <option value="analyse">Analyse</option>
        <option value="préparation">Préparation</option>
    </select>
    <button class="search-button">
        <i class="fa fa-search"></i> 
    </button>
  </form>
</div>

<div class="container">
  <h2 class="results-title">Résultats</h2>
  <div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Programme</th>
                <th>Phase</th>
                <th>Description</th>
                <th>Mots Clés</th>
                <th>État</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        
        $stmt = $bd->prepare("
          SELECT BonnesPratiques.IDBonnePratique, Programmes.NomProgramme, Phases.NomPhase, BonnesPratiques.Description, MotsCles.NomMotsCles, BonnesPratiques.Etat
          FROM PratiqueProg
          INNER JOIN Programmes ON PratiqueProg.IDProgramme = Programmes.IDProgramme
          INNER JOIN PratiquePhases ON PratiqueProg.IDBonnePratique = PratiquePhases.IDBonnePratique
          INNER JOIN Phases ON PratiquePhases.IDPhase = Phases.IDPhase
          INNER JOIN PratiqueMotsCles ON PratiqueProg.IDBonnePratique = PratiqueMotsCles.IDBonnePratique
          INNER JOIN MotsCles ON PratiqueMotsCles.IDMotsCles = MotsCles.IDMotsCles
          INNER JOIN BonnesPratiques ON PratiqueProg.IDBonnePratique = BonnesPratiques.IDBonnePratique
          ORDER BY FIELD(Phases.NomPhase, 'codage', 'exécution', 'analyse', 'préparation')
        ");

       
        $stmt->execute();

      
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

       
        $groupedResults = [];
        foreach ($results as $row) {
          $id = $row['IDBonnePratique'];
          $program = $row['NomProgramme'];
          $keyword = $row['NomMotsCles'];

          if (!isset($groupedResults[$id])) {
            $groupedResults[$id] = $row;
            $groupedResults[$id]['NomProgramme'] = [$program];
            $groupedResults[$id]['NomMotsCles'] = [$keyword];
          } else {
            if (!in_array($program, $groupedResults[$id]['NomProgramme'])) {
              $groupedResults[$id]['NomProgramme'][] = $program;
            }
            if (!in_array($keyword, $groupedResults[$id]['NomMotsCles'])) {
              $groupedResults[$id]['NomMotsCles'][] = $keyword;
            }
          }
        }

        // Display the grouped results in the table
        foreach ($groupedResults as $row) {
          $status = $row['Etat'] ? "<span style='color: red;'>Supprimé</span>" : "Actif";
          echo "<tr>";
          echo "<td>" . htmlspecialchars(implode(", ", $row['NomProgramme'])) . "</td>";
          echo "<td>" . htmlspecialchars($row['NomPhase']) . "</td>";
          echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
          echo "<td>" . htmlspecialchars(implode(", ", $row['NomMotsCles'])) . "</td>";
          echo "<td>" . $status . "</td>";
          echo "<td>
          <form method='post' action='superadmin_modify.php' style='display:inline;'>
          <input type='hidden' name='modify_id' value='" . $row['IDBonnePratique'] . "'>
          <button type='submit'>Modifier</button>
      </form>
      <form method='post'>
      <input type='hidden' name='id' value='" . $row['IDBonnePratique'] . "'>
      " . ($row['Etat'] ? "<button type='submit' name='action' value='restore'>Restaurer</button>" : "") . "
      <button type='submit' name='action' value='permanent_delete'>Suppression Définitive</button>
  </form>
                </td>";
          echo "</tr>";
        }
        ?>
        </tbody>
    </table>
  </div>
</div>

<div class="export-button">
    <button class="button primary">Exporter le Tableau</button>
</div>

</body>
</html>
