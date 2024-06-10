<?php
session_start();
include("base.php");

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $currentUsername = $_SESSION['username'];

    if ($username !== $currentUsername) {
        $stmt = $bd->prepare("UPDATE Utilisateurs SET Bloque = 0 WHERE NomUtilisateur = :username AND TypeUtilisateur != 'superadmin'");
        $stmt->execute([':username' => $username]);

        $_SESSION['error'] = "L'utilisateur a été débloqué avec succès.";
    } else {
        $_SESSION['error'] = "Vous ne pouvez pas vous débloquer vous-même.";
    }
    header("Location: ../Superadmin/superadmin_users_list.php");
    exit();
} else {
    $_SESSION['error'] = "Nom d'utilisateur non spécifié.";
    header("Location: ../Superadmin/superadmin_users_list.php");
    exit();
}
?>
