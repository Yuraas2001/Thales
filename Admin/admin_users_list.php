<?php
    include("../Database/base.php");

    // Prepare and execute the SQL statement
    $stmt = $bd->prepare("SELECT NomUtilisateur, TypeUtilisateur, Bloque FROM Utilisateurs");
    $stmt->execute();

    // Fetch all the results
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    session_start();
    $currentUsername = $_SESSION['username'];

    foreach ($users as $index => $user) {
        if ($user['NomUtilisateur'] === $currentUsername) {
            // Remove the user from its current position
            unset($users[$index]);

            // Add the user at the beginning of the array
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
        <img src="Images/logo.svg" alt="REBOOTERS Logo" height="200" >
    </div>
    <div>
        <div class="user-menu">
            <a href="./admin_home.php" class="menu-button">Admin</a> 
            <button class="user-button">☰</button>
            <div class="user-dropdown">
                <a href="../Database/deconnex.php">Se déconnecter</a>
            </div>
        </div>
    </div>
</nav>

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
        </select>

        <button type="submit"> Add User </button>
    </form>
</div>
<div class="menu">
    <a href="admin_users_list.php">Listes des utilisateurs</a>
    <a href="admin_bp.php">Gestion des bonnes pratiques</a>
    <a href="admin_banned_users.php">Modifier mot de passe verrouillé</a>
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
                <td><?php echo $user['TypeUtilisateur'] == 1 ? 'Admin' : 'User'; ?></td>
                <td><?php echo $user['Bloque'] == 1 ? 'Oui' : ''; ?></td>
                <td>
                    <?php if ($usernameToModify === $user['NomUtilisateur'] && $currentUsername !== $user['NomUtilisateur']): ?>
                        <!-- Change role form -->
                    <?php else: ?>
                        <?php if ($currentUsername !== $user['NomUtilisateur']): ?>
                            <!-- The Modifier button -->
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
                                <option value="0" <?php echo $user['TypeUtilisateur'] == 'User' ? 'selected' : ''; ?>>User</option>
                                <option value="1" <?php echo $user['TypeUtilisateur'] == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>

                            <input type="submit" value="Modifier">
                        </form>
                        <?php
                    }
                    ?>
                        <?php if ($user['Bloque'] == 1): ?>
                                <?php if (isset($_GET['unblock']) && $_GET['unblock'] === $user['NomUtilisateur']): ?>
                                   <!-- Unblock User form -->
                                    <form action="../Database/unblock_user.php" method="POST">
                                        <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['NomUtilisateur']); ?>">
                                        New Password: <input type="password" name="new_password">
                                        <button type="submit">Submit</button>
                                    </form>
                                <?php else: ?>
                                    <a href="?unblock=<?php echo urlencode($user['NomUtilisateur']); ?>">Débloquer</a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <!-- The Delete button -->
                            <form action="../Database/delete_user.php" method="POST" onsubmit="return confirm('Are you sure ?');">
                                <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['NomUtilisateur']); ?>">
                                <button type="submit">Delete</button>
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