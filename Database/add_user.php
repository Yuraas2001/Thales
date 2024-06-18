<?php
    session_start();
    include("base.php");

    // Check if the form is submitted
    if (isset($_POST['username'], $_POST['password'], $_POST['role'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];
            // Get the password requirements from the database
            $stmt = $bd->prepare("SELECT * FROM PasswordRequirements ORDER BY id DESC LIMIT 1");
            $stmt->execute();
            $requirements = $stmt->fetch(PDO::FETCH_ASSOC);
    
            $n = $requirements['n']; // Number of numeric characters
            $p = $requirements['p']; // Number of lowercase alphabetic characters
            $q = $requirements['q']; // Number of uppercase alphabetic characters
            $r = $requirements['r']; // Number of special characters


        // Check the password against the requirements
        if (!preg_match('/^[^\p{M}]*$/u', $password)) {
            $_SESSION['error'] = "Le mot de passe ne doit pas contenir d’accent.";
        } elseif (strpos($password, $username) !== false) {
            $_SESSION['error'] = "Le mot de passe ne doit pas contenir le login de l’utilisateur.";
        } elseif (!preg_match('/^[a-zA-Z0-9!"#$%&\'*+,\-.\/;<=>?@\\\^_`|}~]*$/', $password)) {
            $_SESSION['error'] = "Le mot de passe contient des caractères non autorisés.";
        } elseif (preg_match_all('/[0-9]/', $password) < $n) {
            $_SESSION['error'] = "Le mot de passe doit contenir au moins $n caractère(s) numérique(s).";
        } elseif (preg_match_all('/[a-z]/', $password) < $p) {
            $_SESSION['error'] = "Le mot de passe doit contenir au moins $p caractère(s) alphabétique(s) en minuscule.";
        } elseif (preg_match_all('/[A-Z]/', $password) < $q) {
            $_SESSION['error'] = "Le mot de passe doit contenir au moins $q caractère(s) alphabétique(s) en majuscule.";
        } elseif (preg_match_all('/[!"#$%&\'*+,\-.\/;<=>?@\\\^_`|}~]/', $password) < $r) {
            $_SESSION['error'] = "Le mot de passe doit contenir au moins $r caractère(s) spécial(aux).";
        } else {
            // If the password meets all requirements, hash it and insert the new user into the database
            $password = password_hash($password, PASSWORD_DEFAULT);
        
            $stmt = $bd->prepare("INSERT INTO Utilisateurs (NomUtilisateur, MotDePasse, TypeUtilisateur) VALUES (:username, :password, :role)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role, PDO::PARAM_INT);
            $stmt->execute();
        }
        
        // Redirect back to the admin user list
        header('Location: ../Admin/admin_users_list.php');
        exit;
    }
 
  else {
        // Redirect back to the admin user list if the form is not submitted
        header('Location: ../Admin/admin_users_list.php');
        exit;
    }
?>