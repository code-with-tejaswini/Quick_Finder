<?php
$page_title = 'Manage Providers';
require_once 'connect.php';
if (!isset($_SESSION['admin_id'])) redirect('login.php');

$search  = sanitize($conn, $_GET['q'] ?? '');
$filter  = sanitize($conn, $_GET['status'] ?? '');
$where   = "WHERE 1=1";
if ($search) $where .= " AND (name LIKE '%$search%' OR email LIKE '%$search%' OR category LIKE '%$search%')";
if ($filter) $where .= " AND status='$filter'";

$providers = mysqli_query($conn, "SELECT * FROM service_providers $where ORDER BY created_at DESC");

require_once 'header.php';
?>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-brand"><h4>Admin Panel</h4><p><?= htmlspecialchars($_SESSION['admin_name']) ?></p></div>
        <nav class="sidebar-nav">
            <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="manage_users.php"><i class="fas fa-users"></i> Manage Users</a>
            <a href="manage_providers.php" class="active"><i class="fas fa-hard-hat"></i> Manage Providers</a>
            <a href="manage_bookings.php"><i class="fas fa-calendar-check"></i> Manage Bookings</a>
            <div class="sidebar-divider"></div>
            <a href="logout.php" style="color:#ef4444;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <main class="dashboard-content">
        <div class="dashboard-header">
            <h1>Manage Service Providers</h1>
            <p>Approve, reject, or remove service providers</p>
        </div>

        <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>

        <!-- Filters -->
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px;align-items:center;">
            <a href="manage_providers.php" class="btn btn-sm <?= !$filter?'btn-secondary':'btn-outline' ?>">All</a>
            <a href="manage_providers.php?status=pending" class="btn btn-sm <?= $filter==='pending'?'btn-warning':'btn-outline' ?>">Pending</a>
            <a href="manage_providers.php?status=approved" class="btn btn-sm <?= $filter==='approved'?'btn-success':'btn-outline' ?>">Approved</a>
            <a href="manage_providers.php?status=rejected" class="btn btn-sm <?= $filter==='rejected'?'btn-danger':'btn-outline' ?>">Rejected</a>
        </div>

        <div class="table-card">
            <div class="table-card-header">
                <h3>Providers (<?= mysqli_num_rows($providers) ?>)</h3>
                <form method="GET" style="display:flex;gap:8px;">
                    <input type="hidden" name="status" value="<?= htmlspecialchars($filter) ?>">
                    <input type="text" name="q" class="form-control" style="width:220px;padding:8px 12px;" placeholder="Search name, email, category..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
                    <?php if ($search): ?><a href="manage_providers.php?status=<?= $filter ?>" class="btn btn-outline btn-sm"><i class="fas fa-times"></i></a><?php endif; ?>
                </form>
            </div>
            <?php if (mysqli_num_rows($providers) === 0): ?>
            <div class="empty-state"><i class="fas fa-hard-hat"></i><h3>No providers found</h3></div>
            <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr><th>#</th><th>Name</th><th>Category</th><th>Location</th><th>Experience</th><th>Status</th><th>Joined</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                    <?php while ($p = mysqli_fetch_assoc($providers)): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td>
                            <strong><?= htmlspecialchars($p['name']) ?></strong><br>
                            <small style="color:var(--text-muted)"><?= htmlspecialchars($p['email']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($p['category']) ?></td>
                        <td><?= htmlspecialchars($p['location']) ?></td>
                        <td><?= $p['experience'] ?> yrs</td>
                        <td><span class="badge badge-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                        <td><?= date('M d, Y', strtotime($p['created_at'])) ?></td>
                        <td>
                            <div class="action-btns">
                                <?php if ($p['status'] === 'pending'): ?>
                                <a href="admin_action.php?action=approve_provider&id=<?= $p['id'] ?>" class="btn btn-success btn-sm" data-confirm="Approve this provider?"><i class="fas fa-check"></i> Approve</a>
                                <a href="admin_action.php?action=reject_provider&id=<?= $p['id'] ?>" class="btn btn-warning btn-sm" data-confirm="Reject this provider?"><i class="fas fa-times"></i> Reject</a>
                                <?php elseif ($p['status'] === 'approved'): ?>
                                <a href="admin_action.php?action=reject_provider&id=<?= $p['id'] ?>" class="btn btn-warning btn-sm" data-confirm="Suspend this provider?"><i class="fas fa-ban"></i> Suspend</a>
                                <?php elseif ($p['status'] === 'rejected'): ?>
                                <a href="admin_action.php?action=approve_provider&id=<?= $p['id'] ?>" class="btn btn-success btn-sm" data-confirm="Re-approve this provider?"><i class="fas fa-redo"></i> Re-approve</a>
                                <?php endif; ?>
                                <a href="admin_action.php?action=delete_provider&id=<?= $p['id'] ?>" class="btn btn-danger btn-sm" data-confirm="Permanently delete this provider and all bookings?"><i class="fas fa-trash"></i></a>
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
