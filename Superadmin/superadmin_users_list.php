<?php
session_start();
include("../Database/base.php");

$currentUsername = $_SESSION['username'];

// Préparez et exécutez la requête SQL
$stmt = $bd->prepare("SELECT NomUtilisateur, TypeUtilisateur, Bloque FROM Utilisateurs");
$stmt->execute();

// Récupérez tous les résultats
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $index => $user) {
    if ($user['NomUtilisateur'] === $currentUsername) {
        // Retirez l'utilisateur de sa position actuelle
        unset($users[$index]);

        // Ajoutez l'utilisateur au début du tableau
        array_unshift($users, $user);

        break;
    }
}

$usernameToModify = isset($_GET['modify']) ? $_GET['modify'] : null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/Styles/admin.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  <title>Rebooters Search</title>
</head>
<body>
<nav>
    <div class="logo">
        <img src="Images/logo.svg" alt="REBOOTERS Logo" height="200">
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
<div class="menu">
  <a href="superadmin_users_list.php">Listes des utilisateurs</a>
  <a href="superadmin_bp.php">Gestion des bonnes pratiques</a>
  <a href="superadmin_editprog.php">Modifier un programme</a>
</div>

<style>
.error {
    color: red;
}
</style>
<div class="content">
<?php
    if (isset($_SESSION['error'])) {
        echo "<p class='error'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    }
?>

<div class="content">
    <h3>Ajouter un utilisateur</h3>
    <form action="../Database/add_user.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="role">Role:</label>
        <select id="role" name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
            <option value="superadmin">Superadmin</option>
        </select>

        <button type="submit">Add User</button>
    </form>
</div>

<div class="content">
    <table>
        <tr>
            <th>Username</th>
            <th>Rôle</th>
            <th>Bloqué</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['NomUtilisateur']); ?></td>
                <td><?php echo $user['TypeUtilisateur'] == 'superadmin' ? 'Superadmin' : ($user['TypeUtilisateur'] == 'admin' ? 'Admin' : 'User'); ?></td>
                <td><?php echo $user['Bloque'] == 1 ? 'Oui' : ''; ?></td>
                <td>
                    <?php if ($usernameToModify === $user['NomUtilisateur'] && $currentUsername !== $user['NomUtilisateur']): ?>
                        <!-- Formulaire de changement de rôle -->
                    <?php else: ?>
                        <?php if ($currentUsername !== $user['NomUtilisateur']): ?>
                            <!-- Le bouton Modifier -->
                            <a href="?edit=<?php echo urlencode($user['NomUtilisateur']); ?>">Modifier</a>

                            <?php
                            if (isset($_GET['edit']) && $_GET['edit'] == $user['NomUtilisateur']) {
                                ?>
                                <form action="../Database/change_role.php" method="post">
                                    <input type="hidden" name="old_username" value="<?php echo htmlspecialchars($user['NomUtilisateur']); ?>">

                                    <label for="new_username">New Username:</label>
                                    <input type="text" id="new_username" name="new_username" value="<?php echo htmlspecialchars($user['NomUtilisateur']); ?>">

                                    <label for="new_role">New Role:</label>
                                    <select id="new_role" name="new_role">
                                        <option value="user" <?php echo $user['TypeUtilisateur'] == 'user' ? 'selected' : ''; ?>>User</option>
                                        <option value="admin" <?php echo $user['TypeUtilisateur'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        <option value="superadmin" <?php echo $user['TypeUtilisateur'] == 'superadmin' ? 'selected' : ''; ?>>Superadmin</option>
                                    </select>

                                    <input type="submit" value="Modifier">
                                </form>
                                <?php
                            }
                            ?>
                            <?php if ($user['Bloque'] == 1): ?>
                                <form action="../Database/superunblock_user.php" method="POST">
                                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['NomUtilisateur']); ?>">
                                    <button type="submit">Débloquer</button>
                                </form>
                            <?php else: ?>
                                <?php if ($user['NomUtilisateur'] !== $currentUsername): ?>
                                    <form action="../Database/superblock_user.php" method="POST">
                                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['NomUtilisateur']); ?>">
                                        <button type="submit">Bloquer</button>
                                    </form>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
