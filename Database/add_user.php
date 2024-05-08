<?php
    include("base.php");

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve the form data
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'] == 'admin' ? 1 : 0;

        // Prepare the SQL INSERT statement
        $stmt = $bd->prepare("INSERT INTO Utilisateurs (NomUtilisateur, MotDePasse, TypeUtilisateur) VALUES (:username, :password, :role)");

        // Bind the form data to the SQL INSERT statement
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);

        // Execute the SQL INSERT statement
        $stmt->execute();

        // Redirect back to the admin user list
        header('Location: ../Admin/admin_users_list.php');
        exit;
    } else {
        // Redirect back to the admin user list if the form is not submitted
        header('Location: ../Admin/admin_users_list.php');
        exit;
    }
?>