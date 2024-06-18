<?php
session_start();
include("../Database/base.php");
include("../Database/helpers.php"); 

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}
$currentUsername = $_SESSION['username'];
// Prepare and execute the query to get user details
$stmt = $bd->prepare("SELECT NomUtilisateur, TypeUtilisateur, Bloque FROM Utilisateurs");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Move the current user to the beginning of the users array
foreach ($users as $index => $user) {
    if ($user['NomUtilisateur'] === $currentUsername) {
        unset($users[$index]);
        array_unshift($users, $user);
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<style>
  .container form {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }

    .container form label,
    .container form input {
        flex: 1 0 40%;
        margin: 5px;
    }

    .container form input[type="number"] {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .container form input[type="submit"] {
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .container form input[type="submit"]:hover {
        background-color: #45a049;
    }
</style>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="user.css">
  <link rel="stylesheet" href="/Styles/admin.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  <title>Rebooters Search</title>
</head>
<body>

<nav>
    <div class="logo">
        <img src="/Images/logo.svg" alt="REBOOTERS Logo" height="200"> <!-- Ajustez le chemin et la taille -->
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
<div class="menu">
    <a href="superadmin_users_list.php">Listes des utilisateurs</a>
    <a href="superadmin_banned_users.php">Modifier paramètres mot de passe</a>
    <a href="superadmin_bp.php">Gestion des bonnes pratiques</a>
    <a href="superadmin_editprog.php">Modifier un programmee</a>
    <a href="superadmin_addbp.php">Ajouter une bonne pratique</a>
</div>

<?php
    include("../Database/base.php");

    // Get the current password requirements from the database
    $stmt = $bd->prepare("SELECT * FROM PasswordRequirements");
    $stmt->execute();
    $requirements = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($requirements) {
        $n = $requirements['n']; // Number of numeric characters
        $p = $requirements['p']; // Number of lowercase alphabetic characters
        $q = $requirements['q']; // Number of uppercase alphabetic characters
        $r = $requirements['r']; // Number of special characters
    } else {
        $n = $p = $q = $r = 0; // Default values if no requirements are found
    }
?>

<div class="container">
  <h2>Paramètres du mot de passe</h2>
  <form action="../Database/update_password_requirements.php" method="post">
    <label for="n">Nombre de caractères numériques :</label>
    <input type="number" id="n" name="n" min="0" value="<?php echo $n; ?>" required>

    <label for="p">Nombre de caractères alphabétiques minuscules :</label>
    <input type="number" id="p" name="p" min="0" value="<?php echo $p; ?>" required>

    <label for="q">Nombre de caractères alphabétiques majuscules :</label>
    <input type="number" id="q" name="q" min="0" value="<?php echo $q; ?>" required>

    <label for="r">Nombre de caractères spéciaux :</label>
    <input type="number" id="r" name="r" min="0" value="<?php echo $r; ?>" required>

    <input type="submit" value="Mettre à jour">
  </form>
</div>

</body>
</html>
