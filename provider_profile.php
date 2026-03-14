<?php
$page_title = 'Provider Profile';
require_once 'connect.php';
require_once 'header.php';

$id = (int)($_GET['id'] ?? 0);
$provider = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM service_providers WHERE id=$id AND status='approved'"));

if (!$provider) {
    echo '<div style="text-align:center;padding:100px 20px;"><h2>Provider not found.</h2><a href="services.php">Go back</a></div>';
    require_once 'footer.php';
    exit;
}

// Booking count
$bookingCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM bookings WHERE provider_id=$id AND status IN ('accepted','completed')"))['c'];
?>

<div class="profile-hero">
    <div class="container">
        <div class="profile-header-card">
            <div class="profile-avatar-lg"><?= strtoupper(substr($provider['name'],0,1)) ?></div>
            <div class="profile-info" style="flex:1;">
                <h1><?= htmlspecialchars($provider['name']) ?></h1>
                <span class="provider-badge" style="font-size:.9rem;padding:6px 16px;">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($provider['category']) ?>
                </span>
                <div class="meta">
                    <span><i class="fas fa-map-marker-alt"></i><?= htmlspecialchars($provider['location']) ?></span>
                    <span><i class="fas fa-briefcase"></i><?= $provider['experience'] ?> years experience</span>
                    <span><i class="fas fa-calendar-check"></i><?= $bookingCount ?> jobs completed</span>
                    <span><i class="fas fa-phone"></i><?= htmlspecialchars($provider['phone']) ?></span>
                </div>
            </div>
            <div>
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="book_service.php?provider_id=<?= $provider['id'] ?>" class="btn btn-primary">
                    <i class="fas fa-calendar-plus"></i> Book Now
                </a>
                <?php else: ?>
                <a href="login.php" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Login to Book</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<section class="section-pad" style="background:var(--bg-alt);">
    <div class="container">
        <div style="display:grid;grid-template-columns:2fr 1fr;gap:40px;align-items:start;" class="profile-grid">
            <div>
                <?php if ($provider['bio']): ?>
                <div class="table-card" style="margin-bottom:24px;">
                    <div class="table-card-header"><h3><i class="fas fa-user" style="color:var(--primary)"></i> About</h3></div>
                    <div style="padding:24px;line-height:1.8;color:var(--text-muted);">
                        <?= nl2br(htmlspecialchars($provider['bio'])) ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="table-card">
                    <div class="table-card-header"><h3><i class="fas fa-history" style="color:var(--primary)"></i> Recent Bookings</h3></div>
                    <?php
                    $bkgs = mysqli_query($conn, "SELECT b.*, u.name as user_name FROM bookings b JOIN users u ON b.user_id=u.id WHERE b.provider_id=$id ORDER BY b.created_at DESC LIMIT 5");
                    $bkgCount = mysqli_num_rows($bkgs);
                    ?>
                    <?php if ($bkgCount === 0): ?>
                    <div class="empty-state" style="padding:40px;"><i class="fas fa-calendar"></i><p>No bookings yet.</p></div>
                    <?php else: ?>
                    <div class="table-wrapper">
                        <table>
                            <thead><tr><th>Customer</th><th>Date</th><th>Status</th></tr></thead>
                            <tbody>
                            <?php while ($b = mysqli_fetch_assoc($bkgs)): ?>
                            <tr>
                                <td><?= htmlspecialchars($b['user_name']) ?></td>
                                <td><?= htmlspecialchars($b['service_date']) ?></td>
                                <td><span class="badge badge-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
                            </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div>
                <div class="table-card" style="margin-bottom:24px;">
                    <div class="table-card-header"><h3><i class="fas fa-info-circle" style="color:var(--primary)"></i> Details</h3></div>
                    <div style="padding:24px;">
                        <div class="provider-meta">
                            <span><i class="fas fa-tools"></i><strong>Service:</strong> <?= htmlspecialchars($provider['category']) ?></span>
                            <span><i class="fas fa-star"></i><strong>Experience:</strong> <?= $provider['experience'] ?> years</span>
                            <span><i class="fas fa-map-pin"></i><strong>Location:</strong> <?= htmlspecialchars($provider['location']) ?></span>
                            <span><i class="fas fa-envelope"></i><strong>Email:</strong> <?= htmlspecialchars($provider['email']) ?></span>
                            <span><i class="fas fa-calendar"></i><strong>Member since:</strong> <?= date('M Y', strtotime($provider['created_at'])) ?></span>
                        </div>
                    </div>
                </div>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="table-card">
                    <div class="table-card-header"><h3><i class="fas fa-calendar-plus" style="color:var(--primary)"></i> Quick Book</h3></div>
                    <div style="padding:24px;">
                        <a href="book_service.php?provider_id=<?= $provider['id'] ?>" class="btn btn-primary w-100 justify-center">
                            <i class="fas fa-bolt"></i> Book This Provider
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <div class="alert alert-info"><i class="fas fa-sign-in-alt"></i> <a href="login.php" style="color:inherit;font-weight:600;">Login</a> to book this provider.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
@media (max-width: 768px) {
    .profile-grid { grid-template-columns: 1fr !important; }
    .profile-header-card { flex-direction: column; text-align: center; }
    .profile-info .meta { justify-content: center; }
}
</style>

<?php require_once 'footer.php'; ?>
