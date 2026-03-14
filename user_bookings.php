<?php
$page_title = 'My Bookings';
require_once 'connect.php';
if (!isset($_SESSION['user_id'])) redirect('login.php');

$uid = (int)$_SESSION['user_id'];
$filter = sanitize($conn, $_GET['status'] ?? '');
$where = "WHERE b.user_id=$uid";
if ($filter) $where .= " AND b.status='$filter'";
$bookings = mysqli_query($conn, "SELECT b.*, sp.name as pname, sp.category, sp.phone as pphone, sp.location FROM bookings b JOIN service_providers sp ON b.provider_id=sp.id $where ORDER BY b.created_at DESC");

require_once 'header.php';
?>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-brand"><h4>Customer Portal</h4><p><?= htmlspecialchars($_SESSION['user_name']) ?></p></div>
        <nav class="sidebar-nav">
            <a href="user_dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="services.php"><i class="fas fa-search"></i> Find Services</a>
            <a href="user_bookings.php" class="active"><i class="fas fa-calendar-alt"></i> My Bookings</a>
            <div class="sidebar-divider"></div>
            <a href="logout.php" style="color:#ef4444;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>
    
    <main class="dashboard-content">
        <div class="dashboard-header">
            <h1>My Bookings</h1>
            <p>Track all your service booking requests</p>
        </div>
        
        <!-- Filter -->
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px;">
            <a href="user_bookings.php" class="btn btn-sm <?= !$filter?'btn-secondary':'btn-outline' ?>">All</a>
            <?php foreach (['pending','accepted','rejected','completed'] as $s): ?>
            <a href="user_bookings.php?status=<?= $s ?>" class="btn btn-sm <?= $filter===$s?'btn-primary':'btn-outline' ?>"><?= ucfirst($s) ?></a>
            <?php endforeach; ?>
        </div>
        
        <div class="table-card">
            <div class="table-card-header">
                <h3>Booking History</h3>
                <a href="services.php" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Booking</a>
            </div>
            <?php if (mysqli_num_rows($bookings) === 0): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3>No bookings found</h3>
                <p>You haven't made any <?= $filter ?> bookings yet.</p>
                <a href="services.php" class="btn btn-primary" style="margin-top:16px;">Find Services</a>
            </div>
            <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr><th>ID</th><th>Provider</th><th>Category</th><th>Location</th><th>Date & Time</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                    <?php while ($b = mysqli_fetch_assoc($bookings)): ?>
                    <tr>
                        <td>#<?= $b['id'] ?></td>
                        <td><strong><?= htmlspecialchars($b['pname']) ?></strong><br><small style="color:var(--text-muted)"><?= htmlspecialchars($b['pphone']) ?></small></td>
                        <td><?= htmlspecialchars($b['category']) ?></td>
                        <td><?= htmlspecialchars($b['location']) ?></td>
                        <td><?= $b['service_date'] ?><br><small style="color:var(--text-muted)"><?= substr($b['service_time'],0,5) ?></small></td>
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
