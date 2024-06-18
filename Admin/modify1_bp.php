<?php
session_start();
include("../Database/base.php");

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect the user to the login page if they are not logged in
    header("Location: login.php");
    exit;
}

$currentUsername = $_SESSION['username'];// Get the current username from session

// Check if the ID of the best practice to be modified is passed in the request
if (isset($_POST['modify1_id'])) {
    $id = $_POST['modify1_id'];

    // Retrieve the details of the best practice to be modified
    $stmt = $bd->prepare("SELECT * FROM BonnesPratiques WHERE IDBonnePratique = :id");
    $stmt->execute([':id' => $id]);
    $bp = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bp) {
        echo "Bonne pratique non trouvée.";
        exit;
    }

    // Retrieve the programs associated with the best practice
    $stmt = $bd->prepare("SELECT DISTINCT NomProgramme FROM Programmes 
                          INNER JOIN PratiqueProg ON Programmes.IDProgramme = PratiqueProg.IDProgramme 
                          WHERE PratiqueProg.IDBonnePratique = :id");
    $stmt->execute([':id' => $id]);
    $programs = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Check if the form has been submitted to update the details
    if (isset($_POST['description'])) {
        $description = $_POST['description'];
        $programs = isset($_POST['program']) ? $_POST['program'] : [];

        // Update the best practice description
        $stmt = $bd->prepare("UPDATE BonnesPratiques SET Description = :description WHERE IDBonnePratique = :id");
        $stmt->execute([':description' => $description, ':id' => $id]);

        // Update the programs
        // Delete existing entries
        $stmt = $bd->prepare("DELETE FROM PratiqueProg WHERE IDBonnePratique = :id");
        $stmt->execute([':id' => $id]);

        // Add new entries
        foreach ($programs as $program) {
            $stmt = $bd->prepare("SELECT IDProgramme FROM Programmes WHERE NomProgramme = :program");
            $stmt->execute([':program' => $program]);
            $programId = $stmt->fetchColumn();
            if ($programId) {
                $stmt = $bd->prepare("INSERT INTO PratiqueProg (IDBonnePratique, IDProgramme) VALUES (:id, :programId)");
                $stmt->execute([':id' => $id, ':programId' => $programId]);
            }
        }

        // Redirect to user home page after successful update
        header("Location: /Admin/admin_home.php");
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
            <a href="./admin_home.php" class="menu-button"><?php echo htmlspecialchars($currentUsername); ?></a>
            <button class="user-button">☰</button>
            <div class="user-dropdown">
            <a href="./admin_changepassword.php">Modifier le mot de passe</a>
                <a href="../Database/deconnex.php">Se déconnecter</a>
            </div>
        </div>
    </div>
</nav>

<div class="container">
    <div class="header">
        <h1>Modifier la bonne pratique</h1>
    </div>

    <form action="modify1_bp.php" method="post">
        <input type="hidden" name="modify1_id" value="<?php echo htmlspecialchars($bp['IDBonnePratique']); ?>">

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" cols="50"><?php echo htmlspecialchars($bp['Description']); ?></textarea>
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
                            <input type="checkbox" name="program[]" value="<?php echo htmlspecialchars($programme); ?>" <?php echo in_array($programme, $programs) ? 'checked' : ''; ?>>
                            <?php echo htmlspecialchars($programme); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <button type="submit" class="btn">Enregistrer</button>
    </form>
</div>
</body>
</html>
