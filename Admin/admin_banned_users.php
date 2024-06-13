<?php
    session_start();
    include("../Database/base.php");

    
    $currentUsername = $_SESSION['username'];

    
    $stmt = $bd->prepare("SELECT NomUtilisateur, TypeUtilisateur, Bloque FROM Utilisateurs");
    $stmt->execute();

    
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
  <link rel="stylesheet" href="user.css">
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
        <a href="./admin_home.php" class="menu-button"><?php echo htmlspecialchars($currentUsername); ?></a> 
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
  <a href="admin_editprog.php">Modifier ue programme</a>
</div>
</section>
<div class="search-container">
 
  <input type="text" placeholder="Entrer un mot clé...">
  <select>
    <form action="/chemin-vers-votre-serveur-de-recherche" method="get">
      <option value="option1">Programme</option>
      <option value="option2">Prog_1</option>
      <option value="option2">Prog_2</option>
      <option value="option2">Prog_3</option>
      <option value="option2">Prog_4</option>
  </select>
  <select>
      <option value="optionA">Execution</option>
      <option value="optionB">Codage</option>
      <option value="optionB">Analyse</option>
      <option value="optionB">Préparation</option>
  </select>
  <button class="search-button">
      <i class="fa fa-search"></i> 
  </button>
</div>
</form>   
</section>


 
 <div class="container">
  <h2 class="results-title">Résultats</h2>
 <div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Programme</th>
                <th>Colonne 2</th>
                <th>Colonne 3</th>
                <th>Colonne 4</th>
                <th>Modification</th>
               
                
               
               
            </tr>
        </thead>
        <tbody>
            <tr class="highlight">
                <td>Objet 1</td>
                <td>La description</td>
                <td>La description</td>
                <td>La description</td>
                <td>
                  <button onclick="modifyRow(this)">Modifier</button>
                </td>
                
               
               
            </tr>
            <tr class="highlight">
              <td>Objet 1</td>
              <td>La description</td>
              <td>La description</td>
              <td>La description</td>
              <td>
                <button onclick="modifyRow(this)">Modifier</button>
              </td>
              
              
             
          </tr>
          <tr class="highlight">
            <td>Objet 1</td>
            <td>La description</td>
            <td>La description</td>
            <td>La description</td>
            <td>
              <button onclick="modifyRow(this)">Modifier</button>
            </td>
            <tr class="highlight">
              <td>Objet 1</td>
              <td>La description</td>
              <td>La description</td>
              <td>La description</td>
              <td>
                <button onclick="modifyRow(this)">Modifier</button>
              </td>
              
           
           
        </tr>
        <tr class="highlight">
          <td>Objet 1</td>
          <td>La description</td>
          <td>La description</td>
          <td>La description</td>
          <td>
            <button onclick="modifyRow(this)">Modifier</button>
          </td>
          
         
      
            
        </tbody>
    </table>
</div>
</body>
</html>
      
</body>