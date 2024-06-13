<?php
session_start();
include("../Database/base.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$currentUsername = $_SESSION['username'];


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


if (isset($_POST['action']) && $_POST['action'] == 'add_program') {
    $newProgram = trim($_POST['new_program']);
    if (!empty($newProgram)) {
        $checkQuery = $bd->prepare("SELECT * FROM Programmes WHERE NomProgramme = :new_program");
        $checkQuery->execute([':new_program' => $newProgram]);
        if ($checkQuery->rowCount() == 0) {
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


if (isset($_POST['action']) && $_POST['action'] == 'delete_program') {
    $programId = $_POST['program_id'];
    $query = $bd->prepare("DELETE FROM Programmes WHERE IDProgramme = :program_id");
    $query->execute([':program_id' => $programId]);
}


$programStmt = $bd->prepare("SELECT DISTINCT NomProgramme FROM Programmes WHERE TRIM(NomProgramme) != ''");
$programStmt->execute();
$programs = $programStmt->fetchAll(PDO::FETCH_ASSOC);
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
            <select id="existing_programs" name="existing_programs">
                <?php foreach ($programs as $program): ?>
                    <option value="<?php echo htmlspecialchars($program['NomProgramme']); ?>"><?php echo htmlspecialchars($program['NomProgramme']); ?></option>
                <?php endforeach; ?>
            </select>
            <form method="post" style="display:inline;">
                <input type="hidden" name="program_id" id="program_id" value="">
                <button type="submit" name="action" value="delete_program" onclick="return confirm('Voulez-vous vraiment supprimer ce programme?')">Supprimer</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
