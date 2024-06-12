<?php
session_start();
include("../Database/base.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'] === 'admin' ? 1 : 0;

    // Hacher le mot de passe
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insérer le nouvel utilisateur dans la base de données
    $stmt = $bd->prepare("INSERT INTO Utilisateurs (NomUtilisateur, MotDePasse, TypeUtilisateur) VALUES (:username, :password, :role)");
    if ($stmt->execute([':username' => $username, ':password' => $hashedPassword, ':role' => $role])) {
        $_SESSION['success'] = "Utilisateur ajouté avec succès.";
    } else {
        $_SESSION['error'] = "Erreur lors de l'ajout de l'utilisateur.";
    }

    header("Location: ../admin/admin_home.php");
    exit;
}
?>
