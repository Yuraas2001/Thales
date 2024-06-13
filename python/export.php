<?php
session_start();
$username = escapeshellarg($_SESSION['username']); // Escape the username for shell safety
$keyword = isset($_POST['keyword']) ? escapeshellarg($_POST['keyword']) : '';
$program = isset($_POST['program']) ? escapeshellarg($_POST['program']) : '';
$phase = isset($_POST['phase']) ? escapeshellarg($_POST['phase']) : '';
$format = isset($_POST['format']) ? escapeshellarg($_POST['format']) : 'excel'; // Default to Excel if not set

// Construct the command with parameters
$command = "python3 export.py -u $username";
if (!empty($keyword)) $command .= " -k $keyword";
if (!empty($program)) $command .= " -p $program";
if (!empty($phase)) $command .= " -ph $phase";
$command .= " -f $format 2>&1";

echo "Command : $command\n";

// Execute the command and capture both output and exit status
$output = [];
$status = -1;
exec($command, $output, $status);

// Check if the command executed successfully
if ($status === 0) {
    // Output contains any output from the command
    echo "Command output: " . implode("\n", $output) . "\n";
    echo "Export generated successfully.";
} else {
    echo "An error occurred while generating the export.";
    echo "Command output: " . implode("\n", $output) . "\n";
    echo "Status: $status\n";
}
?>