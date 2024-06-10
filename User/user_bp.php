<?php
session_start();
include("../Database/base.php");

// Check if 'username' key exists in the session
if (!isset($_SESSION['username'])) {
    // Redirect the user to the login page if they are not logged in
    header("Location: login.php");
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
                <a href="./change_password.php">Gestion de mot de passe</a>
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

        <label for="program">Programme</label>

        <?php
        // Include the database connection file
        include '../Database/base.php'; 

        // Prepare the SQL query to fetch enum values from 'NomProgramme' column
        $query = $bd->prepare("SHOW COLUMNS FROM Programmes LIKE 'NomProgramme'");
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);

        // Extract the enum values
        preg_match("/^enum\(\'(.*)\'\)$/", $row['Type'], $matches);
        $enum = explode("','", $matches[1]);
        ?>

        <div class="checkbox-group">
            <?php foreach ($enum as $programme): ?>
                <label>
                    <input type="checkbox" name="program[]" value="<?php echo $programme; ?>">
                    <?php echo $programme; ?>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="form-group">
            <label for="phase">Phase</label>
            <select id="phase" name="phase">
                <?php
                // Prepare the SQL query to fetch enum values from 'NomPhase' column
                $query = $bd->prepare("SHOW COLUMNS FROM Phases LIKE 'NomPhase'");
                $query->execute();
                $row = $query->fetch(PDO::FETCH_ASSOC);

                // Extract the enum values
                preg_match("/^enum\(\'(.*)\'\)$/", $row['Type'], $matches);
                $enum = explode("','", $matches[1]);

                foreach ($enum as $phase): ?>
                    <option value="<?php echo $phase; ?>"><?php echo $phase; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn">Ajouter</button>
    </form>
</div>
</body>
</html>
