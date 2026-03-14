<?php
$page_title = 'Manage Bookings';
require_once 'connect.php';
if (!isset($_SESSION['admin_id'])) redirect('login.php');

$filter = sanitize($conn, $_GET['status'] ?? '');
$where  = "WHERE 1=1";
if ($filter) $where .= " AND b.status='$filter'";

$bookings = mysqli_query($conn, "SELECT b.*, u.name as uname, sp.name as pname, sp.category FROM bookings b JOIN users u ON b.user_id=u.id JOIN service_providers sp ON b.provider_id=sp.id $where ORDER BY b.created_at DESC");

require_once 'header.php';
?>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-brand"><h4>Admin Panel</h4><p><?= htmlspecialchars($_SESSION['admin_name']) ?></p></div>
        <nav class="sidebar-nav">
            <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a>
            <a href="manage_providers.php"><i class="fas fa-hard-hat"></i> Manage Providers</a>
            <a href="manage_bookings.php" class="active"><i class="fas fa-calendar-check"></i> Manage Bookings</a>
            <div class="sidebar-divider"></div>
            <a href="logout.php" style="color:#ef4444;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <main class="dashboard-content">
        <div class="dashboard-header">
            <h1>Manage Bookings</h1>
            <p>Overview of all platform bookings</p>
        </div>

        <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>

        <!-- Filter Tabs -->
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px;">
            <a href="manage_bookings.php" class="btn btn-sm <?= !$filter?'btn-secondary':'btn-outline' ?>">All</a>
            <?php foreach (['pending','accepted','rejected','completed'] as $s): ?>
            <a href="manage_bookings.php?status=<?= $s ?>" class="btn btn-sm <?= $filter===$s?'btn-primary':'btn-outline' ?>"><?= ucfirst($s) ?></a>
            <?php endforeach; ?>
        </div>

        <div class="table-card">
            <div class="table-card-header">
                <h3>All Bookings (<?= mysqli_num_rows($bookings) ?>)</h3>
            </div>
            <?php if (mysqli_num_rows($bookings) === 0): ?>
            <div class="empty-state"><i class="fas fa-calendar-times"></i><h3>No bookings found</h3></div>
            <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr><th>#</th><th>Customer</th><th>Provider</th><th>Category</th><th>Date & Time</th><th>Message</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                    <?php while ($b = mysqli_fetch_assoc($bookings)): ?>
                    <tr>
                        <td>#<?= $b['id'] ?></td>
                        <td><?= htmlspecialchars($b['uname']) ?></td>
                        <td><?= htmlspecialchars($b['pname']) ?></td>
                        <td><?= htmlspecialchars($b['category']) ?></td>
                        <td><?= $b['service_date'] ?><br><small><?= substr($b['service_time'],0,5) ?></small></td>
                        <td style="max-width:160px;font-size:.82rem;color:var(--text-muted);"><?= htmlspecialchars($b['message'] ?: '—') ?></td>
                        <td><span class="badge badge-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
                        <td>
                            <div class="action-btns">
                                <?php if ($b['status'] === 'pending'): ?>
                                <a href="update_booking.php?id=<?= $b['id'] ?>&action=accepted" class="btn btn-success btn-sm" data-confirm="Accept this booking?"><i class="fas fa-check"></i></a>
                                <a href="update_booking.php?id=<?= $b['id'] ?>&action=rejected" class="btn btn-warning btn-sm" data-confirm="Reject this booking?"><i class="fas fa-times"></i></a>
                                <?php endif; ?>
                                <a href="admin_action.php?action=delete_booking&id=<?= $b['id'] ?>" class="btn btn-danger btn-sm" data-confirm="Permanently delete this booking?"><i class="fas fa-trash"></i></a>
                            </div>
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
