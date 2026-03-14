<?php
$page_title = 'About Us';
require_once 'connect.php';
require_once 'header.php';
?>

<div class="about-hero">
    <div class="container">
        <div class="section-label" style="background:rgba(255,107,44,0.2);color:#ff9a6c;">Our Story</div>
        <h1>About Quick Finder</h1>
        <p>We're on a mission to make local service discovery simple, fast, and trustworthy for everyone.</p>
    </div>
</div>

<!-- Mission -->
<section class="section-pad" style="background:var(--bg);">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;" class="about-grid">
            <div>
                <div class="section-label">Our Mission</div>
                <h2 class="section-title" style="margin-bottom:20px;">Connecting Communities<br>with Trusted Experts</h2>
                <p style="color:var(--text-muted);line-height:1.8;margin-bottom:20px;">
                    Quick Finder was founded with a simple idea: finding a reliable local service provider shouldn't be a hassle. Whether you need your pipes fixed, a tutor for your child, or a carpenter for your home — we make it fast and effortless.
                </p>
                <p style="color:var(--text-muted);line-height:1.8;margin-bottom:32px;">
                    All our service providers go through a careful vetting process. We verify their credentials, experience, and location so you can book with confidence.
                </p>
                <div style="display:flex;gap:32px;">
                    <div><div class="stat-num" style="font-family:var(--font-head);font-size:2rem;font-weight:800;color:var(--primary);">500+</div><div style="font-size:.85rem;color:var(--text-muted);">Verified Providers</div></div>
                    <div><div class="stat-num" style="font-family:var(--font-head);font-size:2rem;font-weight:800;color:var(--primary);">10K+</div><div style="font-size:.85rem;color:var(--text-muted);">Happy Customers</div></div>
                    <div><div class="stat-num" style="font-family:var(--font-head);font-size:2rem;font-weight:800;color:var(--primary);">6</div><div style="font-size:.85rem;color:var(--text-muted);">Service Types</div></div>
                </div>
            </div>
            <div style="background:linear-gradient(135deg,var(--accent),#2a1a4e);border-radius:var(--radius-lg);padding:48px;color:#fff;">
                <h3 style="margin-bottom:24px;font-size:1.3rem;">Why Choose Quick Finder?</h3>
                <ul style="display:flex;flex-direction:column;gap:16px;">
                    <?php
                    $reasons = [
                        ['fas fa-shield-alt','Verified Providers','Every provider is reviewed and approved by our admin team.'],
                        ['fas fa-bolt','Instant Booking','Send booking requests in seconds, get confirmed fast.'],
                        ['fas fa-map-marker-alt','Local First','Find professionals right in your neighbourhood.'],
                        ['fas fa-star','Quality Assured','We monitor provider ratings and booking history.'],
                    ];
                    foreach ($reasons as [$icon,$title,$desc]): ?>
                    <li style="display:flex;gap:16px;align-items:flex-start;">
                        <div style="width:40px;height:40px;background:rgba(255,107,44,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--primary);font-size:16px;">
                            <i class="<?= $icon ?>"></i>
                        </div>
                        <div>
                            <strong style="display:block;margin-bottom:2px;"><?= $title ?></strong>
                            <span style="color:rgba(255,255,255,0.6);font-size:.88rem;"><?= $desc ?></span>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Values -->
<section class="section-pad" style="background:var(--bg-alt);">
    <div class="container text-center">
        <div class="section-label">What We Stand For</div>
        <h2 class="section-title">Our Core Values</h2>
        <p class="section-subtitle">Everything we do is guided by these principles.</p>
        <div class="values-grid">
            <?php
            $values = [
                ['fas fa-handshake','Trust','We verify every provider to protect customers.'],
                ['fas fa-bolt','Speed','From search to booking in under 60 seconds.'],
                ['fas fa-heart','Care','We genuinely care about your service experience.'],
                ['fas fa-globe','Community','Empowering local service professionals everywhere.'],
            ];
            foreach ($values as [$icon,$title,$desc]): ?>
            <div class="value-item">
                <div class="value-icon"><i class="<?= $icon ?>"></i></div>
                <h3><?= $title ?></h3>
                <p><?= $desc ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <div class="section-label" style="background:rgba(255,107,44,0.2);color:#ff9a6c;">Get Involved</div>
            <h2>Join Our Growing Network</h2>
            <p>Whether you're looking for help or offering services — Quick Finder is the place for you.</p>
            <div class="cta-actions">
                <a href="register_user.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Register as Customer</a>
                <a href="register_provider.php" class="btn btn-outline-white"><i class="fas fa-tools"></i> Join as Provider</a>
            </div>
        </div>
    </div>
</section>

<style>
@media (max-width: 768px) {
    .about-grid { grid-template-columns: 1fr !important; gap: 40px !important; }
}
</style>

<?php require_once 'footer.php'; ?>
