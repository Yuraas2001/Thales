<?php

// This file is to be executed once in order to hash all the passwords in the database
include("base.php");

// Fetch all users
$stmt = $bd->prepare("SELECT * FROM Utilisateurs");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Loop through each user
foreach ($users as $user) {
    // Hash the user's password
    $hashedPassword = password_hash($user['MotDePasse'], PASSWORD_DEFAULT);

    // Update the user's password in the database
    $stmt = $bd->prepare("UPDATE Utilisateurs SET MotDePasse = :password WHERE NomUtilisateur = :username");
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':username', $user['NomUtilisateur']);
    $stmt->execute();
}

echo "All passwords have been hashed.";
?>