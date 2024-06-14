<?php
include("base.php");

if (isset($_POST['username'])) {
    $username = $_POST['username'];

    // Préparez et exécutez la requête SQL pour obtenir les détails de l'utilisateur
    $stmt = $bd->prepare("SELECT TypeUtilisateur FROM Utilisateurs WHERE NomUtilisateur = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Vérifiez le type d'utilisateur et mettez à jour l'état ou affichez le message approprié
        if ($user['TypeUtilisateur'] == 2) { // Super admin
            $message = "Vous avez oublié votre mot de passe. Veuillez contacter le chef de projet.";
        } else { // Autres utilisateurs
            // Bloquer le compte de l'utilisateur
            $stmt = $bd->prepare("UPDATE Utilisateurs SET Bloque = 1 WHERE NomUtilisateur = :username");
            $stmt->execute([':username' => $username]);
            $message = "Vous avez oublié votre mot de passe. Votre compte a été bloqué. Veuillez contacter un administrateur pour le déverrouiller.";
        }

        header("Location: /index.php?forgot_message=" . urlencode($message));
        exit;
    } else {
        // Si l'utilisateur n'existe pas, rediriger avec un message d'erreur
        header("Location: /index.php?forgot_message=" . urlencode("Utilisateur non trouvé."));
        exit;
    }
} else {
    header("Location: /index.php");
    exit;
}
?>
