<?php
$page_title = 'Book Service';
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) redirect('login.php');

$provider_id = (int)($_GET['provider_id'] ?? $_POST['provider_id'] ?? 0);
$provider = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM service_providers WHERE id=$provider_id AND status='approved'"));

if (!$provider) {
    redirect('services.php');
}

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id    = (int)$_SESSION['user_id'];
    $sdate      = sanitize($conn, $_POST['service_date'] ?? '');
    $stime      = sanitize($conn, $_POST['service_time'] ?? '');
    $message    = sanitize($conn, $_POST['message'] ?? '');
    
    if (empty($sdate) || empty($stime)) {
        $error = 'Please select date and time.';
    } else {
        // Check duplicate
        $dup = mysqli_query($conn, "SELECT id FROM bookings WHERE user_id=$user_id AND provider_id=$provider_id AND service_date='$sdate' AND status IN ('pending','accepted')");
        if (mysqli_num_rows($dup) > 0) {
            $error = 'You already have a booking with this provider on that date.';
        } else {
            mysqli_query($conn, "INSERT INTO bookings (user_id, provider_id, service_date, service_time, message) VALUES ($user_id, $provider_id, '$sdate', '$stime', '$message')");
            $success = 'Booking request sent! The provider will confirm shortly.';
        }
    }
}

require_once 'header.php';
?>

<div class="page-hero">
    <div class="container">
        <h1><i class="fas fa-calendar-plus" style="color:var(--primary)"></i> Book a Service</h1>
        <p>Schedule your appointment with <?= htmlspecialchars($provider['name']) ?></p>
    </div>
</div>

<section class="section-pad-sm" style="background:var(--bg-alt);">
    <div class="container" style="max-width:800px;">
        <!-- Provider Summary -->
        <div class="provider-card" style="margin-bottom:32px;display:flex;align-items:center;gap:24px;padding:24px;border-radius:var(--radius);">
            <div class="provider-avatar" style="width:64px;height:64px;font-size:1.4rem;border:none;flex-shrink:0;">
                <?= strtoupper(substr($provider['name'],0,1)) ?>
            </div>
            <div style="flex:1;">
                <h3 style="margin-bottom:4px;"><?= htmlspecialchars($provider['name']) ?></h3>
                <span class="provider-badge"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($provider['category']) ?></span>
                <p style="margin-top:8px;font-size:.88rem;color:var(--text-muted);">
                    <i class="fas fa-map-marker-alt" style="color:var(--primary)"></i> <?= htmlspecialchars($provider['location']) ?>
                    &nbsp;|&nbsp;
                    <i class="fas fa-briefcase" style="color:var(--primary)"></i> <?= $provider['experience'] ?> yrs exp
                </p>
            </div>
            <a href="provider_profile.php?id=<?= $provider['id'] ?>" class="btn btn-outline btn-sm">View Profile</a>
        </div>
        
        <?php if ($success): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= $success ?> <a href="user_bookings.php">View Bookings</a></div>
        <?php endif; ?>
        <?php if ($error): ?>
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <div class="form-card">
            <div class="form-card-header">
                <h1>Booking Details</h1>
                <p>Fill in the details for your appointment</p>
            </div>
            <div class="form-card-body">
                <form method="POST" class="validate-form">
                    <input type="hidden" name="provider_id" value="<?= $provider_id ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Preferred Date <span>*</span></label>
                            <input type="date" name="service_date" id="service_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Preferred Time <span>*</span></label>
                            <input type="time" name="service_time" class="form-control" required value="09:00">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Message / Service Details</label>
                        <textarea name="message" class="form-control" rows="4" placeholder="Describe the work needed, access details, or any special instructions..."></textarea>
                    </div>
                    
                    <div class="alert alert-info" style="margin-bottom:20px;">
                        <i class="fas fa-info-circle"></i>
                        Your booking will be sent as a request. The provider will accept or reject it.
                    </div>
                    
                    <div style="display:flex;gap:12px;">
                        <a href="services.php" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
                        <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;">
                            <i class="fas fa-paper-plane"></i> Send Booking Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>
