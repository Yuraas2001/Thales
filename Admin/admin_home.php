<?php
session_start(); // Assure que la session est démarrée
include("../Database/base.php"); // Inclut le fichier de connexion à la base de données

// Vérifie si la clé 'username' existe dans la session
if (!isset($_SESSION['username'])) {
    // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: login.php");
    exit; // Arrête l'exécution ultérieure
}

$currentUsername = $_SESSION['username']; // Récupère le nom d'utilisateur actuel depuis la session

// Traite l'action de suppression pour la bonne pratique
if (isset($_POST['action']) && $_POST['action'] == 'delete_bp') {
    $bpId = $_POST['bp_id'];
    $query = $bd->prepare("UPDATE BonnesPratiques SET is_deleted = TRUE WHERE IDBonnePratique = :bp_id");
    $query->execute([':bp_id' => $bpId]);

    // Journalise l'action de suppression
    include 'functions.php'; // Inclut le fichier de fonctions pour le journal
    $deleteMessage = "admin_delete_good_practice_info"; // Message de journal personnalisé
    logger($deleteMessage, 'info'); // Journalise l'action avec le niveau 'info'
}

// Affiche le message d'état d'exportation s'il est défini
if (isset($_SESSION['export_status'])) {
    echo "<script>alert('" . $_SESSION['export_status'] . "');</script>";
    unset($_SESSION['export_status']); // Efface le message d'état d'exportation après l'affichage
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
    <style>
        .reset-button {
            margin-left: 10px;
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
<div class="menu">
    <a href="admin_users_list.php">Liste des utilisateurs</a>
    <a href="admin_banned_users.php">Modifier les paramètres du mot de passe</a>
    <a href="admin_bp.php">Gestion des bonnes pratiques</a>
    <a href="admin_editprog.php">Modifier un programme</a>
    <a href="admin_addbp.php">Ajouter une bonne pratique</a>
</div>

<div class="search-container">
    <form action="admin_home.php" method="get">
        <input type="text" name="keyword" placeholder="Entrer un mot clé...">

        <label for="program">Programme</label>
        <?php
        // Récupère les noms de programme distincts depuis la base de données
        $query = $bd->prepare("SELECT DISTINCT NomProgramme FROM Programmes");
        $query->execute();
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
        // Récupère les noms de phase distincts ordonnés par un ordre spécifique
        $query = $bd->prepare("SELECT DISTINCT NomPhase FROM Phases ORDER BY FIELD(NomPhase, 'préparation', 'codage', 'exécution', 'analyse')");
        $query->execute();
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($groupedResults as $id => $result): ?>
                <tr>
                    <td><?php echo $result['NomProgramme']; ?></td>
                    <td><?php echo $result['NomPhase']; ?></td>
                    <td><?php echo $result['Description']; ?></td>
                    <td><?php echo implode(', ', $result['NomMotsCles']); ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="bp_id" value="<?php echo $id; ?>">
                            <input type="hidden" name="action" value="delete_bp">
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
