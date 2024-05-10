<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/Styles/user.css">
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
        <a href="./user_home.php" class="menu-button">User</a> 
          <button class="user-button">☰</button>
          <div class="user-dropdown">
            <a href="./user_bp.php">Paramètres</a>
            <a href="../Database/deconnex.php">Se déconnecter</a>
          </div>
        </div>
      </div>
</nav>


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

<!-- Votre section de résultats ici -->
 
 <div class="container">
  <h2 class="results-title">Résultats</h2>
 <div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Programme</th>
                <th>Phase</th>
                <th>Description</th>
                <th>Mots Clés</th>
                <th>Modification</th>
               
                
               
               
            </tr>
        </thead>
        <tbody>
        <?php
            include("../Database/fetch_data.php");

            // Loop through the results and create table rows
            foreach ($results as $row) {
              echo "<tr class='highlight'>";
              echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
              echo "<td>" . htmlspecialchars($row['NomPhase']) . "</td>";
              echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
              echo "<td>" . htmlspecialchars($row['NomMotsCles']) . "</td>";
              echo "<td><button onclick='modifyRow(this)'>Modifier</button></td>";
              echo "</tr>";
            }
          ?>
            
        </tbody>
    </table>
</div>
</div>
    <div class="export-button">
        <button class="button primary">Exporter le Tableau</button>
      </div>

</body>
</html>
