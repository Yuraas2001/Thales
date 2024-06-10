<?php
include("../Database/base.php");

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $description = $_POST['description'];
    // Ajoutez d'autres champs nécessaires ici

    $stmt = $bd->prepare("UPDATE BonnesPratiques SET Description = :description WHERE IDBonnePratique = :id");
    $stmt->execute([
        ':description' => $description,
        ':id' => $id
    ]);

    echo "Bonne pratique mise à jour avec succès.";
}
?>
