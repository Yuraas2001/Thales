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
        <img src="Images/logo.svg" alt="REBOOTERS Logo" height="200" > <!-- Ajustez le chemin et la taille -->
       
    </div>
   
    <div>
       
        <div class="user-menu">
        <a href="./admin_home.php" class="menu-button">Admin</a> <button class="user-button">☰</button>
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
    <div id="add-remove" class="section">
        <h3>Liste des Utilisateurs</h3>
        <table>
            <tr>
                <th>Nom</th>
                <th>Mot de passe</th>
                <th>Action</th>
            </tr>
            <tr>
                <td>Alice Dupont</td>
                <td>123456</td>
                <td><button>Modifier</button> <button>Supprimer</button></td>
            </tr>
            <!-- D'autres utilisateurs ici -->
        </table>
        <h3>Ajouter un Utilisateur</h3>
        <form>
            <input type="text" placeholder="Nom">
            <input type="text" placeholder="Mot de passe">
            <button type="submit">Ajouter</button>
        </form>
    </div>

    <div id="set-password" class="section">
        
    </div>

  
</div>
</section>
</body>
</html>
      
</body>