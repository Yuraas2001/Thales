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
    <a href="admin_banned_users.php">Modifier paramètres mot de passe</a>
    <a href="admin_add_bp.php">Ajouter une bonne pratique</a>

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
        // Prepare the SQL query
        $query = $bd->prepare("SELECT DISTINCT NomProgramme FROM Programmes");

        // Execute the query
        $query->execute();

        // Fetch the results
        $programmes = $query->fetchAll(PDO::FETCH_COLUMN);
        ?>
        <select name="program">
            <option value="">Programme</option>
            <?php foreach ($programmes as $programme): ?>
                <option value="<?php echo $programme; ?>"><?php echo $programme; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="phase">Phase</label>
        <?php
        // Prepare the SQL query
            $query = $bd->prepare("SELECT DISTINCT NomPhase FROM Phases ORDER BY FIELD(NomPhase, 'préparation', 'codage', 'exécution', 'analyse')");

            // Exécution de la requête
            $query->execute();

            // Récupération des résultats
            $phases = $query->fetchAll(PDO::FETCH_COLUMN);

        ?>
        <select name="phase">
            <option value="">Phase</option>
            <?php foreach ($phases as $phase): ?>
                <option value="<?php echo $phase; ?>"><?php echo $phase; ?></option>
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
                                <th>Modification</th>

                        </tr>
                </thead>
                <tbody>
                <?php

            

                $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
                strpos($keyword, ',') !== false ? $keywords = explode(',', $keyword) : $keywords = [$keyword];
                $program = isset($_GET['program']) ? $_GET['program'] : '';
                $phase = isset($_GET['phase']) ? $_GET['phase'] : '';

                // Préparation de la requête SQL
                    $sql = "SELECT BonnesPratiques.IDBonnePratique, Programmes.NomProgramme, Phases.NomPhase, BonnesPratiques.Description, MotsCles.NomMotsCles
                    FROM PratiqueProg
                    INNER JOIN Programmes ON PratiqueProg.IDProgramme = Programmes.IDProgramme
                    INNER JOIN PratiquePhases ON PratiqueProg.IDBonnePratique = PratiquePhases.IDBonnePratique
                    INNER JOIN Phases ON PratiquePhases.IDPhase = Phases.IDPhase
                    INNER JOIN PratiqueMotsCles ON PratiqueProg.IDBonnePratique = PratiqueMotsCles.IDBonnePratique
                    INNER JOIN MotsCles ON PratiqueMotsCles.IDMotsCles = MotsCles.IDMotsCles
                    INNER JOIN BonnesPratiques ON PratiqueProg.IDBonnePratique = BonnesPratiques.IDBonnePratique";

                    $conditions = [];
                    $params = [];

                    // Ajout des conditions de recherche par mot clé
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

                    // Ajout des conditions de recherche par programme
                    if ($program !== '') {
                    $conditions[] = "BonnesPratiques.IDBonnePratique IN (
                                    SELECT PratiqueProg.IDBonnePratique
                                    FROM PratiqueProg
                                    INNER JOIN Programmes ON PratiqueProg.IDProgramme = Programmes.IDProgramme
                                    WHERE Programmes.NomProgramme = ? OR Programmes.NomProgramme = 'GENERIC'
                                )";
                    $params[] = $program;
                    }

                    // Ajout des conditions de recherche par phase
                    if ($phase !== '') {
                    $conditions[] = "Phases.NomPhase = ?";
                    $params[] = $phase;
                    }

                    // Concaténation des conditions avec 'WHERE' ou 'AND' selon le cas
                    if (!empty($conditions)) {
                    $sql .= ' WHERE ' . implode(' AND ', $conditions);
                    }

                    // Ajout du tri par phase
                    $sql .= " ORDER BY FIELD(Phases.NomPhase, 'préparation', 'codage', 'exécution', 'analyse')";

                    $stmt = $bd->prepare($sql);

                    // Liaison des paramètres
                    foreach ($params as $index => $param) {
                    $stmt->bindValue($index + 1, $param);
                    }

                    $stmt->execute();
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);


                // Group results by IDBonnePratique
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
                    echo "<td><button onclick='modifyRow(this)'>Modifier</button></td>";
                    echo "</tr>";
                }
                ?>

                </tbody>
        </table>
    </div>
</div>
<div class="export-button">
    <form action="../Python/export.php" method="post">
        <input type="hidden" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>">
        <input type="hidden" name="program" value="<?php echo htmlspecialchars($program); ?>">
        <input type="hidden" name="phase" value="<?php echo htmlspecialchars($phase); ?>">
        
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