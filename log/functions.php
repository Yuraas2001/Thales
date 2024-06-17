<?php
// Check session status and start session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Define the log file path
$logFile = 'log.txt';

// Create the log file if it doesn't exist
if (!file_exists($logFile)) {
    // Try to create the file, if failed, display an error message and exit
    if (!touch($logFile)) {
        echo "Error: Unable to create the log file.";
        exit();
    }
}

/**
 * Function to log events with specified parameters.
 * 
 * @param string $message The message to be logged.
 * @param string $level The level of the log message (e.g., info, warning, error).
 * @param string $username The username associated with the log message (default: 'Unknown User').
 * @param string $profile The user profile associated with the log message (default: 'Unknown Profile').
 */
function logger($message, $level, $username = 'Unknown User', $profile = 'Unknown Profile') {
    // Get the current datetime in the desired format (day-month-year hour:minute:second)
    $datetime = date('d-m-Y H:i:s');

    // Get the client's IP address
    $ip = $_SERVER['REMOTE_ADDR'];

    // Construct the log entry with tab-separated values
    $logEntry = "$ip\t$username\t$profile\t\t$datetime\t$level\t$message\n";

    // Append the log entry to the log file
    file_put_contents('log.txt', $logEntry, FILE_APPEND);
}

/**
 * Function to get the description of an informational log message.
 * 
 * @param string $log The log message to retrieve the description for.
 * @return string The description of the log message.
 */
function getInfoDescription($log) {
    switch ($log) {
        case 'user_login':
            return "The user successfully logged into the system.";
        case 'add_task':
            return "A new task has been successfully added to the list.";
        case 'update_profile':
            return "The user's profile has been updated with the provided information.";
        default:
            return $log;
    }
}

/**
 * Function to get the description of a warning log message.
 * 
 * @param string $log The log message to retrieve the description for.
 * @return string The description of the log message.
 */
function getWarningDescription($log) {
    switch ($log) {
        case 'db_connection_failed':
            return "The connection to the database has failed.";
        case 'suspicious_activity':
            return "The system detected suspicious activity on the user's account.";
        case 'config_file_missing':
            return "The configuration file was not found, default values will be used.";
        default:
            return $log;
    }
}

/**
 * Function to get the description of an alarm log message.
 * 
 * @param string $log The log message to retrieve the description for.
 * @return string The description of the log message.
 */
function getAlarmDescription($log) {
    switch ($log) {
        case 'mail_server_offline':
            return "The mail server is offline, emails cannot be sent.";
        case 'intrusion_detected':
            return "The system detected an intrusion and blocked access to the user.";
        case 'low_storage':
            return "The available storage level is critical, action must be taken to free up space.";
        default:
            return $log;
    }
}
?>
