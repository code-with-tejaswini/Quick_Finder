<?php
$page_title = 'Find Services';
require_once 'connect.php';
require_once 'header.php';

$cat = sanitize($conn, $_GET['cat'] ?? '');
$loc = sanitize($conn, $_GET['loc'] ?? '');

$where = "WHERE status='approved'";
if ($cat) $where .= " AND category='$cat'";
if ($loc) $where .= " AND location LIKE '%$loc%'";

$providers = mysqli_query($conn, "SELECT * FROM service_providers $where ORDER BY name ASC");
$count = mysqli_num_rows($providers);

$categories = ['Electrician','Plumber','Carpenter','Mechanic','Tutor','Cleaner'];
?>

<div class="services-hero">
    <div class="container text-center">
        <div class="section-label">Explore</div>
        <h1>Find Your Service Provider</h1>
        <p>Browse verified local professionals ready to help</p>
    </div>
</div>

<section class="section-pad-sm" style="background:var(--bg-alt);">
    <div class="container">
        <!-- Filter Bar -->
        <form id="filterForm" method="GET" class="filter-bar">
            <label><i class="fas fa-filter" style="color:var(--primary)"></i> Filter:</label>
            <select name="cat" class="form-control" style="width:auto;padding:10px 16px;">
                <option value="">All Categories</option>
                <?php foreach ($categories as $c): ?>
                <option value="<?= $c ?>" <?= $cat===$c?'selected':'' ?>><?= $c ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="loc" class="form-control" style="width:220px;" placeholder="Search by city..." value="<?= htmlspecialchars($loc) ?>">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Search</button>
            <?php if ($cat || $loc): ?>
            <a href="services.php" class="btn btn-outline btn-sm"><i class="fas fa-times"></i> Clear</a>
            <?php endif; ?>
        </form>
        
        <!-- Category Pills -->
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:32px;">
            <a href="services.php" class="btn btn-sm <?= !$cat ? 'btn-secondary' : 'btn-outline' ?>">All</a>
            <?php foreach ($categories as $c): ?>
            <a href="services.php?cat=<?= $c ?>" class="btn btn-sm <?= $cat===$c ? 'btn-primary' : 'btn-outline' ?>"><?= $c ?></a>
            <?php endforeach; ?>
        </div>
        
        <!-- Results Header -->
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
            <h3 style="font-size:1rem;color:var(--text-muted);">
                <strong style="color:var(--accent)"><?= $count ?></strong> provider<?= $count!=1?'s':'' ?> found
                <?= $cat ? ' in <strong>'.htmlspecialchars($cat).'</strong>' : '' ?>
                <?= $loc ? ' near <strong>'.htmlspecialchars($loc).'</strong>' : '' ?>
            </h3>
        </div>
        
        <?php if ($count === 0): ?>
        <div class="empty-state">
            <i class="fas fa-search-minus"></i>
            <h3>No providers found</h3>
            <p>Try adjusting your filters or search for a different location.</p>
            <a href="services.php" class="btn btn-primary" style="margin-top:16px;">View All Providers</a>
        </div>
        <?php else: ?>
        <div class="providers-grid">
            <?php while ($p = mysqli_fetch_assoc($providers)): ?>
            <div class="provider-card">
                <div class="provider-card-header">
                    <div class="provider-avatar"><?= strtoupper(substr($p['name'],0,1)) ?></div>
                    <h3><?= htmlspecialchars($p['name']) ?></h3>
                    <span class="provider-badge"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($p['category']) ?></span>
                </div>
                <div class="provider-card-body">
                    <div class="provider-meta">
                        <span><i class="fas fa-map-marker-alt"></i><?= htmlspecialchars($p['location']) ?></span>
                        <span><i class="fas fa-briefcase"></i><?= $p['experience'] ?> years experience</span>
                        <?php if ($p['bio']): ?>
                        <span><i class="fas fa-quote-left"></i><?= htmlspecialchars(substr($p['bio'],0,60)) . (strlen($p['bio'])>60?'...':'') ?></span>
                        <?php endif; ?>
                    </div>
                    <a href="provider_profile.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm w-100 justify-center">
                        <i class="fas fa-eye"></i> View Profile & Book
                    </a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once 'footer.php'; ?>
