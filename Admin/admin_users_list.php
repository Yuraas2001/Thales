<?php
    include("../Database/base.php");

    // Prepare and execute the SQL statement
    $stmt = $bd->prepare("SELECT NomUtilisateur, TypeUtilisateur, Bloque FROM Utilisateurs");
    $stmt->execute();

    // Fetch all the results
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $usernameToModify = isset($_GET['modify']) ? $_GET['modify'] : null;
    session_start();
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
<div class="menu">
    <a href="admin_users_list.php">Listes des utilisateurs</a>
    <a href="admin_bp.php">Gestion des bonnes pratiques</a>
    <a href="admin_banned_users.php">Modifier mot de passe verrouillé</a>
</div>
<div class="content">
    <table>
        <tr>
            <th>Nom</th>
            <th>Role</th>
            <th>Bloqué</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['NomUtilisateur']); ?></td>
                <td><?php echo $user['TypeUtilisateur'] == 1 ? 'Admin' : 'User'; ?></td>
                <td><?php echo $user['Bloque'] == 1 ? 'Oui' : ''; ?></td>
                <td>
                    <?php if ($usernameToModify === $user['NomUtilisateur']): ?>
                        <form action="../Database/change_role.php" method="post">
                            <input type="hidden" name="old_username" value="<?php echo htmlspecialchars($user['NomUtilisateur']); ?>">
                            New Username: <input type="text" name="new_username" value="<?php echo htmlspecialchars($user['NomUtilisateur']); ?>"><br>
                            New Role: <select name="new_role">
                                <option value="0" <?php echo $user['TypeUtilisateur'] == 0 ? 'selected' : ''; ?>>User</option>
                                <option value="1" <?php echo $user['TypeUtilisateur'] == 1 ? 'selected' : ''; ?>>Admin</option>
                            </select><br>
                            <button type="submit">Submit Changes</button>
                        </form>
                    <?php else: ?>
                        <a href="?modify=<?php echo urlencode($user['NomUtilisateur']); ?>">Modifier</a>
                        <?php if ($_SESSION['username'] !== $user['NomUtilisateur']): ?>
                            <form action="../Database/delete_user.php" method="POST">
                                <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['NomUtilisateur']); ?>">
                                <button type="submit">Delete</button>
                            
                            </form>
                            <?php if ($user['Bloque'] == 1): ?>
                                <form action="../Database/unblock_user.php" method="POST">
                                     <input type="hidden" name="username" value="<?php echo htmlspecialchars($user['NomUtilisateur']); ?>">
                                    <button type="submit">Débloquer</button>
                                 </form>
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