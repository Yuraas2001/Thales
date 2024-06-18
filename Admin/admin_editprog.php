<?php
session_start();
include("../Database/base.php");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$currentUsername = $_SESSION['username'];
$message = "";

// Retrieve existing programs
$programStmt = $bd->prepare("SELECT DISTINCT NomProgramme FROM Programmes WHERE TRIM(NomProgramme) != '' ORDER BY NomProgramme");
$programStmt->execute();
$programs = $programStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle program addition and deletion
if (isset($_POST['action']) && $_POST['action'] == 'add_program') {
    $newProgram = trim($_POST['new_program']);
    if (!empty($newProgram)) {
        // Check if the program already exists
        $checkQuery = $bd->prepare("SELECT * FROM Programmes WHERE NomProgramme = :new_program");
        $checkQuery->execute([':new_program' => $newProgram]);
        if ($checkQuery->rowCount() == 0) {
            // Add the new program to the database
            $query = $bd->prepare("INSERT INTO Programmes (NomProgramme) VALUES (:new_program)");
            if ($query->execute([':new_program' => $newProgram])) {
                $message = "Programme ajouté avec succès.";
            } else {
                $message = "Erreur lors de l'ajout du programme.";
            }
        } else {
            $message = "Le programme existe déjà.";
        }
    } else {
        $message = "Le nom du programme ne peut pas être vide.";
    }
    // Refresh the list of programs after addition
    $programStmt->execute();
    $programs = $programStmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST['action']) && $_POST['action'] == 'delete_program') {
    $programName = $_POST['program_name'];
    // Delete the selected program from the database
    $query = $bd->prepare("DELETE FROM Programmes WHERE NomProgramme = :program_name");
    $query->execute([':program_name' => $programName]);
   // Refresh the list of programs after deletion
    $programStmt->execute();
    $programs = $programStmt->fetchAll(PDO::FETCH_ASSOC);
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
                <a href="./admin_changepassword.php">Modifier le mot de passe</a>
                <a href="../Database/deconnex.php">Se déconnecter</a>
            </div>
        </div>
    </div>
</nav>
<div class="menu">
    <!-- Links for navigation in the admin section -->
    <a href="admin_users_list.php">Listes des utilisateurs</a>
    <a href="admin_banned_users.php">Modifier paramètres mot de passe</a>
    <a href="admin_bp.php">Gestion des bonnes pratiques</a>
    <a href="admin_editprog.php">Modifier un programme</a>
    <a href="admin_addbp.php">Ajouter une bonne pratique</a>
</div>
<div class="container">
    <div class="header">
        <div class="program-management">
            <!-- Display a message if it's set -->
            <h2>Gestion des Programmes</h2>
            <?php if (!empty($message)): ?>
                <p><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
              <!-- Form to add a new program -->
            <form method="post">
                <label for="new_program">Ajouter un nouveau programme :</label>
                <input type="text" name="new_program" id="new_program" required>
                <button type="submit" name="action" value="add_program">Ajouter</button>
            </form>
            <h3>Programmes existants :</h3>*<!-- Form to delete an existing program -->
            <form method="post" style="display:inline;">
                <select id="existing_programs" name="program_name">
                    <!-- Loop through existing programs and create an option for each -->
                    <?php foreach ($programs as $program): ?>
                        <option value="<?php echo htmlspecialchars($program['NomProgramme']); ?>"><?php echo htmlspecialchars($program['NomProgramme']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="action" value="delete_program" onclick="return confirm('Voulez-vous vraiment supprimer ce programme?')">Supprimer</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
