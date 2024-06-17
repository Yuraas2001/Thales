<?php
$program = isset($_POST['program']) && !empty($_POST['program']) ? escapeshellarg($_POST['program']) : '';
$phase = isset($_POST['phase']) && !empty($_POST['phase']) ? escapeshellarg($_POST['phase']) : '';
$format = isset($_POST['format']) ? escapeshellarg($_POST['format']) : 'excel'; // Default to Excel if not set


// Construct the command with required parameters
$pythonPath = "./myenv/bin/python3";
$command = "$pythonPath export.py -u $username";


// Add optional parameters only if they are provided
if (!empty($keyword)) {
   $command .= " -k $keyword";
}
if (!empty($program)) {
   $command .= " -p $program";
}
if (!empty($phase)) {
   $command .= " -ph $phase";
}


$command .= " -f $format 2>&1";


// Display the command for debugging purposes
echo "Command : $command\n";


// Execute the command and capture both output and exit status
$output = [];
$status = -1;
exec($command, $output, $status);




// Check if the command executed successfully
if ($status === 0) {
   $_SESSION['export_status'] = "Export generated successfully.";
} else {
   $_SESSION['export_status'] = "An error occurred while generating the export.";
}


// Redirect back to the page with the form
header("Location: ../Admin/admin_home.php");
exit;
?>
