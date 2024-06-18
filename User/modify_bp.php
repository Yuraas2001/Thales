<?php
session_start();
include("../Database/base.php");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$currentUsername = $_SESSION['username'];
$userRole = $_SESSION['role']; // Ensure the user's role is stored in the session

// Check if the ID of the best practice to be modified is passed in the request
if (isset($_POST['modify_id'])) {
    $id = $_POST['modify_id'];

    // Check if the form has been submitted to update the details
    if (isset($_POST['description'])) {
        $description = $_POST['description'];
        $keyword = $_POST['keyword'];
        $programs = isset($_POST['program']) ? $_POST['program'] : [];
        $phase = $_POST['phase'];

        try {
            // Update the best practice
            $stmt = $bd->prepare("UPDATE BonnesPratiques SET Description = :description WHERE IDBonnePratique = :id");
            $stmt->execute([':description' => $description, ':id' => $id]);

            // Update keywords, programs, and phase
            // Delete existing entries
            $stmt = $bd->prepare("DELETE FROM PratiqueMotsCles WHERE IDBonnePratique = :id");
            $stmt->execute([':id' => $id]);
            $stmt = $bd->prepare("DELETE FROM PratiqueProg WHERE IDBonnePratique = :id");
            $stmt->execute([':id' => $id]);
            $stmt = $bd->prepare("DELETE FROM PratiquePhases WHERE IDBonnePratique = :id");
            $stmt->execute([':id' => $id]);

            // Add new entries
            // For keywords, assuming they are comma-separated
            $keywordsArray = explode(',', $keyword);
            foreach ($keywordsArray as $keyword) {
                $stmt = $bd->prepare("SELECT IDMotsCles FROM MotsCles WHERE NomMotsCles = :keyword");
                $stmt->execute([':keyword' => trim($keyword)]);
                $keywordId = $stmt->fetchColumn();
                if ($keywordId) {
                    $stmt = $bd->prepare("INSERT INTO PratiqueMotsCles (IDBonnePratique, IDMotsCles) VALUES (:id, :keywordId)");
                    $stmt->execute([':id' => $id, ':keywordId' => $keywordId]);
                }
            }

            foreach ($programs as $program) {
                $stmt = $bd->prepare("SELECT IDProgramme FROM Programmes WHERE NomProgramme = :program");
                $stmt->execute([':program' => $program]);
                $programId = $stmt->fetchColumn();
                if ($programId) {
                    $stmt = $bd->prepare("INSERT INTO PratiqueProg (IDBonnePratique, IDProgramme) VALUES (:id, :programId)");
                    $stmt->execute([':id' => $id, ':programId' => $programId]);
                }
            }

            $stmt = $bd->prepare("SELECT IDPhase FROM Phases WHERE NomPhase = :phase");
            $stmt->execute([':phase' => $phase]);
            $phaseId = $stmt->fetchColumn();
            if ($phaseId) {
                $stmt = $bd->prepare("INSERT INTO PratiquePhases (IDBonnePratique, IDPhase) VALUES (:id, :phaseId)");
                $stmt->execute([':id' => $id, ':phaseId' => $phaseId]);
            }

            // Redirect to the appropriate home page after successful update
            if ($userRole == 'admin') {
                header("Location: /Admin/admin_home.php");
            } else {
                header("Location: /User/user_home.php");
            }
            exit;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Retrieve the details of the best practice to be modified
        $stmt = $bd->prepare("SELECT * FROM BonnesPratiques WHERE IDBonnePratique = :id");
        $stmt->execute([':id' => $id]);
        $bp = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$bp) {
            echo "Bonne pratique non trouvée.";
            exit;
        }

        // Retrieve the keywords associated with the best practice
        $stmt = $bd->prepare("SELECT NomMotsCles FROM MotsCles 
                              INNER JOIN PratiqueMotsCles ON MotsCles.IDMotsCles = PratiqueMotsCles.IDMotsCles 
                              WHERE PratiqueMotsCles.IDBonnePratique = :id");
        $stmt->execute([':id' => $id]);
        $keywords = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Retrieve the programs associated with the best practice
        $stmt = $bd->prepare("SELECT DISTINCT NomProgramme FROM Programmes 
                              INNER JOIN PratiqueProg ON Programmes.IDProgramme = PratiqueProg.IDProgramme 
                              WHERE PratiqueProg.IDBonnePratique = :id");
        $stmt->execute([':id' => $id]);
        $programs = $stmt->fetchAll(PDO::FETCH_COLUMN);

        //  Retrieve the phase associated with the best practice
        $stmt = $bd->prepare("SELECT NomPhase FROM Phases 
                              INNER JOIN PratiquePhases ON Phases.IDPhase = PratiquePhases.IDPhase 
                              WHERE PratiquePhases.IDBonnePratique = :id");
        $stmt->execute([':id' => $id]);
        $phase = $stmt->fetchColumn();
    }
} else {
    echo "ID de la bonne pratique manquant.";
    exit;
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
  <title>Modifier la bonne pratique</title>
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

<div class="container">
    <div class="header">
        <h1>Modifier la bonne pratique</h1>
    </div>

    <form action="modify_bp.php" method="post">
        <input type="hidden" name="modify_id" value="<?php echo htmlspecialchars($bp['IDBonnePratique']); ?>">

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" cols="50"><?php echo htmlspecialchars($bp['Description']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="keyword">Mot(s) clé(s)</label>
            <input type="text" id="keyword" name="keyword" value="<?php echo htmlspecialchars(implode(", ", $keywords)); ?>">
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
                            <input type="checkbox" name="program[]" value="<?php echo htmlspecialchars($programme); ?>" <?php echo in_array($programme, $programs) ? 'checked' : ''; ?>>
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
                // Préparer la requête SQL pour obtenir les valeurs enum de 'NomPhase'
                $query = $bd->prepare("SHOW COLUMNS FROM Phases LIKE 'NomPhase'");
                $query->execute();
                $row = $query->fetch(PDO::FETCH_ASSOC);

                // Extraire les valeurs enum
                preg_match("/^enum\(\'(.*)\'\)$/", $row['Type'], $matches);
                $enum = explode("','", $matches[1]);

                foreach ($enum as $phaseOption): ?>
                    <option value="<?php echo htmlspecialchars($phaseOption); ?>" <?php echo $phaseOption == $phase ? 'selected' : ''; ?>><?php echo htmlspecialchars($phaseOption); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn">Enregistrer</button>
    </form>
</div>
</body>
</html>
