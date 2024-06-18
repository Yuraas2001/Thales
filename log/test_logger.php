<?php
session_start(); // Assurez-vous que la session est démarrée

// Définir les variables de session pour le test
$_SESSION['username'] = 'khawla';

include 'functions.php'; // Assurez-vous que ce chemin est correct

// Test de journalisation d'une alarme pour le mot de passe oublié
$logMessage = "forgot_password_alarm";
logger($logMessage, 'alarm');

// Test de journalisation d'un avertissement pour un mot de passe incorrect
$logMessage = "incorrect_password_warning";
logger($logMessage, 'warning');

// Test de journalisation d'une information pour l'exportation en PDF ou CSV
$logMessage = "export_pdf_csv_info";
logger($logMessage, 'info');

// Message à afficher sur la page
echo "Tests de journalisation effectués avec succès.";
?>
