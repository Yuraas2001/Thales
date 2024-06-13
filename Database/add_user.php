<?php
session_start();
include("base.php");

// Check if the form is submitted
if (isset($_POST['username'], $_POST['password'], $_POST['role'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Define the password requirements
    $n = 1; // Number of numeric characters
    $p = 1; // Number of lowercase alphabetic characters
    $q = 1; // Number of uppercase alphabetic characters
    $r = 1; // Number of special characters

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

        try {
            $stmt = $bd->prepare("INSERT INTO Utilisateurs (NomUtilisateur, MotDePasse, TypeUtilisateur) VALUES (:username, :password, :role)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role, PDO::PARAM_INT);
            $stmt->execute();

            // Redirect back to the admin user list
            $_SESSION['success'] = "Utilisateur ajouté avec succès.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage();
        }
    }

    // Redirect back to the admin user list
    header('Location: ../Admin/admin_users_list.php');
    exit;
} else {
    // Redirect back to the admin user list if the form is not submitted
    header('Location: ../Admin/admin_users_list.php');
    exit;
}
?>
