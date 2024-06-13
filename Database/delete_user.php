<?php
include("base.php");

session_start();

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    echo "Accès non autorisé.";
    exit;
}

// Récupérer le nom d'utilisateur et le type d'utilisateur connecté
$logged_in_user = $_SESSION['username'];
$logged_in_role = $_SESSION['role'];

// Récupérer le nom d'utilisateur du formulaire
if (isset($_POST['username'])) {
    $username = $_POST['username'];

    try {
        // Préparer la requête SQL pour obtenir les détails de l'utilisateur à supprimer
        $stmt = $bd->prepare("SELECT * FROM Utilisateurs WHERE NomUtilisateur = :username");
        $stmt->execute([':username' => $username]);

        // Récupérer les détails de l'utilisateur
        $user_to_delete = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user_to_delete) {
            $user_to_delete_role = $user_to_delete['TypeUtilisateur'];

            // Vérifier les permissions de suppression
            if ($logged_in_role == 2) {
                // Superadmin: peut supprimer tout le monde
                $stmt = $bd->prepare("DELETE FROM Utilisateurs WHERE NomUtilisateur = :username");
                $stmt->bindParam(':username', $username);
                $stmt->execute();

                header('Location: ../Admin/admin_users_list.php');
                exit;
            } elseif ($logged_in_role == 1) {
                // Admin: ne peut pas supprimer un autre admin ou superadmin
                if ($user_to_delete_role == 0) {
                    $stmt = $bd->prepare("DELETE FROM Utilisateurs WHERE NomUtilisateur = :username");
                    $stmt->bindParam(':username', $username);
                    $stmt->execute();

                    header('Location: ../Admin/admin_users_list.php');
                    exit;
                } else {
                    echo "Vous n'avez pas le droit de supprimer cet utilisateur.";
                    exit;
                }
            } else {
                // Utilisateur normal: ne peut rien supprimer
                echo "Accès non autorisé.";
                exit;
            }
        } else {
            echo "Utilisateur introuvable.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage();
    }
} else {
    echo "Nom d'utilisateur non fourni.";
    exit;
}
?>
