<?php
session_start();
include("../Database/base.php");

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    // Redirigez l'utilisateur vers la page de connexion s'il n'est pas connecté
    header("Location: login.php");
    exit;
}

$currentUsername = $_SESSION['username'];

// Préparez et exécutez la requête SQL pour les utilisateurs
$stmt = $bd->prepare("SELECT NomUtilisateur, TypeUtilisateur, Bloque FROM Utilisateurs");
$stmt->execute();

// Récupérez tous les résultats
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $index => $user) {
    if ($user['NomUtilisateur'] === $currentUsername) {
        unset($users[$index]);
        array_unshift($users, $user);
        break;
    }
}

$usernameToModify = isset($_GET['modify']) ? $_GET['modify'] : null;

// Requête SQL pour les programmes avec DISTINCT pour éviter les doublons
$programStmt = $bd->prepare("SELECT DISTINCT NomProgramme FROM Programmes");
$programStmt->execute();
$programs = $programStmt->fetchAll(PDO::FETCH_COLUMN);
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
        <img src="Images/logo.svg" alt="REBOOTERS Logo" height="200">
    </div>
    <div>
        <div class="user-menu">
            <a href="./admin_home.php" class="menu-button"><?php echo htmlspecialchars($currentUsername); ?></a>
            <button class="user-button">☰</button>
            <div class="user-dropdown">
                <a href="../Database/deconnex.php">Se déconnecter</a>
            </div>
        </div>
    </div>
</nav>
<div class="menu">
  <a href="admin_users_list.php">Listes des utilisateurs</a>
  <a href="admin_bp.php">Gestion des bonnes pratiques</a>
  <a href="admin_editprog.php">Modifier un programme</a>
</div>

<div class="search-container">
  <form action="admin_home.php" method="get">
    <input type="text" name="keyword" placeholder="Entrer un mot clé...">
    <select name="program">
        <option value="">Programme</option>
        <?php foreach ($programs as $program): ?>
            <option value="<?php echo htmlspecialchars($program); ?>"><?php echo htmlspecialchars($program); ?></option>
        <?php endforeach; ?>
    </select>
    <select name="phase">
        <option value="">Phase</option>
        <option value="optionA">Codage</option>
        <option value="optionB">Execution</option>
        <option value="optionC">Analyse</option>
        <option value="optionD">Préparation</option>
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
        // Préparez la requête SQL
        $stmt = $bd->prepare("
          SELECT BonnesPratiques.IDBonnePratique, Programmes.NomProgramme, Phases.NomPhase, BonnesPratiques.Description, MotsCles.NomMotsCles, BonnesPratiques.Etat
          FROM PratiqueProg
          INNER JOIN Programmes ON PratiqueProg.IDProgramme = Programmes.IDProgramme
          INNER JOIN PratiquePhases ON PratiqueProg.IDBonnePratique = PratiquePhases.IDBonnePratique
          INNER JOIN Phases ON PratiquePhases.IDPhase = Phases.IDPhase
          INNER JOIN PratiqueMotsCles ON PratiqueProg.IDBonnePratique = PratiqueMotsCles.IDBonnePratique
          INNER JOIN MotsCles ON PratiqueMotsCles.IDMotsCles = MotsCles.IDMotsCles
          INNER JOIN BonnesPratiques ON PratiqueProg.IDBonnePratique = BonnesPratiques.IDBonnePratique
        ");

        // Exécutez la requête
        $stmt->execute();

        // Récupérez les résultats
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Regroupez les résultats par bonne pratique, programme et mot clé
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

        // Parcourez les résultats groupés et créez des lignes de tableau
        foreach ($groupedResults as $row) {
          $status = $row['Etat'] ? "<span style='color: red;'>Supprimé</span>" : "Actif";
          echo "<tr>";
          echo "<td>" . htmlspecialchars(implode(", ", $row['NomProgramme'])) . "</td>";
          echo "<td>" . htmlspecialchars($row['NomPhase']) . "</td>";
          echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
          echo "<td>" . htmlspecialchars(implode(", ", $row['NomMotsCles'])) . "</td>";
          echo "<td>" . $status . "</td>";
          echo "<td>
                  <form method='post'>
                      <input type='hidden' name='id' value='" . $row['IDBonnePratique'] . "'>
                      " . ($row['Etat'] ? "<button type='submit' name='action' value='restore'>Restaurer</button>" : "") . "
                      <button type='submit' name='action' value='permanent_delete'>Suppression Définitive</button>
                  </form>
                </td>";
          echo "</tr>";
        }

        // Check if an action is triggered
        if (isset($_POST['action'])) {
          $id = $_POST['id'];
          $action = $_POST['action'];
          
          if ($action === 'restore') {
            $query = $bd->prepare("UPDATE BonnesPratiques SET Etat = 0 WHERE IDBonnePratique = :id");
            $query->execute([':id' => $id]);
          } elseif ($action === 'permanent_delete') {
            $query = $bd->prepare("DELETE FROM BonnesPratiques WHERE IDBonnePratique = :id");
            $query->execute([':id' => $id]);
          }

          // Refresh the page to reflect changes
          header("Location: " . $_SERVER['PHP_SELF']);
          exit();
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
