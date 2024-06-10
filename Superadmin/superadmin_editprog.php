<?php
session_start();
include("../Database/base.php");

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

// Ajouter un programme
if (isset($_POST['action']) && $_POST['action'] == 'add_program') {
    $newProgram = $_POST['new_program'];
    // Vérifiez si le programme existe déjà
    $checkQuery = $bd->prepare("SELECT * FROM Programmes WHERE NomProgramme = :new_program");
    $checkQuery->execute([':new_program' => $newProgram]);
    if ($checkQuery->rowCount() == 0) {
        // Ajoutez le nouveau programme s'il n'existe pas
        $query = $bd->prepare("INSERT INTO Programmes (NomProgramme) VALUES (:new_program)");
        $query->execute([':new_program' => $newProgram]);
    }
}

// Supprimer un programme
if (isset($_POST['action']) && $_POST['action'] == 'delete_program') {
    $programId = $_POST['program_id'];
    $query = $bd->prepare("DELETE FROM Programmes WHERE IDProgramme = :program_id");
    $query->execute([':program_id' => $programId]);
}

// Requête SQL pour les programmes
$programStmt = $bd->prepare("SELECT IDProgramme, NomProgramme FROM Programmes");
$programStmt->execute();
$programs = $programStmt->fetchAll(PDO::FETCH_ASSOC);

// Requête SQL pour les bonnes pratiques
$stmt = $bd->prepare("
  SELECT bp.IDBonnePratique, p.NomProgramme, ph.NomPhase, bp.Description, mc.NomMotsCles, bp.is_deleted
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
  <a href="superadmin_users_list.php">Listes des utilisateurs</a>
  <a href="superadmin_bp.php">Gestion des bonnes pratiques</a>
  <a href="superadmin_editprog.php">Modifier un programme</a>
</div>
</section>
<div class="program-management">
    <h2>Gestion des Programmes</h2>
    <form method="post">
      <label for="new_program">Ajouter un nouveau programme :</label>
      <input type="text" name="new_program" id="new_program">
      <button type="submit" name="action" value="add_program">Ajouter</button>
    </form>
    <h3>Programmes existants :</h3>
    <ul>
      <?php foreach ($programs as $program): ?>
        <li>
          <?php echo htmlspecialchars($program['NomProgramme']); ?>
          <form method="post" style="display:inline;">
            <input type="hidden" name="program_id" value="<?php echo $program['IDProgramme']; ?>">
            <button type="submit" name="action" value="delete_program">Supprimer</button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>



</body>
</html>
