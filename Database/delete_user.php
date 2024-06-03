<?php
    include("base.php");

    // Retrieve the username from the form submission
    $username = $_POST['username'];


    // Prepare the SQL DELETE statement
    $stmt = $bd->prepare("DELETE FROM Utilisateurs WHERE NomUtilisateur = :username");

    // Bind the username to the SQL DELETE statement
    $stmt->bindParam(':username', $username);

    // Execute the SQL DELETE statement
    $stmt->execute();

    header('Location: ../Admin/admin_users_list.php');

    exit;

    ?>