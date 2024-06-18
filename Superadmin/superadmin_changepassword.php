<?php
session_start();
include("../Database/base.php");
include("../Database/helpers.php"); 

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect the user to the login page if they are not logged in
    header("Location: login.php");
    exit;
}

$currentUsername = $_SESSION['username'];// Get the current username from session
$message = "";// Initialize a message variable

// Retrieve password requirements
$stmt = $bd->prepare("SELECT * FROM PasswordRequirements LIMIT 1");
$stmt->execute();
$requirements = $stmt->fetch(PDO::FETCH_ASSOC);

if ($requirements !== false) {
    $n = $requirements['n']; // Number of numeric characters
    $p = $requirements['p']; // Number of lowercase alphabetic characters
    $q = $requirements['q']; // Number of uppercase alphabetic characters
    $r = $requirements['r']; // Number of special characters
} else {
    $n = $p = $q = $r = 0;
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if the fields are not empty
    if (!empty($oldPassword) && !empty($newPassword) && !empty($confirmPassword)) {
        // Check if the new password and confirmation match
        if ($newPassword === $confirmPassword) {
            // Validate the new password
            function validatePassword($password, $n, $p, $q, $r) {
                $number    = preg_match_all('@[0-9]@', $password) >= $n;
                $lowercase = preg_match_all('@[a-z]@', $password) >= $p;
                $uppercase = preg_match_all('@[A-Z]@', $password) >= $q;
                $special   = preg_match_all('@[^\w]@', $password) >= $r;

                if(!$number || !$lowercase || !$uppercase || !$special || strlen($password) < 8) {
                    return false;
                }
                return true;
            }

            if (!validatePassword($newPassword, $n, $p, $q, $r)) {
                $message = "Le mot de passe doit contenir au moins $n caractères numériques, $p caractères alphabétiques minuscules, $q caractères alphabétiques majuscules et $r caractères spéciaux.";
            } else {
                // Retrieve the current password of the user
                $stmt = $bd->prepare("SELECT MotDePasse FROM Utilisateurs WHERE NomUtilisateur = :username");
                $stmt->execute([':username' => $currentUsername]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    $storedPasswordHash = $user['MotDePasse'];
                    
                    // If the password is not hashed, hash it and update
                    if (!password_get_info($storedPasswordHash)['algo']) {
                        // Check if the old password matches
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
        <a href="./superadmin_home.php" class="menu-button"><?php echo displayUsername($currentUsername, 'superadmin'); ?></a>
            <button class="user-button">☰</button>
            <div class="user-dropdown">
            <a href="./superadmin_changepassword.php">Modifier le mot de passe</a>
                <a href="../Database/deconnex.php">Se déconnecter</a>
            </div>
        </div>
    </div>
</nav>

<div class="container">
    <div class="header">
        <h1>Modifier le mot de passe</h1>
    </div>
    <?php if ($message): ?><!-- Display a message if it exists -->
        <p class="<?php echo strpos($message, 'succès') !== false ? 'message' : 'error-message'; ?>"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form action="superadmin_changepassword.php" method="post">
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

    <div class="requirements-container">
        <h3>Exigences du mot de passe</h3>
        <p>Nombre de caractères numériques : <?php echo $n; ?></p>
        <p>Nombre de caractères alphabétiques minuscules : <?php echo $p; ?></p>
        <p>Nombre de caractères alphabétiques majuscules : <?php echo $q; ?></p>
        <p>Nombre de caractères spéciaux : <?php echo $r; ?></p>
    </div>
</div>
</body>
</html>
