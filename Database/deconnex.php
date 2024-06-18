<?php 
    // Start the session
      session_start(); 
      // Destroy the session, logging out the user
      session_destroy(); 
      // Redirect the user to the home page
      header("Location: ../index.php"); 
      exit();// Ensure no further code is executed after the redirect
    ?> 