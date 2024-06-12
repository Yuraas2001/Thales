<?php
    include("base.php");

    // Check if the form is submitted
    if (isset($_POST['n'], $_POST['p'], $_POST['q'], $_POST['r'])) {
        $n = $_POST['n'];
        $p = $_POST['p'];
        $q = $_POST['q'];
        $r = $_POST['r'];

        // Update the password requirements in the database
        $stmt = $bd->prepare("UPDATE PasswordRequirements SET n = :n, p = :p, q = :q, r = :r");
        $stmt->bindParam(':n', $n);
        $stmt->bindParam(':p', $p);
        $stmt->bindParam(':q', $q);
        $stmt->bindParam(':r', $r);
        $stmt->execute();

        // Redirect back to the admin page
        header("Location: ../Admin/admin_banned_users.php");
        exit();
    }
?>