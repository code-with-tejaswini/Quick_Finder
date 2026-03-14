<?php
$page_title = 'Admin Dashboard';
require_once 'connect.php';
if (!isset($_SESSION['admin_id'])) redirect('login.php');

$totalUsers     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users"))['c'];
$totalProviders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM service_providers"))['c'];
$pendingProviders = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM service_providers WHERE status='pending'"))['c'];
$totalBookings  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM bookings"))['c'];

$recentBookings = mysqli_query($conn, "SELECT b.*, u.name as uname, sp.name as pname, sp.category FROM bookings b JOIN users u ON b.user_id=u.id JOIN service_providers sp ON b.provider_id=sp.id ORDER BY b.created_at DESC LIMIT 5");
$recentProviders = mysqli_query($conn, "SELECT * FROM service_providers WHERE status='pending' ORDER BY created_at DESC LIMIT 5");

require_once 'header.php';
?>

<div class="dashboard-layout">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h4>Admin Panel</h4>
            <p><?= htmlspecialchars($_SESSION['admin_name']) ?></p>
        </div>
        <nav class="sidebar-nav">
            <a href="admin_dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a>
            <a href="manage_providers.php"><i class="fas fa-hard-hat"></i> Manage Providers</a>
            <a href="manage_bookings.php"><i class="fas fa-calendar-check"></i> Manage Bookings</a>
            <div class="sidebar-divider"></div>
            <a href="logout.php" style="color:#ef4444;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <!-- MAIN -->
    <main class="dashboard-content">
        <div class="dashboard-header">
            <h1>Admin Dashboard</h1>
            <p>Platform overview & management</p>
        </div>

        <?php if ($pendingProviders > 0): ?>
        <div class="alert alert-warning">
            <i class="fas fa-bell"></i>
            <strong><?= $pendingProviders ?></strong> provider application(s) awaiting approval.
            <a href="manage_providers.php?status=pending" style="font-weight:700;color:inherit;"> Review now →</a>
        </div>
        <?php endif; ?>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-info"><p>Total Users</p><h3><?= $totalUsers ?></h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-hard-hat"></i></div>
                <div class="stat-info"><p>Total Providers</p><h3><?= $totalProviders ?></h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-info"><p>Total Bookings</p><h3><?= $totalBookings ?></h3></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-info"><p>Pending Approvals</p><h3><?= $pendingProviders ?></h3></div>
            </div>
        </div>

        <!-- Pending Providers -->
        <?php if (mysqli_num_rows($recentProviders) > 0): ?>
        <div class="table-card" style="margin-bottom:24px;">
            <div class="table-card-header">
                <h3><i class="fas fa-hourglass-half" style="color:var(--pending)"></i> Pending Provider Approvals</h3>
                <a href="manage_providers.php?status=pending" class="btn btn-warning btn-sm">View All</a>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead><tr><th>Name</th><th>Category</th><th>Location</th><th>Applied</th><th>Action</th></tr></thead>
                    <tbody>
                    <?php while ($p = mysqli_fetch_assoc($recentProviders)): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($p['name']) ?></strong><br><small><?= htmlspecialchars($p['email']) ?></small></td>
                        <td><?= htmlspecialchars($p['category']) ?></td>
                        <td><?= htmlspecialchars($p['location']) ?></td>
                        <td><?= date('M d, Y', strtotime($p['created_at'])) ?></td>
                        <td>
                            <div class="action-btns">
                                <a href="admin_action.php?action=approve_provider&id=<?= $p['id'] ?>" class="btn btn-success btn-sm" data-confirm="Approve this provider?"><i class="fas fa-check"></i> Approve</a>
                                <a href="admin_action.php?action=reject_provider&id=<?= $p['id'] ?>" class="btn btn-danger btn-sm" data-confirm="Reject this provider?"><i class="fas fa-times"></i> Reject</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Recent Bookings -->
        <div class="table-card">
            <div class="table-card-header">
                <h3>Recent Bookings</h3>
                <a href="manage_bookings.php" class="btn btn-outline btn-sm">View All</a>
            </div>
            <?php if (mysqli_num_rows($recentBookings) === 0): ?>
            <div class="empty-state"><i class="fas fa-calendar"></i><p>No bookings yet.</p></div>
            <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead><tr><th>#</th><th>Customer</th><th>Provider</th><th>Category</th><th>Date</th><th>Status</th></tr></thead>
                    <tbody>
                    <?php while ($b = mysqli_fetch_assoc($recentBookings)): ?>
                    <tr>
                        <td>#<?= $b['id'] ?></td>
                        <td><?= htmlspecialchars($b['uname']) ?></td>
                        <td><?= htmlspecialchars($b['pname']) ?></td>
                        <td><?= htmlspecialchars($b['category']) ?></td>
                        <td><?= $b['service_date'] ?></td>
                        <td><span class="badge badge-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
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
