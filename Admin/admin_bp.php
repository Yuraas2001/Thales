<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/Styles/user.css">
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
  <a href="admin_banned_users.php">Modifier paramètres mot de passe</a>
  <a href="admin_add_bp.php">Ajouter une bonne pratique</a>

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
        include("../Database/base.php");

        // Préparez la requête SQL
        $stmt = $bd->prepare("
          SELECT BonnesPratiques.IDBonnePratique, Programmes.NomProgramme, Phases.NomPhase, BonnesPratiques.Description, MotsCles.NomMotsCles
          FROM PratiqueProg
          INNER JOIN Programmes ON PratiqueProg.IDProgramme = Programmes.IDProgramme
          INNER JOIN PratiquePhases ON PratiqueProg.IDBonnePratique = PratiquePhases.IDBonnePratique
          INNER JOIN Phases ON PratiquePhases.IDPhase = Phases.IDPhase
          INNER JOIN PratiqueMotsCles ON PratiqueProg.IDBonnePratique = PratiqueMotsCles.IDBonnePratique
          INNER JOIN MotsCles ON PratiqueMotsCles.IDMotsCles = MotsCles.IDMotsCles
          INNER JOIN BonnesPratiques ON PratiqueProg.IDBonnePratique = BonnesPratiques.IDBonnePratique
        ");

        // Exécutez la requête
        $stmt->execute();

        // Récupérez les résultats
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Regroupez les résultats par bonne pratique
        // Regroupez les résultats par bonne pratique, programme et mot clé
        $groupedResults = [];
        foreach ($results as $row) {
          $id = $row['IDBonnePratique'];
          $program = $row['NomProgramme'];
          $keyword = $row['NomMotsCles'];

          if (!isset($groupedResults[$id])) {
            $groupedResults[$id] = $row;
            $groupedResults[$id]['NomProgramme'] = [$program];
            $groupedResults[$id]['NomMotsCles'] = [$keyword];
          } else {
            if (!in_array($program, $groupedResults[$id]['NomProgramme'])) {
              $groupedResults[$id]['NomProgramme'][] = $program;
            }
            if (!in_array($keyword, $groupedResults[$id]['NomMotsCles'])) {
              $groupedResults[$id]['NomMotsCles'][] = $keyword;
            }
          }
        }

        // Parcourez les résultats groupés et créez des lignes de tableau
        foreach ($groupedResults as $row) {
          echo "<tr>";
          echo "<td>" . htmlspecialchars(implode(", ", $row['NomProgramme'])) . "</td>";
          echo "<td>" . htmlspecialchars($row['NomPhase']) . "</td>";
          echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
          echo "<td>" . htmlspecialchars(implode(", ", $row['NomMotsCles'])) . "</td>";
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
      
</body>