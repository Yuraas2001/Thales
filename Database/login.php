<?php
include("base.php");

// Récupérer le nom d'utilisateur et le mot de passe depuis le formulaire
$username = $_POST["username"];
$password = $_POST["password"];

// Préparer et exécuter la requête SQL pour obtenir les détails de l'utilisateur
$stmt = $bd->prepare("SELECT * FROM Utilisateurs WHERE NomUtilisateur = :username");
$stmt->execute([':username' => $username]);

// Récupérer le résultat
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si une ligne est retournée
if ($result) {
    // Vérifier si l'utilisateur est bloqué
    if ($result['Bloque'] == 1) {
        header('Location: http://localhost/index.php?err=2');
        exit;
    }

    // Vérifier si le mot de passe est correct
    if (password_verify($password, $result['MotDePasse'])) {
        // Réinitialiser le nombre de tentatives
        $stmt = $bd->prepare("UPDATE Utilisateurs SET NBtentative = 0 WHERE NomUtilisateur = :username");
        $stmt->execute([':username' => $username]);

        // Démarrer la session et stocker le nom d'utilisateur
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $result['TypeUtilisateur'];

        // Rediriger vers la page d'accueil appropriée
        if ($result['TypeUtilisateur'] == 1) {
            header('Location: ../Admin/admin_home.php');
        } else {
            header('Location: ../User/user_home.php');
        }
        exit;
    } else {
        // Incrémenter le nombre de tentatives
        $stmt = $bd->prepare("UPDATE Utilisateurs SET NBtentative = NBtentative + 1 WHERE NomUtilisateur = :username");
        $stmt->execute([':username' => $username]);
        
        // Récupérer les détails de l'utilisateur mis à jour
        $stmt = $bd->prepare("SELECT * FROM Utilisateurs WHERE NomUtilisateur = :username");
        $stmt->execute([':username' => $username]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Vérifier si le nombre de tentatives a atteint 3
        if ($result['NBtentative'] >= 3) {
            // Bloquer l'utilisateur et réinitialiser le nombre de tentatives
            $stmt = $bd->prepare("UPDATE Utilisateurs SET Bloque = 1, NBtentative = 0 WHERE NomUtilisateur = :username");
            $stmt->execute([':username' => $username]);
            
            // Rediriger vers la page de connexion avec un code d'erreur
            header('Location: http://localhost/index.php?err=2');
            exit;
        }

        // Rediriger vers la page de connexion avec un code d'erreur
        header('Location: http://localhost/index.php?err=1');
        exit;
    }
} else {
    // Rediriger vers la page de connexion avec un code d'erreur
    header('Location: http://localhost/index.php?err=1');
    exit;
}
?>
