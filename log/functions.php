<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$logFile = 'log.txt';
if (!file_exists($logFile)) {
    if (!touch($logFile)) {
        echo "Erreur : Impossible de créer le fichier de journal.";
        exit();
    }
}

function logger($message, $level, $username = 'Unknown User', $profile = 'Unknown Profile') {
    $datetime = date('d-m-Y H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $logEntry = "$ip\t$username\t$profile\t\t$datetime\t$level\t$message\n";
    file_put_contents('log.txt', $logEntry, FILE_APPEND);
}

function getInfoDescription($log) {
    switch ($log) {
        case 'user_login':
            return "L'utilisateur a réussi à se connecter au système.";
        case 'add_task':
            return "Une nouvelle tâche a été ajoutée avec succès à la liste.";
        case 'update_profile':
            return "Le profil de l'utilisateur a été mis à jour avec les informations fournies.";
        default:
            return $log;
    }
}

function getWarningDescription($log) {
    switch ($log) {
        case 'db_connection_failed':
            return "La connexion à la base de données a échoué.";
        case 'suspicious_activity':
            return "Le système a détecté une activité suspecte sur le compte de l'utilisateur.";
        case 'config_file_missing':
            return "Le fichier de configuration n'a pas été trouvé, les valeurs par défaut seront utilisées.";
        default:
            return $log;
    }
}

function getAlarmDescription($log) {
    switch ($log) {
        case 'mail_server_offline':
            return "Le serveur de messagerie est hors ligne, les e-mails ne peuvent pas être envoyés.";
        case 'intrusion_detected':
            return "Le système a détecté une intrusion et a bloqué l'accès à l'utilisateur.";
        case 'low_storage':
            return "Le niveau de stockage disponible est critique, des mesures doivent être prises pour libérer de l'espace.";
        default:
            return $log;
    }
}
?>
