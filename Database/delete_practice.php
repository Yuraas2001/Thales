<?php
include("../Database/base.php");

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Mettre à jour la colonne is_deleted à TRUE pour une suppression temporaire
    $stmt = $bd->prepare("UPDATE BonnesPratiques SET is_deleted = TRUE WHERE IDBonnePratique = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Rediriger vers la page précédente après la suppression
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    echo "ID de la bonne pratique non spécifié.";
}
?>
