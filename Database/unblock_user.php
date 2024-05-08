<?php
    include("base.php");


    $username = $_POST['username'];

    // Prepare and execute the SQL statement
    $stmt = $bd->prepare("UPDATE Utilisateurs SET Bloque = 0 WHERE NomUtilisateur = :username");
    $stmt->execute([':username' => $username]);

    // Redirect back to the users list
    header("Location: ../admin/admin_users_list.php");
?>