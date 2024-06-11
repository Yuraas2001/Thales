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
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $index => $user) {
    if ($user['NomUtilisateur'] === $currentUsername) {
        unset($users[$index]);
        array_unshift($users, $user);
        break;
    }
}

// Ajouter un programme
if (isset($_POST['action']) && $_POST['action'] == 'add_program') {
    $newProgram = trim($_POST['new_program']);
    if (!empty($newProgram)) {
        // Vérifiez si le programme existe déjà
        $checkQuery = $bd->prepare("SELECT * FROM Programmes WHERE NomProgramme = :new_program");
        $checkQuery->execute([':new_program' => $newProgram]);
        if ($checkQuery->rowCount() == 0) {
            // Ajoutez le nouveau programme s'il n'existe pas
            $query = $bd->prepare("INSERT INTO Programmes (NomProgramme) VALUES (:new_program)");
            if ($query->execute([':new_program' => $newProgram])) {
                echo "Programme ajouté avec succès.";
            } else {
                echo "Erreur lors de l'ajout du programme.";
            }
        } else {
            echo "Le programme existe déjà.";
        }
    } else {
        echo "Le nom du programme ne peut pas être vide.";
    }
}

// Supprimer un programme
if (isset($_POST['action']) && $_POST['action'] == 'delete_program') {
    $programId = $_POST['program_id'];
    $query = $bd->prepare("DELETE FROM Programmes WHERE IDProgramme = :program_id");
    $query->execute([':program_id' => $programId]);
}

// Requête SQL pour les programmes avec DISTINCT pour éviter les doublons
$programStmt = $bd->prepare("SELECT DISTINCT IDProgramme, NomProgramme FROM Programmes");
$programStmt->execute();
$programs = $programStmt->fetchAll(PDO::FETCH_ASSOC);

// Requête SQL pour les bonnes pratiques
$stmt = $bd->prepare("
  SELECT bp.IDBonnePratique, p.NomProgramme, ph.NomPhase, bp.Description, mc.NomMotsCles, bp.Etat
  FROM PratiqueProg pp
  INNER JOIN Programmes p ON pp.IDProgramme = p.IDProgramme
  INNER JOIN PratiquePhases pp2 ON pp.IDBonnePratique = pp2.IDBonnePratique
  INNER JOIN Phases ph ON pp2.IDPhase = ph.IDPhase
  INNER JOIN PratiqueMotsCles pmc ON pp.IDBonnePratique = pmc.IDBonnePratique
  INNER JOIN MotsCles mc ON pmc.IDMotsCles = mc.IDMotsCles
  INNER JOIN BonnesPratiques bp ON pp.IDBonnePratique = bp.IDBonnePratique
");

$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Regrouper les résultats par bonne pratique, programme et mot clé
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
<div class="container">
    <div class="header">
        <div class="program-management">
            <h2>Gestion des Programmes</h2>
            <form method="post">
                <label for="new_program">Ajouter un nouveau programme :</label>
                <input type="text" name="new_program" id="new_program" required>
                <button type="submit" name="action" value="add_program">Ajouter</button>
            </form>
            <h3>Programmes existants :</h3>
            <table>
                <thead>
                    <tr>
                        <th>Programme</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($programs as $program): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($program['NomProgramme']); ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="program_id" value="<?php echo htmlspecialchars($program['IDProgramme']); ?>">
                                    <button type="submit" name="action" value="delete_program">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
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
