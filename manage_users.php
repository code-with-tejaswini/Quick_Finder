<?php
$page_title = 'Manage Users';
require_once 'connect.php';
if (!isset($_SESSION['admin_id'])) redirect('login.php');

$search = sanitize($conn, $_GET['q'] ?? '');
$where = $search ? "WHERE name LIKE '%$search%' OR email LIKE '%$search%'" : '';
$users = mysqli_query($conn, "SELECT * FROM users $where ORDER BY created_at DESC");

require_once 'header.php';
?>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-brand"><h4>Admin Panel</h4><p><?= htmlspecialchars($_SESSION['admin_name']) ?></p></div>
        <nav class="sidebar-nav">
            <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            <a href="manage_users.php" class="active"><i class="fas fa-users"></i> Manage Users</a>
            <a href="manage_providers.php"><i class="fas fa-hard-hat"></i> Manage Providers</a>
            <a href="manage_bookings.php"><i class="fas fa-calendar-check"></i> Manage Bookings</a>
            <div class="sidebar-divider"></div>
            <a href="logout.php" style="color:#ef4444;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>

    <main class="dashboard-content">
        <div class="dashboard-header">
            <h1>Manage Users</h1>
            <p>View and manage all registered customers</p>
        </div>

        <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($_GET['msg']) ?></div>
        <?php endif; ?>

        <div class="table-card">
            <div class="table-card-header">
                <h3>All Users (<?= mysqli_num_rows($users) ?>)</h3>
                <form method="GET" style="display:flex;gap:8px;">
                    <input type="text" name="q" class="form-control" style="width:220px;padding:8px 12px;" placeholder="Search name or email..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
                    <?php if ($search): ?><a href="manage_users.php" class="btn btn-outline btn-sm"><i class="fas fa-times"></i></a><?php endif; ?>
                </form>
            </div>
            <?php if (mysqli_num_rows($users) === 0): ?>
            <div class="empty-state"><i class="fas fa-users"></i><h3>No users found</h3></div>
            <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Joined</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                    <?php while ($u = mysqli_fetch_assoc($users)): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><strong><?= htmlspecialchars($u['name']) ?></strong></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= htmlspecialchars($u['phone']) ?></td>
                        <td><span class="badge badge-<?= $u['status'] ?>"><?= ucfirst($u['status']) ?></span></td>
                        <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                        <td>
                            <div class="action-btns">
                                <a href="admin_action.php?action=toggle_user&id=<?= $u['id'] ?>" class="btn btn-warning btn-sm" data-confirm="Toggle status for this user?">
                                    <i class="fas fa-toggle-<?= $u['status']==='active'?'on':'off' ?>"></i>
                                </a>
                                <a href="admin_action.php?action=delete_user&id=<?= $u['id'] ?>" class="btn btn-danger btn-sm" data-confirm="Permanently delete this user and all their bookings?">
                                    <i class="fas fa-trash"></i>
                                </a>
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
