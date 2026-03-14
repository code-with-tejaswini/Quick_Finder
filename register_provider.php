<?php
$page_title = 'Provider Registration';
require_once 'connect.php';

$errors = []; $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = sanitize($conn, $_POST['name'] ?? '');
    $email      = sanitize($conn, $_POST['email'] ?? '');
    $phone      = sanitize($conn, $_POST['phone'] ?? '');
    $category   = sanitize($conn, $_POST['category'] ?? '');
    $experience = (int)($_POST['experience'] ?? 0);
    $location   = sanitize($conn, $_POST['location'] ?? '');
    $bio        = sanitize($conn, $_POST['bio'] ?? '');
    $password   = $_POST['password'] ?? '';
    $confirm    = $_POST['confirm_password'] ?? '';

    if (empty($name)) $errors[] = 'Name is required.';
    if (empty($email) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
    if (empty($phone)) $errors[] = 'Phone is required.';
    if (empty($category)) $errors[] = 'Service category is required.';
    if (empty($location)) $errors[] = 'Location is required.';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm) $errors[] = 'Passwords do not match.';

    if (empty($errors)) {
        $check = mysqli_query($conn, "SELECT id FROM service_providers WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $errors[] = 'Email already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($conn, "INSERT INTO service_providers (name,email,phone,category,experience,location,bio,password) VALUES ('$name','$email','$phone','$category',$experience,'$location','$bio','$hash')");
            $success = 'Registration submitted! Your account is pending admin approval. You\'ll be able to login once approved.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provider Registration – Quick Finder</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="auth-page" style="padding-top:80px;">
    <div class="auth-card auth-card-wide">
        <div class="auth-header">
            <div class="auth-logo"><i class="fas fa-bolt" style="color:var(--primary)"></i> Quick<span>Finder</span></div>
            <h1>Join as Service Provider</h1>
            <p>Register and start receiving bookings</p>
        </div>
        <div class="auth-body">
            <?php if ($success): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php foreach ($errors as $e): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($e) ?></div>
            <?php endforeach; ?>
            
            <form method="POST" class="validate-form">
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name <span>*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Your full name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Email Address <span>*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="you@email.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Phone Number <span>*</span></label>
                        <input type="text" name="phone" class="form-control" placeholder="+1 555 000 0000" required value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Service Category <span>*</span></label>
                        <select name="category" class="form-control" required>
                            <option value="">Select category...</option>
                            <?php foreach (['Electrician','Plumber','Carpenter','Mechanic','Tutor','Cleaner'] as $cat): ?>
                            <option value="<?= $cat ?>" <?= (($_POST['category'] ?? '') === $cat) ? 'selected' : '' ?>><?= $cat ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Years of Experience <span>*</span></label>
                        <input type="number" name="experience" class="form-control" min="0" max="60" placeholder="e.g. 5" required value="<?= htmlspecialchars($_POST['experience'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Location / City <span>*</span></label>
                        <input type="text" name="location" class="form-control" placeholder="e.g. New York, NY" required value="<?= htmlspecialchars($_POST['location'] ?? '') ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label>Bio / Description</label>
                    <textarea name="bio" class="form-control" rows="3" placeholder="Tell customers about your expertise and services..."><?= htmlspecialchars($_POST['bio'] ?? '') ?></textarea>
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
                <div class="alert alert-warning"><i class="fas fa-info-circle"></i> Your account will be reviewed by an admin before you can login.</div>
                <button type="submit" class="btn btn-primary w-100 justify-center">
                    <i class="fas fa-paper-plane"></i> Submit Application
                </button>
            </form>
            
            <p style="text-align:center;font-size:.9rem;color:var(--text-muted);margin-top:24px;">
                Already registered? <a href="login.php" style="color:var(--primary);font-weight:600;">Sign in</a>
            </p>
        </div>
    </div>
</div>
<script src="js/script.js"></script>
</body>
</html>
