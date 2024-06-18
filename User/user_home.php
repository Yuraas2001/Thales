<?php
session_start();
include("../Database/base.php");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$currentUsername = $_SESSION['username'];

// Function to get users
function getUsers($bd, $currentUsername) {
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
    return $users;
}

// Function to get programs
function getPrograms($bd) {
    $stmt = $bd->prepare("SELECT DISTINCT NomProgramme FROM Programmes");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Function to get phases
function getPhases($bd) {
    $stmt = $bd->prepare("SELECT DISTINCT NomPhase FROM Phases ORDER BY FIELD(NomPhase, 'codage', 'exécution', 'analyse', 'préparation')");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Function to get results
function getResults($bd, $keywords, $program, $phase) {
    $sql = "SELECT BonnesPratiques.IDBonnePratique, Programmes.NomProgramme, Phases.NomPhase, BonnesPratiques.Description, MotsCles.NomMotsCles
            FROM PratiqueProg
            INNER JOIN Programmes ON PratiqueProg.IDProgramme = Programmes.IDProgramme
            INNER JOIN PratiquePhases ON PratiqueProg.IDBonnePratique = PratiquePhases.IDBonnePratique
            INNER JOIN Phases ON PratiquePhases.IDPhase = Phases.IDPhase
            INNER JOIN PratiqueMotsCles ON PratiqueProg.IDBonnePratique = PratiqueMotsCles.IDBonnePratique
            INNER JOIN MotsCles ON PratiqueMotsCles.IDMotsCles = MotsCles.IDMotsCles
            INNER JOIN BonnesPratiques ON PratiqueProg.IDBonnePratique = BonnesPratiques.IDBonnePratique
            WHERE BonnesPratiques.Etat = 0";

    $params = [];
    if (!empty($keywords)) {
        $keywordArray = explode(',', $keywords);
        $keywordConditions = [];
        foreach ($keywordArray as $index => $keyword) {
            $paramName = ':keyword' . $index;
            $keywordConditions[] = "MotsCles.NomMotsCles = $paramName";
            $params[$paramName] = trim($keyword);
        }
        $sql .= " AND BonnesPratiques.IDBonnePratique IN (
                    SELECT PratiqueMotsCles.IDBonnePratique
                    FROM PratiqueMotsCles
                    INNER JOIN MotsCles ON PratiqueMotsCles.IDMotsCles = MotsCles.IDMotsCles
                    WHERE " . implode(' OR ', $keywordConditions) . "
                )";
    }
    if ($program !== '') {
        $sql .= " AND BonnesPratiques.IDBonnePratique IN (
                    SELECT PratiqueProg.IDBonnePratique
                    FROM PratiqueProg
                    INNER JOIN Programmes ON PratiqueProg.IDProgramme = Programmes.IDProgramme
                    WHERE Programmes.NomProgramme = :program OR Programmes.NomProgramme = 'GENERIC'
                )";

        $params[':program'] = $program;
    }
    if ($phase !== '') {
        $sql .= " AND Phases.NomPhase = :phase";
        $params[':phase'] = $phase;
    }

    $sql .= " ORDER BY FIELD(Phases.NomPhase, 'codage', 'exécution', 'analyse', 'préparation')";

    $stmt = $bd->prepare($sql);
    foreach ($params as $key => &$val) {
        $stmt->bindParam($key, $val);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get users
$users = getUsers($bd, $currentUsername);

// Get programs
$programmes = getPrograms($bd);

// Get phases
$phases = getPhases($bd);

// Get search results
$keywords = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$program = isset($_GET['program']) ? $_GET['program'] : '';
$phase = isset($_GET['phase']) ? $_GET['phase'] : '';

$results = getResults($bd, $keywords, $program, $phase);

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

$usernameToModify = isset($_GET['modify']) ? $_GET['modify'] : null;
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
            <a href="./user_home.php" class="menu-button"><?php echo htmlspecialchars($currentUsername); ?></a> 
            <button class="user-button">☰</button>
            <div class="user-dropdown">
                <a href="./user_bp.php">Paramètres</a>
                <a href="./change_password.php">Modifier le mot de passe</a>
                <a href="../Database/deconnex.php">Se déconnecter</a>
            </div>
        </div>
    </div>
</nav>

<div class="search-container">
    <form action="user_home.php" method="get">
        <input type="text" name="keyword" placeholder="Entrer un mot clé...">
        <label for="program">Programme</label>
        <select name="program">
            <option value="">Programme</option>
            <?php foreach ($programmes as $programme): ?>
                <option value="<?php echo htmlspecialchars($programme); ?>"><?php echo htmlspecialchars($programme); ?></option>
            <?php endforeach; ?>
        </select>

        <label for="phase">Phase</label>
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
    <a href="user_home.php" class="reset-button" style="margin-left: 10px;">
        <i class="fa fa-refresh"></i> 
    </a>
</div>
<?php if (isset($_GET['res']) && $_GET['res'] == 0) { ?>
    <p style="color: red; text-align: center;">Oupss : Erreur dans l'exportation du Tableau</p>
<?php } ?>

<?php if (isset($_GET['res']) && $_GET['res'] == 1) { ?>
    <p style="color: green; text-align: center;">Bingooo !! Le Tableau a bien été exporté</p>
<?php } ?>
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
               
                <?php
                // Check if a delete action is triggered
                if (isset($_POST['Etat'])) {
                    $delete_id = $_POST['Etat'];
                    $query = $bd->prepare("UPDATE BonnesPratiques SET Etat = 1 WHERE IDBonnePratique = :id");
                    $query->execute([':id' => $delete_id]);
                }

                // Generate each result row in the table
                foreach ($groupedResults as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars(implode(", ", $row['NomProgramme'])) . "</td>";
                    echo "<td>" . htmlspecialchars($row['NomPhase']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
                    echo "<td>" . htmlspecialchars(implode(", ", $row['NomMotsCles'])) . "</td>";
                    echo "<td>
                            <form method='post' action='modify_bp.php' style='display:inline;'>
                                <input type='hidden' name='modify_id' value='" . $row['IDBonnePratique'] . "'>
                                <button type='submit'>Modifier</button>
                            </form>
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='Etat' value='" . $row['IDBonnePratique'] . "'>
                                <button type='submit'>Supprimer</button>
                            </form>
                          </td>";
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
        $phase = isset($_GET['phase']) ? htmlspecialchars($_GET['phase']) : '';
        $currentUsername = $_SESSION['username'];
        $role = $_SESSION['role'];
        ?>
        <input type="hidden" name="keyword" value="<?php echo $keyword; ?>">
        <input type="hidden" name="program" value="<?php echo $program; ?>">
        <input type="hidden" name="phase" value="<?php echo $phase; ?>">
        <input type="hidden" name="username" value="<?php echo $currentUsername; ?>">
        <input type="hidden" name="role" value="<?php echo $role; ?>">
        
        <label for="format">Format :</label>
        <select name="format" id="format">
            <option value="Excel">CSV</option>
            <option value="PDF">PDF</option>
        </select>
        
        <button type="submit" class="button primary">Exporter le Tableau</button>
    </form>
</div>


</body>
</html>
