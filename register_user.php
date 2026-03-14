<?php
$page_title = 'Register';
require_once 'connect.php';

$errors = []; $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = sanitize($conn, $_POST['name'] ?? '');
    $email    = sanitize($conn, $_POST['email'] ?? '');
    $phone    = sanitize($conn, $_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (empty($name)) $errors[] = 'Name is required.';
    if (empty($email) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
    if (empty($phone)) $errors[] = 'Phone is required.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm) $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $errors[] = 'Email already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($conn, "INSERT INTO users (name, email, phone, password) VALUES ('$name','$email','$phone','$hash')");
            $success = 'Registration successful! You can now <a href="login.php">login here</a>.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – Quick Finder</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-logo"><i class="fas fa-bolt" style="color:var(--primary)"></i> Quick<span>Finder</span></div>
            <h1>Create Account</h1>
            <p>Join as a customer today</p>
        </div>
        <div class="auth-body">
            <?php if ($success): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $success ?></div>
            <?php endif; ?>
            <?php foreach ($errors as $e): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($e) ?></div>
            <?php endforeach; ?>
            
            <form method="POST" class="validate-form">
                <div class="form-group">
                    <label>Full Name <span>*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="John Doe" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Email Address <span>*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="you@email.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Phone Number <span>*</span></label>
                    <input type="text" name="phone" class="form-control" placeholder="+1 555 123 4567" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Password <span>*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 6 characters" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password <span>*</span></label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Repeat password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 justify-center" style="margin-top:8px;">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>
            
            <p style="text-align:center;font-size:.9rem;color:var(--text-muted);margin-top:24px;">
                Already have an account? <a href="login.php" style="color:var(--primary);font-weight:600;">Sign in</a>
            </p>
            <p style="text-align:center;font-size:.9rem;color:var(--text-muted);margin-top:8px;">
                Want to offer services? <a href="register_provider.php" style="color:var(--primary);font-weight:600;">Join as Provider</a>
            </p>
        </div>
    </div>
</div>
<script src="js/script.js"></script>
</body>
</html>
