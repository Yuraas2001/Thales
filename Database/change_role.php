<?php
    session_start();
    include("base.php");

    // Get the old and new username and the new role from the form
    $old_username = $_POST["old_username"];
    $new_username = $_POST["new_username"];
    $new_role = $_POST["new_role"];

    // Log the values of the variables
    error_log("Old username: $old_username");
    error_log("Session username: " . $_SESSION['username']);

    // Check if the old username matches the current user's username
    if ($_SESSION['username'] === $old_username) {
        // Store the message in the session
        $_SESSION['message'] = "You can't change your own role.";

        // Redirect back to the user list without making any changes
        header('Location: ../Admin/admin_users_list.php');
        exit;
    }

    // Prepare and execute the SQL statement
    $stmt = $bd->prepare("UPDATE Utilisateurs SET NomUtilisateur = :new_username, TypeUtilisateur = :new_role WHERE NomUtilisateur = :old_username");
    $stmt->execute([':new_username' => $new_username, ':new_role' => $new_role, ':old_username' => $old_username]);

    // Redirect back to the user list
    header('Location: ../Admin/admin_users_list.php');
    exit;
?>