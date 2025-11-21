<?php
// session.php - Include this at the top of every admin page
session_start();

// Check if user is logged in
if (!isset($_SESSION['librarian_id'])) {
    // Not logged in, redirect to login page
    header("Location: ../login.php");
    exit();
}

// Optional: Check if session has expired (after 2 hours of inactivity)
$inactive_timeout = 7200; // 2 hours in seconds

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive_timeout)) {
    // Session expired
    session_unset();
    session_destroy();
    header("Location: ../login.php?timeout=1");
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

// Make user info available to the page
$librarian_id = $_SESSION['librarian_id'];
$username = $_SESSION['username'];
$first_name = $_SESSION['first_name'];
$last_name = $_SESSION['last_name'];
$role = $_SESSION['role'];
$full_name = $first_name . ' ' . $last_name;
?>