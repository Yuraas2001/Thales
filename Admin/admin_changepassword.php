<?php
session_start();
include("../Database/base.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$currentUsername = $_SESSION['username'];
$message = "";

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Vérifier si les champs ne sont pas vides
    if (!empty($oldPassword) && !empty($newPassword) && !empty($confirmPassword)) {
        // Vérifier si le nouveau mot de passe et la confirmation correspondent
        if ($newPassword === $confirmPassword) {
            // Récupérer le mot de passe actuel de l'utilisateur
            $stmt = $bd->prepare("SELECT MotDePasse FROM Utilisateurs WHERE NomUtilisateur = :username");
            $stmt->execute([':username' => $currentUsername]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $storedPasswordHash = $user['MotDePasse'];
                
                // Si le mot de passe n'est pas haché, le hacher et le mettre à jour
                if (!password_get_info($storedPasswordHash)['algo']) {
                    $storedPasswordHash = password_hash($storedPasswordHash, PASSWORD_DEFAULT);
                    $updateStmt = $bd->prepare("UPDATE Utilisateurs SET MotDePasse = :hashed_password WHERE NomUtilisateur = :username");
                    $updateStmt->execute([':hashed_password' => $storedPasswordHash, ':username' => $currentUsername]);
                }

                // Vérifier si l'ancien mot de passe correspond
                if (password_verify($oldPassword, $storedPasswordHash)) {
                    // Mettre à jour le mot de passe
                    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                    $stmt = $bd->prepare("UPDATE Utilisateurs SET MotDePasse = :new_password WHERE NomUtilisateur = :username");
                    if ($stmt->execute([':new_password' => $newPasswordHash, ':username' => $currentUsername])) {
                        $message = "Mot de passe modifié avec succès.";
                    } else {
                        $message = "Erreur lors de la modification du mot de passe.";
                    }
                } else {
                    $message = "Ancien mot de passe incorrect.";
                }
            } else {
                $message = "Utilisateur non trouvé.";
            }
        } else {
            $message = "Les nouveaux mots de passe ne correspondent pas.";
        }
    } else {
        $message = "Tous les champs sont requis.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Styles/user.css">
    <link rel="stylesheet" href="/Styles/admin.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <title>Modifier le mot de passe</title>
    <style>
        .message {
            color: green;
        }
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
<nav>
    <div class="logo">
        <img src="/Images/logo.svg" alt="REBOOTERS Logo" height="200">
    </div>
    <div>
        <div class="user-menu">
            <a href="./admin_home.php" class="menu-button"><?php echo htmlspecialchars($currentUsername); ?></a>
            <button class="user-button">☰</button>
            <div class="user-dropdown">
                <a href="../Database/deconnex.php">Se déconnecter</a>
            </div>
        </div>
    </div>
</nav>

<div class="container">
    <div class="header">
        <h1>Modifier le mot de passe</h1>
    </div>
    <?php if ($message): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form action="admin_changepassword.php" method="post">
        <div class="form-group">
            <label for="old_password">Ancien mot de passe</label>
            <input type="password" id="old_password" name="old_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">Nouveau mot de passe</label>
            <input type="password" id="new_password" name="new_password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirmer le nouveau mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn">Modifier le mot de passe</button>
    </form>
</div>
</body>
</html>
