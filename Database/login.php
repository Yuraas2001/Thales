<?php 
  include("base.php");

  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Prepare and execute the SQL statement to get user details
  $stmt = $bd->prepare("SELECT * FROM Utilisateurs WHERE NomUtilisateur = :username");
  $stmt->execute([':username' => $username]);

  // Fetch the result
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  // Check if any row is returned
  if ($result) {
    // Check if the user is blocked
    if ($result['Bloque'] == 1) {
      header('Location: http://localhost/index.php?err=2');
      exit;
    }

    // Check if the password is correct
    if ($result['MotDePasse'] == $password) {
      // Reset the number of attempts
      $stmt = $bd->prepare("UPDATE Utilisateurs SET NBtentative = 0 WHERE NomUtilisateur = :username");
      $stmt->execute([':username' => $username]);

      // Start the session and store the username
      session_start();
      $_SESSION['username'] = $username;
      $_SESSION['password'] = $password;
      $_SESSION['role'] = $result['TypeUtilisateur'];

      if ($result['TypeUtilisateur'] == 1) {
        header('Location: ../Admin/admin_home.php');
      } else {
        header('Location: ../User/user_home.php');
      }
      exit;
    } else {
      $stmt = $bd->prepare("UPDATE Utilisateurs SET NBtentative = NBtentative + 1 WHERE NomUtilisateur = :username");
      $stmt->execute([':username' => $username]);
      
      // Fetch the updated user details
      $stmt = $bd->prepare("SELECT * FROM Utilisateurs WHERE NomUtilisateur = :username");
      $stmt->execute([':username' => $username]);
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      
      // Check if the number of attempts has reached 3
      if ($result['NBtentative'] >= 3) {
        // Block the user and reset the number of attempts
        $stmt = $bd->prepare("UPDATE Utilisateurs SET Bloque = 1, NBtentative = 0 WHERE NomUtilisateur = :username");
        $stmt->execute([':username' => $username]);
        
        // Redirect to the login page with an error code
        header('Location: http://localhost/index.php?err=2');
        exit;
      }

      header('Location: http://localhost/index.php?err=1');
      exit;
    }
  } else {
    header('Location: http://localhost/index.php?err=1');
    exit;
  }
?>