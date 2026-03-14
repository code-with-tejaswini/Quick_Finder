<?php
$page_title = 'Provider Dashboard';
require_once 'connect.php';
if (!isset($_SESSION['provider_id'])) redirect('login.php');

$pid = (int)$_SESSION['provider_id'];
$provider = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM service_providers WHERE id=$pid"));

$total    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM bookings WHERE provider_id=$pid"))['c'];
$pending  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM bookings WHERE provider_id=$pid AND status='pending'"))['c'];
$accepted = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM bookings WHERE provider_id=$pid AND status='accepted'"))['c'];
$rejected = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM bookings WHERE provider_id=$pid AND status='rejected'"))['c'];

$recent = mysqli_query($conn, "SELECT b.*, u.name as uname, u.phone as uphon FROM bookings b JOIN users u ON b.user_id=u.id WHERE b.provider_id=$pid ORDER BY b.created_at DESC LIMIT 5");

require_once 'header.php';
?>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-brand"><h4>Provider Portal</h4><p><?= htmlspecialchars($_SESSION['provider_name']) ?></p></div>
        <nav class="sidebar-nav">
            <a href="provider_dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
            <a href="provider_bookings.php"><i class="fas fa-calendar-alt"></i> Booking Requests</a>
            <a href="provider_profile_edit.php"><i class="fas fa-user-edit"></i> Edit Profile</a>
            <div class="sidebar-divider"></div>
            <a href="logout.php" style="color:#ef4444;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>
    
    <main class="dashboard-content">
        <div class="dashboard-header">
            <h1>Welcome, <?= htmlspecialchars(explode(' ', $provider['name'])[0]) ?>! 👋</h1>
            <p><?= htmlspecialchars($provider['category']) ?> · <?= htmlspecialchars($provider['location']) ?></p>
        </div>
        
        <?php if ($pending > 0): ?>
        <div class="alert alert-warning"><i class="fas fa-bell"></i> You have <strong><?= $pending ?></strong> pending booking request(s)! <a href="provider_bookings.php?status=pending" style="font-weight:600;color:inherit;">Review now →</a></div>
        <?php endif; ?>
        
        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="stat-info"><p>Total Requests</p><h3><?= $total ?></h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-info"><p>Pending</p><h3><?= $pending ?></h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info"><p>Accepted</p><h3><?= $accepted ?></h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
                <div class="stat-info"><p>Rejected</p><h3><?= $rejected ?></h3></div>
            </div>
        </div>
        
        <!-- Recent Requests -->
        <div class="table-card">
            <div class="table-card-header">
                <h3>Recent Booking Requests</h3>
                <a href="provider_bookings.php" class="btn btn-outline btn-sm">View All</a>
            </div>
            <?php if (mysqli_num_rows($recent) === 0): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No booking requests yet</h3>
                <p>When customers book you, requests will appear here.</p>
            </div>
            <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead><tr><th>#</th><th>Customer</th><th>Date</th><th>Message</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php while ($b = mysqli_fetch_assoc($recent)): ?>
                    <tr>
                        <td><?= $b['id'] ?></td>
                        <td><strong><?= htmlspecialchars($b['uname']) ?></strong><br><small><?= htmlspecialchars($b['uphon']) ?></small></td>
                        <td><?= $b['service_date'] ?><br><small><?= substr($b['service_time'],0,5) ?></small></td>
                        <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($b['message'] ?: '—') ?></td>
                        <td><span class="badge badge-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
                        <td>
                            <?php if ($b['status'] === 'pending'): ?>
                            <div class="action-btns">
                                <a href="update_booking.php?id=<?= $b['id'] ?>&action=accepted" class="btn btn-success btn-sm" data-confirm="Accept this booking?"><i class="fas fa-check"></i></a>
                                <a href="update_booking.php?id=<?= $b['id'] ?>&action=rejected" class="btn btn-danger btn-sm" data-confirm="Reject this booking?"><i class="fas fa-times"></i></a>
                            </div>
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
