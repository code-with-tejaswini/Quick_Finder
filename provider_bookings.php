<?php
$page_title = 'Booking Requests';
require_once 'connect.php';
if (!isset($_SESSION['provider_id'])) redirect('login.php');

$pid = (int)$_SESSION['provider_id'];
$filter = sanitize($conn, $_GET['status'] ?? '');
$where = "WHERE b.provider_id=$pid";
if ($filter) $where .= " AND b.status='$filter'";

$bookings = mysqli_query($conn, "SELECT b.*, u.name as uname, u.email as uemail, u.phone as uphon FROM bookings b JOIN users u ON b.user_id=u.id $where ORDER BY b.created_at DESC");

require_once 'header.php';
?>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-brand"><h4>Provider Portal</h4><p><?= htmlspecialchars($_SESSION['provider_name']) ?></p></div>
        <nav class="sidebar-nav">
            <a href="provider_dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="provider_bookings.php" class="active"><i class="fas fa-calendar-alt"></i> Booking Requests</a>
            <a href="provider_profile_edit.php"><i class="fas fa-user-edit"></i> Edit Profile</a>
            <div class="sidebar-divider"></div>
            <a href="logout.php" style="color:#ef4444;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>
    
    <main class="dashboard-content">
        <div class="dashboard-header">
            <h1>Booking Requests</h1>
            <p>Manage all incoming service requests</p>
        </div>
        
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px;">
            <a href="provider_bookings.php" class="btn btn-sm <?= !$filter?'btn-secondary':'btn-outline' ?>">All</a>
            <?php foreach (['pending','accepted','rejected','completed'] as $s): ?>
            <a href="provider_bookings.php?status=<?= $s ?>" class="btn btn-sm <?= $filter===$s?'btn-primary':'btn-outline' ?>"><?= ucfirst($s) ?></a>
            <?php endforeach; ?>
        </div>
        
        <div class="table-card">
            <div class="table-card-header"><h3>All Bookings</h3></div>
            <?php if (mysqli_num_rows($bookings) === 0): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No <?= $filter ?: '' ?> bookings found</h3>
            </div>
            <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr><th>#</th><th>Customer</th><th>Contact</th><th>Date & Time</th><th>Message</th><th>Requested</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                    <?php while ($b = mysqli_fetch_assoc($bookings)): ?>
                    <tr>
                        <td>#<?= $b['id'] ?></td>
                        <td><strong><?= htmlspecialchars($b['uname']) ?></strong></td>
                        <td><?= htmlspecialchars($b['uphon']) ?><br><small><?= htmlspecialchars($b['uemail']) ?></small></td>
                        <td><?= $b['service_date'] ?><br><small><?= substr($b['service_time'],0,5) ?></small></td>
                        <td style="max-width:200px;"><small><?= htmlspecialchars($b['message'] ?: '—') ?></small></td>
                        <td><small><?= date('M d, Y', strtotime($b['created_at'])) ?></small></td>
                        <td><span class="badge badge-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
                        <td>
                            <?php if ($b['status'] === 'pending'): ?>
                            <div class="action-btns">
                                <a href="update_booking.php?id=<?= $b['id'] ?>&action=accepted&ref=provider" class="btn btn-success btn-sm" data-confirm="Accept this booking?"><i class="fas fa-check"></i> Accept</a>
                                <a href="update_booking.php?id=<?= $b['id'] ?>&action=rejected&ref=provider" class="btn btn-danger btn-sm" data-confirm="Reject this booking?"><i class="fas fa-times"></i> Reject</a>
                            </div>
                            <?php elseif ($b['status'] === 'accepted'): ?>
                            <a href="update_booking.php?id=<?= $b['id'] ?>&action=completed&ref=provider" class="btn btn-info btn-sm" data-confirm="Mark as completed?"><i class="fas fa-flag-checkered"></i> Complete</a>
                            <?php else: ?>
                            <span style="color:var(--text-light);font-size:.85rem;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php require_once 'footer.php'; ?>
