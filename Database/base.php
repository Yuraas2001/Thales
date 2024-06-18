<?php 
    try {
        // Create a new PDO instance to connect to the MySQL database
        $bd= new PDO ("mysql:host=localhost;dbname=BP","root","");
        // Set the character set to UTF-8
        $bd -> exec('SET NAMES utf8');
    }
    catch (Exception $e){
        // If an error occurs, terminate the script and display an error message
        die("Erreur: Connextion Impossible");
    }

?>