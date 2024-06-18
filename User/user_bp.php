<?php
session_start();
include("../Database/base.php");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}
$currentUsername = $_SESSION['username']; // Get the current username from session
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

<div class="container">
    <div class="header">
        <h1>Gestion des bonnes pratiques</h1>
    </div>

    <form action="../Database/add_bp.php" method="post">
        <div class="form-group">
            <label for="good-practice">Bonne pratique</label>
            <input type="text" id="good-practice" name="good-practice" placeholder="Entrez La bonne pratique">
        </div>

        <div class="form-group">
            <label for="keyword">Mot(s) clé(s)</label>
            <input type="text" id="keyword" name="keyword" placeholder="Entrez des mots clés séparés par des virgules">
        </div>

        <div class="form-group">
            <label for="program">Programme</label>
            <div class="dropdown-checkbox">
                <button type="button">Sélectionner les programmes</button>
                <div class="dropdown-checkbox-content">
                    <?php
                    // Retrieve all existing programs without duplicates
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
            <select id="phase" name="phase">
                <?php
                // Prepare the SQL query to get enum values of 'NomPhase'
                $query = $bd->prepare("SHOW COLUMNS FROM Phases LIKE 'NomPhase'");
                $query->execute();
                $row = $query->fetch(PDO::FETCH_ASSOC);

                // Extract enum values
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
