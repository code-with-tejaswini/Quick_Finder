<?php
$page_title = 'My Dashboard';
require_once 'connect.php';
if (!isset($_SESSION['user_id'])) redirect('login.php');

$uid = (int)$_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id=$uid"));

$total   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM bookings WHERE user_id=$uid"))['c'];
$pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM bookings WHERE user_id=$uid AND status='pending'"))['c'];
$accepted= mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM bookings WHERE user_id=$uid AND status='accepted'"))['c'];
$completed=mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM bookings WHERE user_id=$uid AND status='completed'"))['c'];

$recent = mysqli_query($conn, "SELECT b.*, sp.name as pname, sp.category, sp.phone as pphone FROM bookings b JOIN service_providers sp ON b.provider_id=sp.id WHERE b.user_id=$uid ORDER BY b.created_at DESC LIMIT 5");

require_once 'header.php';
?>

<div class="dashboard-layout">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <h4>Customer Portal</h4>
            <p><?= htmlspecialchars($_SESSION['user_name']) ?></p>
        </div>
        <nav class="sidebar-nav">
            <a href="user_dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
            <a href="services.php"><i class="fas fa-search"></i> Find Services</a>
            <a href="user_bookings.php"><i class="fas fa-calendar-alt"></i> My Bookings</a>
            <div class="sidebar-divider"></div>
            <a href="logout.php" style="color:#ef4444;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>
    
    <!-- MAIN CONTENT -->
    <main class="dashboard-content">
        <div class="dashboard-header">
            <h1>Welcome back, <?= htmlspecialchars(explode(' ', $user['name'])[0]) ?>! 👋</h1>
            <p>Here's a summary of your activity</p>
        </div>
        
        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="stat-info"><p>Total Bookings</p><h3><?= $total ?></h3></div>
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
                <div class="stat-icon"><i class="fas fa-star"></i></div>
                <div class="stat-info"><p>Completed</p><h3><?= $completed ?></h3></div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="table-card" style="margin-bottom:24px;">
            <div class="table-card-header"><h3>Quick Actions</h3></div>
            <div style="padding:24px;display:flex;gap:12px;flex-wrap:wrap;">
                <a href="services.php" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Find Services</a>
                <a href="user_bookings.php" class="btn btn-secondary btn-sm"><i class="fas fa-list"></i> All Bookings</a>
            </div>
        </div>
        
        <!-- Recent Bookings -->
        <div class="table-card">
            <div class="table-card-header">
                <h3>Recent Bookings</h3>
                <a href="user_bookings.php" class="btn btn-outline btn-sm">View All</a>
            </div>
            <?php if (mysqli_num_rows($recent) === 0): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3>No bookings yet</h3>
                <p>Start by finding a service provider</p>
                <a href="services.php" class="btn btn-primary" style="margin-top:16px;">Find Services</a>
            </div>
            <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Provider</th>
                            <th>Category</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($b = mysqli_fetch_assoc($recent)): ?>
                    <tr>
                        <td><?= $b['id'] ?></td>
                        <td><strong><?= htmlspecialchars($b['pname']) ?></strong></td>
                        <td><?= htmlspecialchars($b['category']) ?></td>
                        <td><?= $b['service_date'] ?> <?= substr($b['service_time'],0,5) ?></td>
                        <td><span class="badge badge-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
                        <td><a href="provider_profile.php?id=<?= $b['provider_id'] ?>" class="btn btn-outline btn-sm">View</a></td>
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
