<?php
session_start();
include("../Database/base.php");
include("../Database/helpers.php"); 

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}
$currentUsername = $_SESSION['username'];// Get the current username from the session
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
// Handle the form submission for adding a new program
if (isset($_POST['action']) && $_POST['action'] == 'add_program') {
    $newProgram = trim($_POST['new_program']);
    if (!empty($newProgram)) {
        // Check if the program already exists
        $checkQuery = $bd->prepare("SELECT * FROM Programmes WHERE NomProgramme = :new_program");
        $checkQuery->execute([':new_program' => $newProgram]);
        if ($checkQuery->rowCount() == 0) {
            // Insert the new program into the database
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
// Handle the form submission for deleting a program
if (isset($_POST['action']) && $_POST['action'] == 'delete_program') {
    $programName = $_POST['program_name'];
    $query = $bd->prepare("DELETE FROM Programmes WHERE NomProgramme = :program_name");
    $query->execute([':program_name' => $programName]);
}
// Prepare and execute the query to get distinct program names
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
  <script>
    function setProgramName() {
        var select = document.getElementById('existing_programs');
        var programName = select.options[select.selectedIndex].value;
        document.getElementById('program_name').value = programName;
    }
  </script>
</head>
<body>

<nav>
    <div class="logo">
        <img src="/Images/logo.svg" alt="REBOOTERS Logo" height="200">
    </div>
    <div>
        <div class="user-menu">
        <a href="./superadmin_home.php" class="menu-button"><?php echo displayUsername($currentUsername, 'superadmin'); ?></a>
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
    <a href="superadmin_editprog.php">Modifier un programmee</a>
    <a href="superadmin_addbp.php">Ajouter une bonne pratique</a>
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
            <select id="existing_programs" name="existing_programs" onchange="setProgramName()">
                <?php foreach ($programs as $program): ?>
                    <option value="<?php echo htmlspecialchars($program['NomProgramme']); ?>"><?php echo htmlspecialchars($program['NomProgramme']); ?></option>
                <?php endforeach; ?>
            </select>
            <form method="post" style="display:inline;">
                <input type="hidden" name="program_name" id="program_name" value="">
                <button type="submit" name="action" value="delete_program" onclick="return confirm('Voulez-vous vraiment supprimer ce programme?')">Supprimer</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
