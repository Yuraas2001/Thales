<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("base.php");

    // Get the form data
    $goodPractice = $_POST['good-practice'];
    $keywords = explode(',', $_POST['keyword']); // Split the keywords into an array
    $programs = $_POST['program']; // This will be an array
    $phase = $_POST['phase'];

    // Prepare the SQL statement for inserting the good practice
    $stmt = $bd->prepare("
        INSERT INTO BonnesPratiques (Description)
        VALUES (:goodPractice)
    ");

    // Bind the parameters
    $stmt->bindParam(':goodPractice', $goodPractice);

    // Execute the statement
    $stmt->execute();

    // Get the last inserted ID
    $lastId = $bd->lastInsertId();

    // Insert the keywords
    foreach ($keywords as $keyword) {
        $keyword = trim($keyword); // Remove any whitespace

        // Check if the keyword already exists
        $stmt = $bd->prepare("
            SELECT IDMotsCles FROM MotsCles WHERE NomMotsCles = :keyword
        ");
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        $keywordId = $stmt->fetchColumn();

        // If the keyword doesn't exist, insert it
        if (!$keywordId) {
            $stmt = $bd->prepare("
                INSERT INTO MotsCles (NomMotsCles)
                VALUES (:keyword)
            ");
            $stmt->bindParam(':keyword', $keyword);
            $stmt->execute();
            $keywordId = $bd->lastInsertId();
        }

        // Link the keyword with the good practice
        $stmt = $bd->prepare("
            INSERT INTO PratiqueMotsCles (IDBonnePratique, IDMotsCles)
            VALUES (:lastId, :keywordId)
        ");
        $stmt->bindParam(':lastId', $lastId);
        $stmt->bindParam(':keywordId', $keywordId);
        $stmt->execute();
    }

    // Insert the phase
    // Check if the phase already exists
    $stmt = $bd->prepare("
        SELECT IDPhase FROM Phases WHERE NomPhase = :phase
    ");
    $stmt->bindParam(':phase', $phase);
    $stmt->execute();
    $phaseId = $stmt->fetchColumn();

    // If the phase doesn't exist, insert it
    if (!$phaseId) {
        $stmt = $bd->prepare("
            INSERT INTO Phases (NomPhase)
            VALUES (:phase)
        ");
        $stmt->bindParam(':phase', $phase);
        $stmt->execute();
        $phaseId = $bd->lastInsertId();
    }

    // Link the phase with the good practice
    $stmt = $bd->prepare("
        INSERT INTO PratiquePhases (IDBonnePratique, IDPhase)
        VALUES (:lastId, :phaseId)
    ");
    $stmt->bindParam(':lastId', $lastId);
    $stmt->bindParam(':phaseId', $phaseId);
    $stmt->execute();

    // Insert the programs
    foreach ($programs as $program) {
        // Check if the program already exists
        $stmt = $bd->prepare("
            SELECT IDProgramme FROM Programmes WHERE NomProgramme = :program
        ");
        $stmt->bindParam(':program', $program);
        $stmt->execute();
        $programId = $stmt->fetchColumn();

        // If the program doesn't exist, insert it
        if (!$programId) {
            $stmt = $bd->prepare("
                INSERT INTO Programmes (NomProgramme)
                VALUES (:program)
            ");
            $stmt->bindParam(':program', $program);
            $stmt->execute();
            $programId = $bd->lastInsertId();
        }

        // Link the program with the good practice
        $stmt = $bd->prepare("
            INSERT INTO PratiqueProg (IDBonnePratique, IDProgramme)
            VALUES (:lastId, :programId)
        ");
        $stmt->bindParam(':lastId', $lastId);
        $stmt->bindParam(':programId', $programId);
        $stmt->execute();
    }

    // Redirect back to the form
    header("Location: ../User/user_bp.php");
}
?>
