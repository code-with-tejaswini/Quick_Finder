<?php
// header.php - Reusable header/navigation
if (session_status() === PHP_SESSION_NONE) session_start();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' – Quick Finder' : 'Quick Finder – Find Local Service Providers' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar" id="navbar">
    <div class="nav-container">
        <a href="index.php" class="nav-logo">
            <span class="logo-icon"><i class="fas fa-bolt"></i></span>
            Quick<span>Finder</span>
        </a>
        
        <div class="nav-menu" id="navMenu">
            <a href="index.php" class="nav-link <?= $current_page=='index.php'?'active':'' ?>">Home</a>
            <a href="services.php" class="nav-link <?= $current_page=='services.php'?'active':'' ?>">Services</a>
            <a href="about.php" class="nav-link <?= $current_page=='about.php'?'active':'' ?>">About</a>
            <a href="contact.php" class="nav-link <?= $current_page=='contact.php'?'active':'' ?>">Contact</a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="user_dashboard.php" class="nav-link">Dashboard</a>
                <a href="logout.php" class="nav-btn btn-outline">Logout</a>
            <?php elseif (isset($_SESSION['provider_id'])): ?>
                <a href="provider_dashboard.php" class="nav-link">Dashboard</a>
                <a href="logout.php" class="nav-btn btn-outline">Logout</a>
            <?php elseif (isset($_SESSION['admin_id'])): ?>
                <a href="admin_dashboard.php" class="nav-link">Admin Panel</a>
                <a href="logout.php" class="nav-btn btn-outline">Logout</a>
            <?php else: ?>
                <a href="login.php" class="nav-link <?= $current_page=='login.php'?'active':'' ?>">Login</a>
                <a href="register_user.php" class="nav-btn btn-primary">Get Started</a>
            <?php endif; ?>
        </div>
        
        <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>
