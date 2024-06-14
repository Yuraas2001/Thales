<?php
function displayUsername($username, $userType) {
    if ($userType == 'superadmin') {
        return '<span class="superadmin">' . htmlspecialchars($username) . '</span>';
    } else {
        return '<span>' . htmlspecialchars($username) . '</span>';
    }
}
?>
