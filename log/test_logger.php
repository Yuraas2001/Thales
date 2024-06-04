<?php
include 'functions.php';

// Test de journalisation d'un avertissement pour l'échec de la connexion à la base de données
$logMessage = "db_connection_failed";
logger($logMessage, 'warning');

// Message à afficher sur la page
echo "Test de l'avertissement pour l'échec de la connexion à la base de données.";

?>
