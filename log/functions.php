<?php
// Check the session status and start it if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define the path for the log file
$logFile = 'log.txt';

// Create the log file if it doesn't exist
if (!file_exists($logFile)) {
    // Try to create the file, display an error message and exit if creation fails
    if (!touch($logFile)) {
        echo "Error: Unable to create log file.";
        exit();
    }
}

/**
 * Function to log events into the log file.
 * 
 * @param string $message The message to log.
 * @param string $level The level of the message (e.g., info, warning, error).
 */
function logger($message, $level) {
    global $logFile; // Ensure $logFile is accessible within this function

    // Get the current date in the desired format (day-month-year hour:minute:second)
    $datetime = date('d-m-Y H:i:s');

    // Get the client's IP address
    $ip = $_SERVER['REMOTE_ADDR'];

    // Retrieve the username from the session, default to 'Unknown User' if not set
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Unknown User';

    // Build the log message with tab-separated values
    switch ($level) {
        case 'alarm':
            $description = getAlarmDescription($message);
            $logEntry = "$ip\tUser: $username\t\t$datetime\tAlarm: $description\n";
            break;
        case 'warning':
            $description = getWarningDescription($message);
            $logEntry = "$ip\tUser: $username\t\t$datetime\tWarning: $description\n";
            break;
        case 'info':
            $description = getInfoDescription($message);
            $logEntry = "$ip\tUser: $username\t\t$datetime\tInformation: $description\n";
            break;
        default:
            $logEntry = "$ip\tUser: $username\t\t$datetime\t$level: $message\n";
            break;
    }

    // Append the log entry to the log file
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

/**
 * Function to get the description for an informative log message.
 * 
 * @param string $log The log message to retrieve the description for.
 * @return string The description of the log message.
 */
function getInfoDescription($log) {
    switch ($log) {
        case 'export_pdf_info':
            return "Information: Exportation en PDF effectuée.";
        case 'export_csv_info':
            return "Information: Exportation en CSV effectuée.";
        case 'admin_add_user_info':
            return "Information: Ajout d'un utilisateur par l'administrateur.";
        case 'user_deleted_info':
            return "Information: Utilisateur supprimé par l'administrateur.";
        case 'admin_add_good_practice_info':
            return "Information: L'administrateur a ajouté une nouvelle bonne pratique.";
        // Ajoutez d'autres cas au besoin

        default:
            return $log;
    }
}

/**
 * Function to get the description for a warning log message.
 * 
 * @param string $log The log message to retrieve the description for.
 * @return string The description of the log message.
 */
function getWarningDescription($log) {
    switch ($log) {
        case 'incorrect_password_warning':
            return "Warning: Mot de passe incorrect.";
        case 'authenticated_user_warning':
            return "Warning: Utilisateur authentifié.";
        default:
            return $log;
    }
}

/**
 * Function to get the description for an alarm log message.
 * 
 * @param string $log The log message to retrieve the description for.
 * @return string The description of the log message.
 */
function getAlarmDescription($log) {
    switch ($log) {
        case 'forgot_password_alarm':
            return "Alarm: Mot de passe oublié, compte utilisateur bloqué.";
        case 'user_blocked':
            return "Alarm: Utilisateur bloqué après avoir oublié son mot de passe.";
        default:
            return $log;
    }
}
?>