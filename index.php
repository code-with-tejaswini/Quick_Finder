<?php
$page_title = 'Home';
require_once 'connect.php';
require_once 'header.php';
?>

<!-- HERO -->
<section class="hero">
    <div class="hero-container">
        <div class="hero-content fade-in">
            <div class="hero-badge">
                <i class="fas fa-shield-check"></i>
                Trusted by 10,000+ Customers
            </div>
            <h1 class="hero-title">
                Find Expert<br><span>Service Providers</span><br>Near You
            </h1>
            <p class="hero-desc">
                Connect instantly with verified local professionals — electricians, plumbers, mechanics, tutors, and more. Fast booking, trusted results.
            </p>
            <div class="hero-actions">
                <a href="services.php" class="btn btn-primary"><i class="fas fa-search"></i> Find Services</a>
                <a href="register_provider.php" class="btn btn-outline-white"><i class="fas fa-briefcase"></i> Become a Provider</a>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-num">500+</div>
                    <div class="stat-label">Service Providers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">10K+</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">6</div>
                    <div class="stat-label">Service Categories</div>
                </div>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-cards-grid">
                <?php
                $icons = ['Electrician'=>'bolt','Plumber'=>'wrench','Carpenter'=>'hammer','Mechanic'=>'car','Tutor'=>'graduation-cap','Cleaner'=>'broom'];
                $counts = [42, 38, 29, 55, 33, 47];
                $i = 0;
                foreach ($icons as $cat => $icon):
                ?>
                <a href="services.php?cat=<?= $cat ?>" class="hero-card">
                    <div class="hero-card-icon"><i class="fas fa-<?= $icon ?>"></i></div>
                    <h4><?= $cat ?></h4>
                    <p><?= $counts[$i] ?> providers</p>
                </a>
                <?php $i++; endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- SEARCH BAR -->
<section class="search-section" style="background:var(--bg-alt); padding-bottom:60px;">
    <div class="container">
        <div class="search-wrapper">
            <h3><i class="fas fa-search" style="color:var(--primary)"></i> Quick Search</h3>
            <form class="search-form" id="heroSearch">
                <div class="form-group">
                    <select id="searchCat" name="cat" class="form-control">
                        <option value="">All Categories</option>
                        <option>Electrician</option>
                        <option>Plumber</option>
                        <option>Carpenter</option>
                        <option>Mechanic</option>
                        <option>Tutor</option>
                        <option>Cleaner</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" id="searchLoc" name="loc" class="form-control" placeholder="Enter your city...">
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
            </form>
        </div>
    </div>
</section>

<!-- SERVICES SECTION -->
<section class="section-pad" style="background:var(--bg);">
    <div class="container text-center">
        <div class="section-label">What We Offer</div>
        <h2 class="section-title">Browse Service Categories</h2>
        <p class="section-subtitle">From home repairs to personal tutoring — find the right expert for every need.</p>
        
        <div class="services-grid">
            <?php
            $services = [
                ['Electrician','bolt','Wiring, repairs, installations'],
                ['Plumber','wrench','Pipes, leaks, drainage'],
                ['Carpenter','hammer','Furniture, woodwork, fitting'],
                ['Mechanic','car','Car repairs, servicing'],
                ['Tutor','graduation-cap','Academic tutoring, coaching'],
                ['Cleaner','broom','Home & office cleaning'],
            ];
            foreach ($services as [$name,$icon,$desc]): ?>
            <a href="services.php?cat=<?= $name ?>" class="service-card">
                <div class="service-icon"><i class="fas fa-<?= $icon ?>"></i></div>
                <h3><?= $name ?></h3>
                <p><?= $desc ?></p>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="section-pad" style="background:var(--bg-alt);">
    <div class="container text-center">
        <div class="section-label">Simple Process</div>
        <h2 class="section-title">How Quick Finder Works</h2>
        <p class="section-subtitle">Get connected with the right service provider in just 3 easy steps.</p>
        
        <div class="steps-grid">
            <div class="step-item fade-in-delay-1">
                <div class="step-num">1</div>
                <h3>Search a Service</h3>
                <p>Browse categories or search by your location to find available providers.</p>
            </div>
            <div class="step-item fade-in-delay-2">
                <div class="step-num">2</div>
                <h3>Book a Provider</h3>
                <p>View profiles, check experience, and send a booking request in seconds.</p>
            </div>
            <div class="step-item fade-in-delay-3">
                <div class="step-num">3</div>
                <h3>Get the Job Done</h3>
                <p>Provider confirms the booking, arrives at your location, and delivers the service.</p>
            </div>
        </div>
    </div>
</section>

<!-- FEATURED PROVIDERS -->
<section class="section-pad" style="background:var(--bg);">
    <div class="container">
        <div class="text-center">
            <div class="section-label">Top Rated</div>
            <h2 class="section-title">Featured Providers</h2>
            <p class="section-subtitle">Meet some of our highly rated service professionals.</p>
        </div>
        
        <?php
        $result = mysqli_query($conn, "SELECT * FROM service_providers WHERE status='approved' ORDER BY id DESC LIMIT 3");
        ?>
        <div class="providers-grid" style="margin-top:48px;">
            <?php while ($p = mysqli_fetch_assoc($result)): ?>
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
                    </div>
                    <a href="provider_profile.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm">View Profile</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        
        <div class="text-center" style="margin-top:40px;">
            <a href="services.php" class="btn btn-secondary"><i class="fas fa-th"></i> View All Providers</a>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <div class="section-label" style="background:rgba(255,107,44,0.2);color:#ff9a6c;">Join Quick Finder</div>
            <h2>Ready to Get Started?</h2>
            <p>Register as a customer to book services, or join as a provider to grow your business.</p>
            <div class="cta-actions">
                <a href="register_user.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> Register as Customer</a>
                <a href="register_provider.php" class="btn btn-outline-white"><i class="fas fa-tools"></i> Join as Provider</a>
            </div>
        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>
