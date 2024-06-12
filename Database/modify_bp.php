<?php
    include("base.php");

    $id = $_GET['id'];


    $sql = "SELECT * FROM BonnesPratiques WHERE IDBonnePratique = ?";
    $stmt = $bd->prepare($sql);
    $stmt->execute([$id]);
    $pratique = $stmt->fetch();

    



?>