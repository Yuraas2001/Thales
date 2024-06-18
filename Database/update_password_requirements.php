<?php
include("base.php");

// Démarrer la session pour accéder aux informations de l'utilisateur
session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$currentUsername = $_SESSION['username'];

// Check if the form is submitted
if (isset($_POST['n'], $_POST['p'], $_POST['q'], $_POST['r'])) {
    $n = $_POST['n'];
    $p = $_POST['p'];
    $q = $_POST['q'];
    $r = $_POST['r'];

    // Update the password requirements in the database
    $stmt = $bd->prepare("UPDATE PasswordRequirements SET n = :n, p = :p, q = :q, r = :r");
    $stmt->bindParam(':n', $n);
    $stmt->bindParam(':p', $p);
    $stmt->bindParam(':q', $q);
    $stmt->bindParam(':r', $r);
    $stmt->execute();

    // Retrieve the type of the current user
    $stmt = $bd->prepare("SELECT TypeUtilisateur FROM Utilisateurs WHERE NomUtilisateur = :username");
    $stmt->execute([':username' => $currentUsername]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $userType = $user['TypeUtilisateur'];
        
        // Redirect based on user type
        if ($userType == 2) {
            header("Location: ../Superadmin/superadmin_banned_users.php");
            exit();
        } else {
            header("Location: ../Admin/admin_banned_users.php");
            exit();
        }
    } 
    
}
?>
