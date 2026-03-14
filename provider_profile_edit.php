<?php
$page_title = 'Edit Profile';
require_once 'connect.php';
if (!isset($_SESSION['provider_id'])) redirect('login.php');

$pid = (int)$_SESSION['provider_id'];
$provider = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM service_providers WHERE id=$pid"));

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = sanitize($conn, $_POST['name'] ?? '');
    $phone      = sanitize($conn, $_POST['phone'] ?? '');
    $category   = sanitize($conn, $_POST['category'] ?? '');
    $experience = (int)($_POST['experience'] ?? 0);
    $location   = sanitize($conn, $_POST['location'] ?? '');
    $bio        = sanitize($conn, $_POST['bio'] ?? '');

    if (empty($name) || empty($phone) || empty($category) || empty($location)) {
        $error = 'Please fill all required fields.';
    } else {
        mysqli_query($conn, "UPDATE service_providers SET name='$name', phone='$phone', category='$category', experience=$experience, location='$location', bio='$bio' WHERE id=$pid");
        $_SESSION['provider_name'] = $name;
        $provider = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM service_providers WHERE id=$pid"));
        $success = 'Profile updated successfully.';
    }

    // Password change
    if (!empty($_POST['new_password'])) {
        $newpass = $_POST['new_password'];
        $confirm = $_POST['confirm_password'] ?? '';
        if (strlen($newpass) < 6) {
            $error = 'Password must be at least 6 characters.';
        } elseif ($newpass !== $confirm) {
            $error = 'Passwords do not match.';
        } else {
            $hash = password_hash($newpass, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE service_providers SET password='$hash' WHERE id=$pid");
            $success .= ' Password updated.';
        }
    }
}

require_once 'header.php';
?>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-brand"><h4>Provider Portal</h4><p><?= htmlspecialchars($_SESSION['provider_name']) ?></p></div>
        <nav class="sidebar-nav">
            <a href="provider_dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="provider_bookings.php"><i class="fas fa-calendar-alt"></i> Booking Requests</a>
            <a href="provider_profile_edit.php" class="active"><i class="fas fa-user-edit"></i> Edit Profile</a>
            <div class="sidebar-divider"></div>
            <a href="logout.php" style="color:#ef4444;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </nav>
    </aside>
    
    <main class="dashboard-content">
        <div class="dashboard-header">
            <h1>Edit Profile</h1>
            <p>Update your provider information</p>
        </div>
        
        <?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div><?php endif; ?>
        
        <div class="table-card" style="margin-bottom:24px;">
            <div class="table-card-header"><h3>Profile Information</h3></div>
            <div style="padding:32px;">
                <form method="POST" class="validate-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name <span>*</span></label>
                            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($provider['name']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Phone Number <span>*</span></label>
                            <input type="text" name="phone" class="form-control" required value="<?= htmlspecialchars($provider['phone']) ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Service Category <span>*</span></label>
                            <select name="category" class="form-control" required>
                                <?php foreach (['Electrician','Plumber','Carpenter','Mechanic','Tutor','Cleaner'] as $c): ?>
                                <option <?= $provider['category']===$c?'selected':'' ?>><?= $c ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Years of Experience <span>*</span></label>
                            <input type="number" name="experience" class="form-control" min="0" max="60" value="<?= $provider['experience'] ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Location / City <span>*</span></label>
                        <input type="text" name="location" class="form-control" required value="<?= htmlspecialchars($provider['location']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Bio / Description</label>
                        <textarea name="bio" class="form-control" rows="4"><?= htmlspecialchars($provider['bio']) ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                </form>
            </div>
        </div>
        
        <div class="table-card">
            <div class="table-card-header"><h3>Change Password</h3></div>
            <div style="padding:32px;">
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="new_password" class="form-control" placeholder="Leave blank to keep current">
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Repeat new password">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-secondary"><i class="fas fa-lock"></i> Update Password</button>
                </form>
            </div>
        </div>
    </main>
</div>

<?php require_once 'footer.php'; ?>
