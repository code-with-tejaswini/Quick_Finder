// ============================================================
// Quick Finder - Main JavaScript
// ============================================================

document.addEventListener('DOMContentLoaded', function () {

    // ---- NAVBAR SCROLL EFFECT ----
    const navbar = document.getElementById('navbar');
    if (navbar) {
        function handleScroll() {
            if (window.scrollY > 30) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }
        window.addEventListener('scroll', handleScroll);
        handleScroll(); // Run on load for dashboard pages
    }

    // ---- MOBILE MENU TOGGLE ----
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function () {
            navMenu.classList.toggle('open');
            document.body.style.overflow = navMenu.classList.contains('open') ? 'hidden' : '';
        });
        // Close on nav link click
        navMenu.querySelectorAll('.nav-link, .nav-btn').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('open');
                document.body.style.overflow = '';
            });
        });
    }

    // ---- FORM VALIDATION ----
    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function validatePhone(phone) {
        return /^[\d\s\+\-\(\)]{8,15}$/.test(phone.trim());
    }

    function setFieldError(field, errorMsg) {
        field.classList.add('error');
        let errEl = field.nextElementSibling;
        if (!errEl || !errEl.classList.contains('error-text')) {
            errEl = document.createElement('div');
            errEl.className = 'error-text';
            field.parentNode.insertBefore(errEl, field.nextSibling);
        }
        errEl.style.display = 'block';
        errEl.textContent = errorMsg;
    }

    function clearFieldError(field) {
        field.classList.remove('error');
        const errEl = field.nextElementSibling;
        if (errEl && errEl.classList.contains('error-text')) {
            errEl.style.display = 'none';
        }
    }

    // Validate all forms with class 'validate-form'
    document.querySelectorAll('.validate-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            let valid = true;

            // Required fields
            form.querySelectorAll('[required]').forEach(field => {
                if (!field.value.trim()) {
                    setFieldError(field, 'This field is required.');
                    valid = false;
                } else {
                    clearFieldError(field);
                }
            });

            // Email fields
            form.querySelectorAll('input[type="email"]').forEach(field => {
                if (field.value && !validateEmail(field.value)) {
                    setFieldError(field, 'Please enter a valid email address.');
                    valid = false;
                }
            });

            // Phone fields
            form.querySelectorAll('input[name="phone"]').forEach(field => {
                if (field.value && !validatePhone(field.value)) {
                    setFieldError(field, 'Please enter a valid phone number (8-15 digits).');
                    valid = false;
                }
            });

            // Password confirmation
            const password = form.querySelector('input[name="password"]');
            const confirm = form.querySelector('input[name="confirm_password"]');
            if (password && confirm) {
                if (password.value.length < 6) {
                    setFieldError(password, 'Password must be at least 6 characters.');
                    valid = false;
                } else if (confirm.value && password.value !== confirm.value) {
                    setFieldError(confirm, 'Passwords do not match.');
                    valid = false;
                }
            }

            if (!valid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = form.querySelector('.error');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });

        // Live validation on input
        form.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('input', function () {
                if (field.value.trim()) clearFieldError(field);
            });
        });
    });

    // ---- LOGIN ROLE TABS ----
    const roleTabs = document.querySelectorAll('.role-tab');
    const roleInput = document.getElementById('roleInput');
    if (roleTabs.length && roleInput) {
        roleTabs.forEach(tab => {
            tab.addEventListener('click', function () {
                roleTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                roleInput.value = this.dataset.role;
            });
        });
    }

    // ---- AUTO-DISMISS ALERTS ----
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // ---- BOOKING DATE MIN ----
    const bookingDate = document.getElementById('service_date');
    if (bookingDate) {
        const today = new Date().toISOString().split('T')[0];
        bookingDate.min = today;
        if (!bookingDate.value) bookingDate.value = today;
    }

    // ---- CONFIRM DIALOGS ----
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm || 'Are you sure?')) {
                e.preventDefault();
            }
        });
    });

    // ---- SEARCH FILTER SUBMIT ----
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.querySelectorAll('select').forEach(sel => {
            sel.addEventListener('change', function () {
                filterForm.submit();
            });
        });
    }

    // ---- FADE IN ON SCROLL ----
    const fadeEls = document.querySelectorAll('.animate-on-scroll');
    if (fadeEls.length) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        fadeEls.forEach(el => observer.observe(el));
    }

    // ---- COUNTER ANIMATION ----
    document.querySelectorAll('.count-up').forEach(el => {
        const target = parseInt(el.dataset.target || el.textContent);
        if (isNaN(target)) return;
        let current = 0;
        const step = Math.ceil(target / 60);
        const timer = setInterval(() => {
            current = Math.min(current + step, target);
            el.textContent = current.toLocaleString() + (el.dataset.suffix || '');
            if (current >= target) clearInterval(timer);
        }, 16);
    });

    // ---- SIDEBAR ACTIVE ----
    const currentPath = window.location.pathname.split('/').pop();
    document.querySelectorAll('.sidebar-nav a').forEach(link => {
        const href = link.getAttribute('href');
        if (href && href.includes(currentPath) && currentPath !== '') {
            link.classList.add('active');
        }
    });

    // ---- IMAGE PREVIEW ----
    const profileImageInput = document.getElementById('profile_image');
    const profilePreview = document.getElementById('profilePreview');
    if (profileImageInput && profilePreview) {
        profileImageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = e => { profilePreview.src = e.target.result; };
                reader.readAsDataURL(file);
            }
        });
    }

    // ---- SEARCH FORM ----
    const heroSearch = document.getElementById('heroSearch');
    if (heroSearch) {
        heroSearch.addEventListener('submit', function(e) {
            e.preventDefault();
            const cat = document.getElementById('searchCat').value;
            const loc = document.getElementById('searchLoc').value;
            let url = 'services.php?';
            if (cat) url += 'cat=' + encodeURIComponent(cat);
            if (loc) url += '&loc=' + encodeURIComponent(loc);
            window.location.href = url;
        });
    }
});
