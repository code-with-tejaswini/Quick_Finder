<?php
$page_title = 'Login';
require_once 'connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = sanitize($conn, $_POST['role'] ?? 'user');
    $email = sanitize($conn, $_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        if ($role === 'user') {
            $res = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND status='active'");
            $user = mysqli_fetch_assoc($res);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                redirect('user_dashboard.php');
            } else {
                $error = 'Invalid credentials or account is inactive.';
            }
        } elseif ($role === 'provider') {
            $res = mysqli_query($conn, "SELECT * FROM service_providers WHERE email='$email' AND status='approved'");
            $prov = mysqli_fetch_assoc($res);
            if ($prov && password_verify($password, $prov['password'])) {
                $_SESSION['provider_id'] = $prov['id'];
                $_SESSION['provider_name'] = $prov['name'];
                redirect('provider_dashboard.php');
            } else {
                $error = 'Invalid credentials or your account is not approved yet.';
            }
        } elseif ($role === 'admin') {
            $res = mysqli_query($conn, "SELECT * FROM admin WHERE email='$email'");
            $adm = mysqli_fetch_assoc($res);
            if ($adm && password_verify($password, $adm['password'])) {
                $_SESSION['admin_id'] = $adm['id'];
                $_SESSION['admin_name'] = $adm['name'];
                redirect('admin_dashboard.php');
            } else {
                $error = 'Invalid admin credentials.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Quick Finder</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo"><i class="fas fa-bolt" style="color:var(--primary)"></i> Quick<span>Finder</span></div>
            <h1>Welcome Back</h1>
            <p>Sign in to your account</p>
        </div>
        <div class="auth-body">
            <?php if ($error): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <p style="font-size:.88rem;color:var(--text-muted);margin-bottom:12px;font-weight:500;">Login as:</p>
            <div class="role-tabs">
                <button type="button" class="role-tab active" data-role="user">Customer</button>
                <button type="button" class="role-tab" data-role="provider">Provider</button>
                <button type="button" class="role-tab" data-role="admin">Admin</button>
            </div>
            
            <form method="POST" class="validate-form">
                <input type="hidden" name="role" id="roleInput" value="user">
                
                <div class="form-group">
                    <label>Email Address <span>*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="you@email.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Password <span>*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 justify-center" style="margin-top:8px;">
                    <i class="fas fa-sign-in-alt"></i> Sign In
                </button>
            </form>
            
            <div class="form-divider" style="margin:24px 0;">or</div>
            
            <p style="text-align:center;font-size:.9rem;color:var(--text-muted);">
                New customer? <a href="register_user.php" style="color:var(--primary);font-weight:600;">Register here</a>
            </p>
            <p style="text-align:center;font-size:.9rem;color:var(--text-muted);margin-top:8px;">
                Want to provide services? <a href="register_provider.php" style="color:var(--primary);font-weight:600;">Join as Provider</a>
            </p>
            
            <div class="alert alert-info" style="margin-top:24px;font-size:.8rem;">
                <div>
                    <strong>Demo Credentials:</strong><br>
                    Admin: admin@quickfinder.com / password<br>
                    Provider: john@example.com / password<br>
                    User: alice@example.com / password
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/script.js"></script>
</body>
</html>
