<?php

session_start();
include("base.php");

// Récupérer les données du formulaire
$username = $_POST['username'];
$new_password = $_POST['new_password'];
$new_role = $_POST['new_role'];

echo $username, $new_password, $new_role;


// Récupérer les exigences de mot de passe depuis la base de données
$stmt = $bd->prepare("SELECT * FROM PasswordRequirements ORDER BY id DESC LIMIT 1");
$stmt->execute();
$requirements = $stmt->fetch(PDO::FETCH_ASSOC);



$n = $requirements['n']; // Nombre de caractères numériques requis
$p = $requirements['p']; // Nombre de caractères alphabétiques minuscules requis
$q = $requirements['q']; // Nombre de caractères alphabétiques majuscules requis
$r = $requirements['r']; // Nombre de caractères spéciaux requis



// Vérifier si le mot de passe satisfait toutes les exigences
if (!preg_match('/^[^\p{M}]*$/u', $new_password)) {
    $_SESSION['error'] = "Le mot de passe ne doit pas contenir d'accent.";
} elseif (strpos($new_password, $username) !== false) {
    $_SESSION['error'] = "Le mot de passe ne doit pas contenir le nom d'utilisateur.";
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

    // Hasher le nouveau mot de passe
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
    $new_role_value = ($new_role == 'Admin') ? 1 : 0;

   // Mettre à jour le rôle et le mot de passe de l'utilisateur dans la base de données
    $stmt = $bd->prepare("UPDATE Utilisateurs SET MotDePasse = :new_password_hash, TypeUtilisateur = :new_role WHERE NomUtilisateur = :username");
    $stmt->execute([':new_password_hash' => $new_password_hash, ':new_role' => $new_role, ':username' => $username]);

    // Rediriger vers la liste des utilisateurs avec un message de succès
    $_SESSION['message'] = "Role and password changed successfully.";
    header('Location: ../Admin/admin_users_list.php');
    exit;
}

// En cas d'erreur de validation du mot de passe, rediriger avec un message d'erreur
header('Location: ../Admin/admin_users_list.php');
exit;

?>
