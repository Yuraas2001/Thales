<?php
session_start();
include("../Database/base.php");

$currentUsername = $_SESSION['username'];

// Préparez et exécutez la requête SQL pour les utilisateurs
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

// Supprimer temporairement une bonne pratique
if (isset($_POST['action']) && $_POST['action'] == 'delete_practice') {
    $delete_id = $_POST['id'];
    $query = $bd->prepare("UPDATE BonnesPratiques SET Etat = 1 WHERE IDBonnePratique = :id");
    $query->execute([':id' => $delete_id]);
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
        <img src="/Images/logo.svg" alt="REBOOTERS Logo" height="200">
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
    <a href="admin_editprog.php">Modifier un programme</a>
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
        include '../Database/base.php';
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
        $query = $bd->prepare("SELECT DISTINCT NomPhase FROM Phases");
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
    <div class="export-button">
  <a href="export_pdf.php" class="button primary">Exporter en PDF</a>
  <a href="export_excel.php" class="button primary">Exporter en Excel</a>
</div>
                <?php

                $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
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
                        WHERE BonnesPratiques.Etat = 0"; // Exclure les bonnes pratiques marquées comme supprimées

                if ($keyword !== '') {
                    $sql .= " AND BonnesPratiques.IDBonnePratique IN (
                        SELECT PratiqueMotsCles.IDBonnePratique
                        FROM PratiqueMotsCles
                        INNER JOIN MotsCles ON PratiqueMotsCles.IDMotsCles = MotsCles.IDMotsCles
                        WHERE MotsCles.NomMotsCles = :keyword
                    )";
                }

                if ($program !== '') {
                    $sql .= " AND BonnesPratiques.IDBonnePratique IN (
                        SELECT PratiqueProg.IDBonnePratique
                        FROM PratiqueProg
                        INNER JOIN Programmes ON PratiqueProg.IDProgramme = Programmes.IDProgramme
                        WHERE Programmes.NomProgramme = :program
                    )";
                }

                if ($phase !== '') {
                    $sql .= " AND Phases.NomPhase = :phase";
                }

                $stmt = $bd->prepare($sql);

                if ($keyword !== '') {
                    $stmt->bindValue(':keyword', $keyword);
                }
                if ($program !== '') {
                    $stmt->bindValue(':program', $program);
                }
                if ($phase !== '') {
                    $stmt->bindValue(':phase', $phase);
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
                    
                }
                ?>
                </tbody>
        </table>
    </div>
</div>


</body>
</html>
