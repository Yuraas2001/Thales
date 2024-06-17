<?php
// Function to display the username with appropriate styling based on user type
function displayUsername($username, $userType) {
    // Check if the user type is 'superadmin'
    if ($userType == 'superadmin') {
        // If user is a superadmin, return the username wrapped in a span with a class for superadmin styling
        return '<span class="superadmin">' . htmlspecialchars($username) . '</span>';
    } else {
        // For all other users, return the username wrapped in a regular span
        return '<span>' . htmlspecialchars($username) . '</span>';
    }
}
?>
