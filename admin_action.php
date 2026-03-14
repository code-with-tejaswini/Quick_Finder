<?php
// admin_action.php - Handles all admin actions
require_once 'connect.php';
if (!isset($_SESSION['admin_id'])) redirect('login.php');

$action = sanitize($conn, $_GET['action'] ?? '');
$id     = (int)($_GET['id'] ?? 0);

switch ($action) {
    case 'approve_provider':
        mysqli_query($conn, "UPDATE service_providers SET status='approved' WHERE id=$id");
        redirect('manage_providers.php?msg=Provider approved successfully.');
        break;

    case 'reject_provider':
        mysqli_query($conn, "UPDATE service_providers SET status='rejected' WHERE id=$id");
        redirect('manage_providers.php?msg=Provider rejected.');
        break;

    case 'delete_provider':
        // Also delete their bookings
        mysqli_query($conn, "DELETE FROM bookings WHERE provider_id=$id");
        mysqli_query($conn, "DELETE FROM service_providers WHERE id=$id");
        redirect('manage_providers.php?msg=Provider deleted.');
        break;

    case 'delete_user':
        mysqli_query($conn, "DELETE FROM bookings WHERE user_id=$id");
        mysqli_query($conn, "DELETE FROM users WHERE id=$id");
        redirect('manage_users.php?msg=User deleted.');
        break;

    case 'toggle_user':
        $cur = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM users WHERE id=$id"));
        $new = ($cur['status'] === 'active') ? 'inactive' : 'active';
        mysqli_query($conn, "UPDATE users SET status='$new' WHERE id=$id");
        redirect('manage_users.php?msg=User status updated.');
        break;

    case 'delete_booking':
        mysqli_query($conn, "DELETE FROM bookings WHERE id=$id");
        redirect('manage_bookings.php?msg=Booking deleted.');
        break;

    default:
        redirect('admin_dashboard.php');
}
