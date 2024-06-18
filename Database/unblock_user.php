<?php
session_start();
include("base.php");

// Get the username and new password from the form
$username = $_POST["username"];
$new_password = $_POST["new_password"];

// Define the password requirements
$n = 1; // Number of numeric characters
$p = 1; // Number of lowercase alphabetic characters
$q = 1; // Number of uppercase alphabetic characters
$r = 1; // Number of special characters

// Check the password against the requirements
if (!preg_match('/^[^\p{M}]*$/u', $new_password)) {
    $_SESSION['error'] = "Le mot de passe ne doit pas contenir d’accent.";
} elseif (strpos($new_password, $username) !== false) {
    $_SESSION['error'] = "Le mot de passe ne doit pas contenir le login de l’utilisateur.";
} elseif (!preg_match('/^[a-zA-Z0-9!"#$%&\'*+,\-.\/;<=>?@\\\^_`|}~]*$/', $new_password)) {
    $_SESSION['error'] = "Le mot de passe contient des caractères non autorisés.";
} elseif (preg_match_all('/[0-9]/', $new_password) < $n) {
    $_SESSION['error'] = "Le mot de passe doit contenir au moins $n caractère(s) numérique(s).";
} elseif (preg_match_all('/[a-z]/', $new_password) < $p) {
    $_SESSION['error'] = "Le mot de passe doit contenir au moins $p caractère(s) alphabétique(s) en minuscule.";
} elseif (preg_match_all('/[A-Z]/', $new_password) < $q) {
    $_SESSION['error'] = "Le mot de passe doit contenir au moins $q caractère(s) alphabétique(s) en majuscule.";
} elseif (preg_match_all('/[!"#$%&\'*+,\-.\/;<=>?@\\\^_`|}~]/', $new_password) < $r) {
    $_SESSION['error'] = "Le mot de passe doit contenir au moins $r caractère(s) spécial(aux).";
} else {
    // If the password meets all requirements, hash it and update the user password in the database
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Prepare and execute the SQL statement to unblock the user and update the password
    $stmt = $bd->prepare("UPDATE Utilisateurs SET Bloque = 0, MotDePasse = :password WHERE NomUtilisateur = :username");
    $stmt->execute([':username' => $username, ':password' => $hashed_password]);
}

// Redirect back to the admin user list
header('Location: ../Admin/admin_users_list.php');
exit;
?>