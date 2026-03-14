<?php
$page_title = 'Contact Us';
require_once 'connect.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = sanitize($conn, $_POST['name'] ?? '');
    $email   = sanitize($conn, $_POST['email'] ?? '');
    $subject = sanitize($conn, $_POST['subject'] ?? '');
    $message = sanitize($conn, $_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email.';
    } else {
        mysqli_query($conn, "INSERT INTO contact_messages (name,email,subject,message) VALUES ('$name','$email','$subject','$message')");
        $success = 'Thank you for reaching out! We\'ll get back to you within 24 hours.';
    }
}

require_once 'header.php';
?>

<div class="contact-hero">
    <div class="container">
        <div class="section-label" style="background:rgba(255,107,44,0.2);color:#ff9a6c;">Get In Touch</div>
        <h1>Contact Us</h1>
        <p>We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
    </div>
</div>

<section class="section-pad" style="background:var(--bg-alt);">
    <div class="container">
        <div class="contact-grid">
            <!-- Info -->
            <div class="contact-info-card">
                <h3>Let's Talk</h3>
                <p>Whether you have a question, feedback, or need support — we're here to help.</p>

                <div class="contact-item">
                    <div class="contact-item-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="contact-item-text">
                        <strong>Our Office</strong>
                        <span>123 Main Street, New York, NY 10001</span>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-item-icon"><i class="fas fa-phone"></i></div>
                    <div class="contact-item-text">
                        <strong>Phone</strong>
                        <span>+1 (555) 123-4567</span>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-item-icon"><i class="fas fa-envelope"></i></div>
                    <div class="contact-item-text">
                        <strong>Email</strong>
                        <span>hello@quickfinder.com</span>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-item-icon"><i class="fas fa-clock"></i></div>
                    <div class="contact-item-text">
                        <strong>Working Hours</strong>
                        <span>Mon–Fri: 9am – 6pm EST</span>
                    </div>
                </div>

                <div class="social-links" style="margin-top:32px;">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Form -->
            <div>
                <div class="form-card">
                    <div class="form-card-header">
                        <h1>Send a Message</h1>
                        <p>We typically reply within 24 hours</p>
                    </div>
                    <div class="form-card-body">
                        <?php if ($success): ?>
                        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
                        <?php endif; ?>
                        <?php if ($error): ?>
                        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <form method="POST" class="validate-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Your Name <span>*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="John Doe" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label>Email Address <span>*</span></label>
                                    <input type="email" name="email" class="form-control" placeholder="you@email.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Subject <span>*</span></label>
                                <input type="text" name="subject" class="form-control" placeholder="How can we help?" required value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label>Message <span>*</span></label>
                                <textarea name="message" class="form-control" rows="5" placeholder="Tell us more about your query..." required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 justify-center">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'footer.php'; ?>
