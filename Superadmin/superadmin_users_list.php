<?php
session_start();
include("../Database/base.php");
include("../Database/helpers.php"); 
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$currentUsername = $_SESSION['username'];

// Fonction pour filtrer les utilisateurs
function filterUsers($users, $typeToExclude) {
    return array_filter($users, function($user) use ($typeToExclude) {
        return $user['TypeUtilisateur'] != $typeToExclude;
    });
}

// Préparez et exécutez la requête SQL pour les utilisateurs
$stmt = $bd->prepare("SELECT NomUtilisateur, TypeUtilisateur, Bloque FROM Utilisateurs");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Filtrer les utilisateurs pour exclure les Superadmins
$users = filterUsers($users, 2);

foreach ($users as $index => $user) {
    if ($user['NomUtilisateur'] === $currentUsername) {
        unset($users[$index]);
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
  <link rel="stylesheet" href="/Styles/user_pb.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  <title>Rebooters Search</title>
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
<div class="menu">
    <a href="superadmin_users_list.php">Listes des utilisateurs</a>
    <a href="superadmin_banned_users.php">Modifier paramètres mot de passe</a>
    <a href="superadmin_bp.php">Gestion des bonnes pratiques</a>
    <a href="superadmin_editprog.php">Modifier un programmee</a>
    <a href="superadmin_addbp.php">Ajouter une bonne pratique</a>
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

    <h3>Ajouter un utilisateur</h3>
    <form action="../Database/add_user.php" method="post">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required>

        <label for="role">Rôle:</label>
        <select id="role" name="role">
            <option value="user">Utilisateur</option>
            <option value="admin">Administrateur</option>
        </select>

        <button type="submit">Ajouter l'utilisateur</button>
    </form>
</div>

<div class="content">
    <table>
        <tr>
            <th>Nom d'utilisateur</th>
            <th>Rôle</th>
            <th>Bloqué</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['NomUtilisateur']); ?></td>
                <td><?php echo $user['TypeUtilisateur'] == 1 ? 'Administrateur' : 'Utilisateur'; ?></td>
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

                                    <label for="new_username">Nouveau nom d'utilisateur:</label>
                                    <input type="text" id="new_username" name="new_username" value="<?php echo htmlspecialchars($user['NomUtilisateur']); ?>">

                                    <label for="new_role">Nouveau rôle:</label>
                                    <select id="new_role" name="new_role">
                                        <option value="0" <?php echo $user['TypeUtilisateur'] == 0 ? 'selected' : ''; ?>>Utilisateur</option>
                                        <option value="1" <?php echo $user['TypeUtilisateur'] == 1 ? 'selected' : ''; ?>>Administrateur</option>
                                    </select>

                                    <input type="submit" value="Modifier">
                                </form>
                                <?php
                            }
                            ?>
                            <?php if ($user['Bloque'] == 1): ?>
                                <?php if (isset($_GET['unblock']) && $_GET['unblock'] === $user['NomUtilisateur']): ?>
                                <!-- Formulaire de déblocage d'utilisateur -->
                                <form action="../Database/unblock_user.php" method="POST">
                                    <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['NomUtilisateur']); ?>">
                                    Nouveau mot de passe: <input type="password" name="new_password" required>
                                    <button type="submit">Débloquer</button>
                                </form>
                                <?php else: ?>
                                    <a href="?unblock=<?php echo urlencode($user['NomUtilisateur']); ?>">Débloquer</a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <!-- Le bouton Supprimer -->
                            <form action="../Database/delete_user.php" method="POST" onsubmit="return confirm('Êtes-vous sûr ?');">
                                <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['NomUtilisateur']); ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
