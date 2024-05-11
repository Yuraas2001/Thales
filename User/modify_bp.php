<?php
session_start();
include("../Database/base.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$currentUsername = $_SESSION['username'];

// Vérifier si l'ID de la bonne pratique à modifier est passé dans la requête
if (isset($_POST['modify_id'])) {
    $id = $_POST['modify_id'];

    // Récupérer les détails de la bonne pratique à modifier
    $stmt = $bd->prepare("SELECT * FROM BonnesPratiques WHERE IDBonnePratique = :id");
    $stmt->execute([':id' => $id]);
    $bp = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bp) {
        echo "Bonne pratique non trouvée.";
        exit;
    }

    // Récupérer les mots clés associés à la bonne pratique
    $stmt = $bd->prepare("SELECT NomMotsCles FROM MotsCles 
                          INNER JOIN PratiqueMotsCles ON MotsCles.IDMotsCles = PratiqueMotsCles.IDMotsCles 
                          WHERE PratiqueMotsCles.IDBonnePratique = :id");
    $stmt->execute([':id' => $id]);
    $keywords = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Récupérer les programmes associés à la bonne pratique
    $stmt = $bd->prepare("SELECT NomProgramme FROM Programmes 
                          INNER JOIN PratiqueProg ON Programmes.IDProgramme = PratiqueProg.IDProgramme 
                          WHERE PratiqueProg.IDBonnePratique = :id");
    $stmt->execute([':id' => $id]);
    $programs = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Récupérer la phase associée à la bonne pratique
    $stmt = $bd->prepare("SELECT NomPhase FROM Phases 
                          INNER JOIN PratiquePhases ON Phases.IDPhase = PratiquePhases.IDPhase 
                          WHERE PratiquePhases.IDBonnePratique = :id");
    $stmt->execute([':id' => $id]);
    $phase = $stmt->fetchColumn();

    // Vérifier si le formulaire a été soumis pour mettre à jour les détails
    if (isset($_POST['description'])) {
        $description = $_POST['description'];
        $keyword = $_POST['keyword'];
        $programs = isset($_POST['program']) ? $_POST['program'] : [];
        $phase = $_POST['phase'];

        // Mettre à jour la bonne pratique
        $stmt = $bd->prepare("UPDATE BonnesPratiques SET Description = :description WHERE IDBonnePratique = :id");
        $stmt->execute([':description' => $description, ':id' => $id]);

        // Mettre à jour les mots clés, programmes et phase
        // Suppression des entrées existantes
        $stmt = $bd->prepare("DELETE FROM PratiqueMotsCles WHERE IDBonnePratique = :id");
        $stmt->execute([':id' => $id]);
        $stmt = $bd->prepare("DELETE FROM PratiqueProg WHERE IDBonnePratique = :id");
        $stmt->execute([':id' => $id]);
        $stmt = $bd->prepare("DELETE FROM PratiquePhases WHERE IDBonnePratique = :id");
        $stmt->execute([':id' => $id]);

        // Ajout des nouvelles entrées
        $stmt = $bd->prepare("INSERT INTO PratiqueMotsCles (IDBonnePratique, IDMotsCles) VALUES (:id, (SELECT IDMotsCles FROM MotsCles WHERE NomMotsCles = :keyword))");
        $stmt->execute([':id' => $id, ':keyword' => $keyword]);

        foreach ($programs as $program) {
            $stmt = $bd->prepare("INSERT INTO PratiqueProg (IDBonnePratique, IDProgramme) VALUES (:id, (SELECT IDProgramme FROM Programmes WHERE NomProgramme = :program))");
            $stmt->execute([':id' => $id, ':program' => $program]);
        }

        $stmt = $bd->prepare("INSERT INTO PratiquePhases (IDBonnePratique, IDPhase) VALUES (:id, (SELECT IDPhase FROM Phases WHERE NomPhase = :phase))");
        $stmt->execute([':id' => $id, ':phase' => $phase]);

        echo "Bonne pratique mise à jour avec succès.";
        exit;
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
                <a href="./change_password.php">Gestion de mot de passe</a>
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
            <select id="program" name="program[]" multiple>
                <?php
                // Récupérer tous les programmes existants
                $query = $bd->prepare("SELECT NomProgramme FROM Programmes");
                $query->execute();
                $allPrograms = $query->fetchAll(PDO::FETCH_COLUMN);

                foreach ($allPrograms as $programme): ?>
                    <option value="<?php echo htmlspecialchars($programme); ?>" <?php echo in_array($programme, $programs) ? 'selected' : ''; ?>><?php echo htmlspecialchars($programme); ?></option>
                <?php endforeach; ?>
            </select>
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
