<!-- footer.php - Reusable footer -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="index.php" class="footer-logo">
                    <span class="logo-icon"><i class="fas fa-bolt"></i></span>
                    Quick<span>Finder</span>
                </a>
                <p>Connecting you with trusted local service professionals. Fast, reliable, and always nearby.</p>
                <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            
            <div class="footer-col">
                <h4>Services</h4>
                <ul>
                    <li><a href="services.php?cat=Electrician">Electrician</a></li>
                    <li><a href="services.php?cat=Plumber">Plumber</a></li>
                    <li><a href="services.php?cat=Carpenter">Carpenter</a></li>
                    <li><a href="services.php?cat=Mechanic">Mechanic</a></li>
                    <li><a href="services.php?cat=Tutor">Tutor</a></li>
                    <li><a href="services.php?cat=Cleaner">Cleaner</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register_user.php">Register as User</a></li>
                    <li><a href="register_provider.php">Join as Provider</a></li>
                </ul>
            </div>
            
            <div class="footer-col">
                <h4>Contact Us</h4>
                <ul class="contact-info">
                    <li><i class="fas fa-map-marker-alt"></i> 123 Main Street, New York, NY</li>
                    <li><i class="fas fa-phone"></i> +1 (555) 123-4567</li>
                    <li><i class="fas fa-envelope"></i> hello@quickfinder.com</li>
                    <li><i class="fas fa-clock"></i> Mon–Fri: 9am – 6pm</li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> QuickFinder. All rights reserved.</p>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<script src="js/script.js"></script>
</body>
</html>
