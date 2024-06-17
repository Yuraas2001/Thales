<?php
include("base.php");// Include the database connection file

if (isset($_POST['username'])) {// Check if the username is set in the POST request
    $username = $_POST['username'];

    // Prepare and execute the SQL query to get the user's details
    $stmt = $bd->prepare("SELECT TypeUtilisateur FROM Utilisateurs WHERE NomUtilisateur = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Check the user type and update the status or display the appropriate message
        if ($user['TypeUtilisateur'] == 2) { // Super admin
            $message = "Vous avez oublié votre mot de passe. Veuillez contacter le chef de projet.";
        } else { // Others users
            // Block the user's account
            $stmt = $bd->prepare("UPDATE Utilisateurs SET Bloque = 1 WHERE NomUtilisateur = :username");
            $stmt->execute([':username' => $username]);
            $message = "Vous avez oublié votre mot de passe. Votre compte a été bloqué. Veuillez contacter un administrateur pour le déverrouiller.";
        }
        // Redirect to the index page with the appropriate message
        header("Location: /index.php?forgot_message=" . urlencode($message));
        exit;
    } else {
         // If the user does not exist, redirect with an error message
        header("Location: /index.php?forgot_message=" . urlencode("Utilisateur non trouvé."));
        exit;
    }
} else {
     // If the username is not set, redirect to the index page
    header("Location: /index.php");
    exit;
}
?>
