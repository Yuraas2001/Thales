<?php
    // fetch_data.php
    include("base.php");

    // Prepare and execute the SQL statement
    $stmt = $bd->prepare("
    SELECT Programmes.NomProgramme, BonnesPratiques.Description, Phases.NomPhase, MotsCles.NomMotsCles FROM BonnesPratiques JOIN PratiquePhases ON BonnesPratiques.IDBonnePratique = PratiquePhases.IDBonnePratique JOIN Phases ON PratiquePhases.IDPhase = Phases.IDPhase JOIN PratiqueMotsCles ON BonnesPratiques.IDBonnePratique = PratiqueMotsCles.IDBonnePratique JOIN MotsCles ON PratiqueMotsCles.IDMotsCles = MotsCles.IDMotsCles JOIN Programmes ON Programmes.IDProgramme = BonnesPratiques.IDBonnePratique; 
    ");
    $stmt->execute();

    // Fetch the results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results
    return $results;
?>