<?php 
    try {
        $bd= new PDO ("mysql:host=localhost;dbname=BP","root","");
        $bd -> exec('SET NAMES utf8');
    }
    catch (Exception $e){
        die("Erreur: Connextion Impossible");
    }

?>