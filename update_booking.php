<?php
// update_booking.php - Handle booking status changes
require_once 'connect.php';

$id     = (int)($_GET['id'] ?? 0);
$action = sanitize($conn, $_GET['action'] ?? '');
$ref    = sanitize($conn, $_GET['ref'] ?? 'provider');

$allowed = ['accepted','rejected','completed'];
if (!in_array($action, $allowed) || $id === 0) redirect('login.php');

if (isset($_SESSION['provider_id'])) {
    $pid = (int)$_SESSION['provider_id'];
    mysqli_query($conn, "UPDATE bookings SET status='$action' WHERE id=$id AND provider_id=$pid");
    redirect('provider_bookings.php');
} elseif (isset($_SESSION['admin_id'])) {
    mysqli_query($conn, "UPDATE bookings SET status='$action' WHERE id=$id");
    redirect('manage_bookings.php');
} else {
    redirect('login.php');
}
