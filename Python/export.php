<?php
session_start();
$username = isset($_POST['username']) && !empty($_POST['username']) ? escapeshellarg($_POST['username']) : 'Guest';
$keyword = isset($_POST['keyword']) && !empty($_POST['keyword']) ? escapeshellarg($_POST['keyword']) : '';
$program = isset($_POST['program']) && !empty($_POST['program']) ? escapeshellarg($_POST['program']) : '';
$phase = isset($_POST['phase']) && !empty($_POST['phase']) ? escapeshellarg($_POST['phase']) : '';
$format = isset($_POST['format']) ? escapeshellarg($_POST['format']) : 'PDF'; // Default to Excel if not set
$role=$_POST['role'];


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

// Execute the command and capture both output and exit status
$output = [];
$status = -1;
exec($command, $output, $status);

// Check if the command executed successfully
if ($status === 0) {
    if ($role == 1) {
        header('Location: http://localhost/Admin/admin_home.php?res=1');
        exit();
    } elseif ($role == 0) {
        header('Location: http://localhost/User/user_home.php?res=1');
        exit();
    } elseif ($role == 2) {
        header('Location: http://localhost/Superadmin/superadmin_home.php?res=1');
        exit();
    }
} else {
    if ($role == 1) {
        header('Location: http://localhost/Admin/admin_home.php?res=0');
        exit();
    } elseif ($role == 0) {
        header('Location: http://localhost/User/user_home.php?res=0');
        exit();
    } elseif ($role == 2) {
        header('Location: http://localhost/Superadmin/superadmin_home.php?res=0');
        exit();
    }
}

exit();
