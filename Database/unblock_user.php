<?php
    include("base.php");

    // Check if the username and new password are provided
    if (!isset($_POST['username'], $_POST['new_password'])) {
        die("No username or new password provided");
    }

    $username = $_POST['username'];
    $newPassword = $_POST['new_password'];

    // Prepare and execute the SQL statement
    $stmt = $bd->prepare("UPDATE Utilisateurs SET Bloque = 0, MotDePasse = :newPassword WHERE NomUtilisateur = :username");
    $stmt->execute([':username' => $username, ':newPassword' => $newPassword]);

    // Redirect back to the users list
    header("Location: ../admin/admin_users_list.php");
?>