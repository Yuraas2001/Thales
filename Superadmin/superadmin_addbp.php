<?php
session_start();
include("../Database/base.php");
include("../Database/helpers.php");

// Check if 'username' key exists in the session
if (!isset($_SESSION['username'])) {
    // Redirect the user to the login page if they are not logged in
    header("Location: login.php");
    exit;
}

$currentUsername = $_SESSION['username']; // Get the current username from session

// Function to print table schema for debugging
function printTableSchema($pdo, $tableName) {
    $query = $pdo->prepare("DESCRIBE $tableName");
    $query->execute();
    $schema = $query->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($schema);
    echo "</pre>";
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $goodPractice = $_POST['good-practice'];
        $keywords = $_POST['keyword'];
        $programs = isset($_POST['program']) ? $_POST['program'] : [];
        $phase = $_POST['phase'];

        // Debugging information
        echo "<pre>";
        echo "Good Practice: $goodPractice\n";
        echo "Keywords: $keywords\n";
        echo "Programs: " . print_r($programs, true) . "\n";
        echo "Phase: $phase\n";
        echo "</pre>";

        // Print table schema for debugging
        printTableSchema($bd, 'BonnesPratiques');

        // Insert the new "bonne pratique" into the database
        $query = $bd->prepare("INSERT INTO BonnesPratiques (Description) VALUES (?)");
        $query->execute([$goodPractice]);

        // Get the ID of the newly inserted "bonne pratique"
        $bpId = $bd->lastInsertId();

        // Insert the keywords associated with the new "bonne pratique"
        $keywordsArray = explode(',', $keywords);
        foreach ($keywordsArray as $keyword) {
            $keyword = trim($keyword);
            $query = $bd->prepare("SELECT IDMotsCles FROM MotsCles WHERE NomMotsCles = ?");
            $query->execute([$keyword]);
            $keywordId = $query->fetchColumn();

            if (!$keywordId) {
                $query = $bd->prepare("INSERT INTO MotsCles (NomMotsCles) VALUES (?)");
                $query->execute([$keyword]);
                $keywordId = $bd->lastInsertId();
            }

            $query = $bd->prepare("INSERT INTO PratiqueMotsCles (IDBonnePratique, IDMotsCles) VALUES (?, ?)");
            $query->execute([$bpId, $keywordId]);
        }

        // Insert the programs associated with the new "bonne pratique"
        foreach ($programs as $program) {
            $query = $bd->prepare("SELECT IDProgramme FROM Programmes WHERE NomProgramme = ?");
            $query->execute([$program]);
            $programId = $query->fetchColumn();

            if (!$programId) {
                $query = $bd->prepare("INSERT INTO Programmes (NomProgramme) VALUES (?)");
                $query->execute([$program]);
                $programId = $bd->lastInsertId();
            }

            $query = $bd->prepare("INSERT INTO PratiqueProg (IDBonnePratique, IDProgramme) VALUES (?, ?)");
            $query->execute([$bpId, $programId]);
        }

        // Insert the phase associated with the new "bonne pratique"
        $query = $bd->prepare("SELECT IDPhase FROM Phases WHERE NomPhase = ?");
        $query->execute([$phase]);
        $phaseId = $query->fetchColumn();

        if (!$phaseId) {
            $query = $bd->prepare("INSERT INTO Phases (NomPhase) VALUES (?)");
            $query->execute([$phase]);
            $phaseId = $bd->lastInsertId();
        }

        $query = $bd->prepare("INSERT INTO PratiquePhases (IDBonnePratique, IDPhase) VALUES (?, ?)");
        $query->execute([$bpId, $phaseId]);

        // Redirect to superadmin_home.php after successful addition
        header("Location: superadmin_home.php");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/Styles/user_bp.css">
  <link rel="stylesheet" href="/Styles/admin.css">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
  <title>Rebooters Search</title>
  <style>
    .dropdown-checkbox {
        position: relative;
        display: inline-block;
    }
    .dropdown-checkbox-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }
    .dropdown-checkbox-content label {
        display: block;
        padding: 12px 16px;
    }
    .dropdown-checkbox:hover .dropdown-checkbox-content {
        display: block;
    }
  </style>
</head>
<body>

<nav>
    <div class="logo">
        <img src="/Images/logo.svg" alt="REBOOTERS Logo" height="200"> <!-- Adjust the path and size -->
    </div>
    <div>
        <div class="user-menu">
        <a href="./superadmin_home.php" class="menu-button"><?php echo displayUsername($currentUsername, 'superadmin'); ?></a>
            <button class="user-button">☰</button>
            <div class="user-dropdown">
                <a href="./superadmin_changepassword.php">Modifier le mot de passe</a>
                <a href="../Database/deconnex.php">Se déconnecter</a>
            </div>
        </div>
    </div>
</nav>
<div class="menu">
    <a href="superadmin_users_list.php">Listes des utilisateurs</a>
    <a href="superadmin_banned_users.php">Modifier paramètres mot de passe</a>
    <a href="superadmin_bp.php">Gestion des bonnes pratiques</a>
    <a href="superadmin_editprog.php">Modifier un programme</a>
    <a href="superadmin_addbp.php">Ajouter une bonne pratique</a>
</div>
<div class="container">
    <div class="header">
        <h1>Gestion des bonnes pratiques</h1>
    </div>

    <form action="" method="post">
        <div class="form-group">
            <label for="good-practice">Bonne pratique</label>
            <input type="text" id="good-practice" name="good-practice" placeholder="Entrez La bonne pratique" required>
        </div>

        <div class="form-group">
            <label for="keyword">Mot(s) clé(s)</label>
            <input type="text" id="keyword" name="keyword" placeholder="Entrez des mots clés séparés par des virgules" required>
        </div>

        <div class="form-group">
            <label for="program">Programme</label>
            <div class="dropdown-checkbox">
                <button type="button">Sélectionner les programmes</button>
                <div class="dropdown-checkbox-content">
                    <?php
                    // Récupérer tous les programmes existants sans doublons
                    $query = $bd->prepare("SELECT DISTINCT NomProgramme FROM Programmes");
                    $query->execute();
                    $allPrograms = $query->fetchAll(PDO::FETCH_COLUMN);

                    foreach ($allPrograms as $programme): ?>
                        <label>
                            <input type="checkbox" name="program[]" value="<?php echo htmlspecialchars($programme); ?>">
                            <?php echo htmlspecialchars($programme); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="phase">Phase</label>
            <select id="phase" name="phase" required>
                <?php
                // Préparer la requête SQL pour obtenir les valeurs enum de 'NomPhase'
                $query = $bd->prepare("SHOW COLUMNS FROM Phases LIKE 'NomPhase'");
                $query->execute();
                $row = $query->fetch(PDO::FETCH_ASSOC);

                // Extraire les valeurs enum
                preg_match("/^enum\(\'(.*)\'\)$/", $row['Type'], $matches);
                $enum = explode("','", $matches[1]);

                foreach ($enum as $phase): ?>
                    <option value="<?php echo htmlspecialchars($phase); ?>"><?php echo htmlspecialchars($phase); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn">Ajouter</button>
    </form>
</div>

</body>
</html>
