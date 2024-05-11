<?php
session_start(); // Start the session
include("../Database/base.php"); // Include database connection

// Check if the user is logged in by verifying if the 'username' session variable is set
if (!isset($_SESSION['username'])) {
    // Redirect the user to the login page if they are not logged in
    header("Location: login.php");
    exit;
}

$currentUsername = $_SESSION['username']; // Get the current username from session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if new passwords match
    if ($newPassword !== $confirmPassword) {
        $_SESSION['error'] = "Les nouveaux mots de passe ne correspondent pas.";
        header("Location: change_password.php");
        exit;
    }

    // Prepare and execute SQL statement to fetch current password
    $stmt = $bd->prepare("SELECT MotDePasse FROM Utilisateurs WHERE NomUtilisateur = ?");
    $stmt->execute([$currentUsername]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the fetched password matches the hashed password
    if (!$user || !password_verify($oldPassword, $user['MotDePasse'])) {
        $_SESSION['error'] = "L'ancien mot de passe est incorrect.";
        header("Location: change_password.php");
        exit;
    }

    // Hash the new password and update it in the database
    $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $bd->prepare("UPDATE Utilisateurs SET MotDePasse = ? WHERE NomUtilisateur = ?");
    $stmt->execute([$newPasswordHashed, $currentUsername]);

    // Set success message and redirect
    $_SESSION['message'] = "Votre mot de passe a été modifié correctement.";
    header("Location: change_password.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Styles/admin.css">
    <link rel="stylesheet" href="/Styles/user_bp.css">
    <title>Modifier le mot de passe</title>
    <style>
        .error {
            color: red;
        }
        .success {
            color: green;
        }
    </style>
</head>
<body>
<nav>
    <div class="logo">
        <img src="/Images/logo.svg" alt="REBOOTERS Logo" height="200">
    </div>
    <div>
        <div class="user-menu">
            <a href="./user_home.php" class="menu-button"><?php echo htmlspecialchars($currentUsername); ?></a>
            <button class="user-button">☰</button>
            <div class="user-dropdown">
                <a href="./user_bp.php">Paramètres</a>
                <a href="./change_password.php">Gestion de mot de passe</a>
                <a href="../Database/deconnex.php">Se déconnecter</a>
            </div>
        </div>
    </div>
</nav>
<div class="container">
    <div class="header">
        <h1>Modifier le mot de passe</h1>
    </div>

    <form action="change_password.php" method="post">
        <div class="form-group">
            <label for="old_password">Ancien mot de passe:</label>
            <input type="password" id="old_password" name="old_password" required>
        </div>

        <div class="form-group">
            <label for="new_password">Nouveau mot de passe:</label>
            <input type="password" id="new_password" name="new_password" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirmer le nouveau mot de passe:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <button type="submit" class="btn">Modifier le mot de passe</button>
    </form>

    <?php
    // Display error message if any
    if (isset($_SESSION['error'])) {
        echo "<p class='error'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
    // Display success message if any
    if (isset($_SESSION['message'])) {
        echo "<p class='success'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>
</div>
</body>
</html>
