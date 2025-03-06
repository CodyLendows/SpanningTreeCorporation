<?php
session_start();
function isAuthenticated() {
    return isset($_SESSION['user']);
}

function hasAccess($page) {
    if (!isAuthenticated()) return false;
    return in_array($page, $_SESSION['access']);
}
?>
