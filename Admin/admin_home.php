<?php
session_start();
include("../Database/base.php");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}
$currentUsername = $_SESSION['username']; // Get the current username from session


if (isset($_POST['action']) && $_POST['action'] == 'delete_bp') {
   $bpId = $_POST['bp_id'];
   $query = $bd->prepare("UPDATE BonnesPratiques SET is_deleted = TRUE WHERE IDBonnePratique = :bp_id");
   $query->execute([':bp_id' => $bpId]);
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
   <title>Rebooters Search</title>
</head>
<body>
<nav>
   <div class="logo">
       <img src="/Images/logo.svg" alt="REBOOTERS Logo" height="200" >
   </div>
   <div>
       <div class="user-menu">
           <a href="./admin_home.php" class="menu-button"><?php echo htmlspecialchars($currentUsername); ?></a>
           <button class="user-button">☰</button>
           <div class="user-dropdown">
               <a href="./admin_changepassword.php">Modifier le mot de passe</a>
               <a href="../Database/deconnex.php">Se déconnecter</a>
           </div>
       </div>
   </div>
</nav>
<div class="menu">
   <a href="admin_users_list.php">Listes des utilisateurs</a>
   <a href="admin_banned_users.php">Modifier paramètres mot de passe</a>
   <a href="admin_bp.php">Gestion des bonnes pratiques</a>
   <a href="admin_editprog.php">Modifier un programme</a>
   <a href="admin_addbp.php">Ajouter une bonne pratique</a>
</div>
<style>
.reset-button {
   margin-left: 10px;
}
</style>


<div class="search-container">
   <form action="admin_home.php" method="get">
       <input type="text" name="keyword" placeholder="Entrer un mot clé...">
      
       <label for="program">Programme</label>
       <?php
       $query = $bd->prepare("SELECT DISTINCT NomProgramme FROM Programmes");
       $query->execute();
       $programmes = $query->fetchAll(PDO::FETCH_COLUMN);
       ?>
       <select name="program">
           <option value="">Programme</option>
           <?php foreach ($programmes as $programme): ?>
               <option value="<?php echo htmlspecialchars($programme); ?>"><?php echo htmlspecialchars($programme); ?></option>
           <?php endforeach; ?>
       </select>


       <label for="phase">Phase</label>
       <?php
       $query = $bd->prepare("SELECT DISTINCT NomPhase FROM Phases ORDER BY FIELD(NomPhase, 'codage', 'exécution', 'analyse', 'préparation')");
       $query->execute();
       $phases = $query->fetchAll(PDO::FETCH_COLUMN);
       ?>
       <select name="phase">
           <option value="">Phase</option>
           <?php foreach ($phases as $phase): ?>
               <option value="<?php echo htmlspecialchars($phase); ?>"><?php echo htmlspecialchars($phase); ?></option>
           <?php endforeach; ?>
       </select>


       <button class="search-button">
           <i class="fa fa-search"></i>
       </button>
   </form>
   <a href="admin_home.php" class="reset-button" style="margin-left: 10px;">
       <i class="fa fa-refresh"></i>
   </a>
</div>


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
               </tr>
           </thead>
           <tbody>
           <?php
           $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
           strpos($keyword, ',') !== false ? $keywords = explode(',', $keyword) : $keywords = [$keyword];
           $program = isset($_GET['program']) ? $_GET['program'] : '';
           $phase = isset($_GET['phase']) ? $_GET['phase'] : '';


           $sql = "SELECT BonnesPratiques.IDBonnePratique, Programmes.NomProgramme, Phases.NomPhase, BonnesPratiques.Description, MotsCles.NomMotsCles
                   FROM PratiqueProg
                   INNER JOIN Programmes ON PratiqueProg.IDProgramme = Programmes.IDProgramme
                   INNER JOIN PratiquePhases ON PratiqueProg.IDBonnePratique = PratiquePhases.IDBonnePratique
                   INNER JOIN Phases ON PratiquePhases.IDPhase = Phases.IDPhase
                   INNER JOIN PratiqueMotsCles ON PratiqueProg.IDBonnePratique = PratiqueMotsCles.IDBonnePratique
                   INNER JOIN MotsCles ON PratiqueMotsCles.IDMotsCles = MotsCles.IDMotsCles
                   INNER JOIN BonnesPratiques ON PratiqueProg.IDBonnePratique = BonnesPratiques.IDBonnePratique
                   WHERE BonnesPratiques.Etat = FALSE";


           $conditions = [];
           $params = [];
                 
           if ($keyword !== '') {
               $placeholders = implode(',', array_fill(0, count($keywords), '?'));
               $conditions[] = "BonnesPratiques.IDBonnePratique IN (
                               SELECT PratiqueMotsCles.IDBonnePratique
                               FROM PratiqueMotsCles
                               INNER JOIN MotsCles ON PratiqueMotsCles.IDMotsCles = MotsCles.IDMotsCles
                               WHERE MotsCles.NomMotsCles IN ($placeholders)
                           )";
               foreach ($keywords as $word) {
                   $params[] = trim($word);
               }
           }


           if ($program !== '') {
               $conditions[] = "BonnesPratiques.IDBonnePratique IN (
                               SELECT PratiqueProg.IDBonnePratique
                               FROM PratiqueProg
                               INNER JOIN Programmes ON PratiqueProg.IDProgramme = Programmes.IDProgramme
                               WHERE Programmes.NomProgramme = ? OR Programmes.NomProgramme = 'GENERIC'
                           )";
               $params[] = $program;
           }


           if ($phase !== '') {
               $conditions[] = "Phases.NomPhase = ?";
               $params[] = $phase;
           }


           if (!empty($conditions)) {
               $sql .= ' AND ' . implode(' AND ', $conditions);
           }


           $sql .= " ORDER BY FIELD(Phases.NomPhase, 'codage', 'exécution', 'analyse', 'préparation')";


           $stmt = $bd->prepare($sql);


           foreach ($params as $index => $param) {
               $stmt->bindValue($index + 1, $param);
           }


           $stmt->execute();
           $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


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


           foreach ($groupedResults as $row) {
               echo "<tr>";
               echo "<td>" . htmlspecialchars(implode(", ", $row['NomProgramme'])) . "</td>";
               echo "<td>" . htmlspecialchars($row['NomPhase']) . "</td>";
               echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
               echo "<td>" . htmlspecialchars(implode(", ", $row['NomMotsCles'])) . "</td>";
               echo "</tr>";
           }
           ?>
           </tbody>
       </table>
   </div>
</div>
<div class="export-button">
   <form action="../Python/export.php" method="POST">
       <?php
       // Récupérer les valeurs de l'URL (si disponibles)
       $keyword = isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '';
       $program = isset($_GET['program']) ? htmlspecialchars($_GET['program']) : '';
       $phase = isset($_GET['phase'])

 ? htmlspecialchars($_GET['phase']) : '';
       ?>


       <input type="hidden" name="keyword" value="<?php echo $keyword; ?>">
       <input  type="hidden" name="program" value="<?php echo $program; ?>">
       <input  type="hidden" name="phase" value="<?php echo $phase; ?>">
      
       <label for="format">Format:</label>
       <select name="format" id="format">
           <option value="Excel">CSV</option>
           <option value="PDF">PDF</option>
       </select>
      
       <button type="submit" class="button primary">Exporter le Tableau</button>
   </form>
</div>


</div>
</body>
</html>
