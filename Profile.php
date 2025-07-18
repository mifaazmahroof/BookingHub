<?php
/* Template Name: Profile */
include 'futsal_db.php';
session_start();
$role = $_SESSION['role'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;
$user = null; // Initialize to avoid undefined variable
$full_name = null;
if (!$role) {
    include 'logout.php';
    exit;
}
if ($role === "customer" && $user_id) {
    $user = getCustomerDetails($user_id); // Assumes function exists and returns full name
	$full_name = $user['full_name'];
}

if ($role === "client" && $user_id) {
    $user = getClientName($user_id); // Changed from $client_id to $user_id assuming same session key
	$full_name = $user['name'];
}

switch ($role) {
    case 'customer':
        include 'customer_dashboard.php';
        break;

    case 'client':
        include 'OwnerDashboard.php';
        break;

    default:
        echo '<p style="color:red;">Invalid role assigned.</p>';
        break;
}
?>
