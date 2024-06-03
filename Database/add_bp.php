<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("base.php");

    // Get the form data
    $goodPractice = $_POST['good-practice'];
    $keyword = $_POST['keyword'];
    $programs = $_POST['program']; //this will be an array
    $phase = $_POST['phase'];

    // Prepare the SQL statement
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

    // Insert the keyword
    $stmt = $bd->prepare("
        INSERT INTO MotsCles (NomMotsCles)
        VALUES (:keyword)
    ");
    $stmt->bindParam(':keyword', $keyword);
    $stmt->execute();

    $keywordId = $bd->lastInsertId();

    $stmt = $bd->prepare("
        INSERT INTO PratiqueMotsCles (IDBonnePratique, IDMotsCles)
        VALUES (:lastId, :keywordId)
    ");
    $stmt->bindParam(':lastId', $lastId);
    $stmt->bindParam(':keywordId', $keywordId);
    $stmt->execute();

    // Insert the phase
    $stmt = $bd->prepare("
        INSERT INTO Phases (NomPhase)
        VALUES (:phase)
    ");
    $stmt->bindParam(':phase', $phase);
    $stmt->execute();

    $phaseId = $bd->lastInsertId();

    $stmt = $bd->prepare("
        INSERT INTO PratiquePhases (IDBonnePratique, IDPhase)
        VALUES (:lastId, :phaseId)
    ");
    $stmt->bindParam(':lastId', $lastId);
    $stmt->bindParam(':phaseId', $phaseId);
    $stmt->execute();

    // Insert the programs
    foreach ($programs as $program) {
        $stmt = $bd->prepare("
            INSERT INTO Programmes (NomProgramme)
            VALUES (:program)
        ");
        $stmt->bindParam(':program', $program);
        $stmt->execute();

        $programId = $bd->lastInsertId();

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