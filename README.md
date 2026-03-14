# Quick Finder – Service Provider Platform
## Complete Deployment Guide

---

## 📁 Project Structure

```
quick-finder/
├── index.php                  ← Home page
├── services.php               ← Browse providers (search & filter)
├── about.php                  ← About page
├── contact.php                ← Contact form
├── login.php                  ← Unified login (3 roles)
├── logout.php                 ← Session destroyer
├── register_user.php          ← Customer registration
├── register_provider.php      ← Provider registration
├── provider_profile.php       ← Public provider profile
├── book_service.php           ← Booking form (users)
├── user_dashboard.php         ← Customer dashboard
├── user_bookings.php          ← Customer booking history
├── provider_dashboard.php     ← Provider dashboard
├── provider_bookings.php      ← Provider booking management
├── provider_profile_edit.php  ← Provider profile editor
├── update_booking.php         ← Accept/Reject/Complete handler
├── admin_dashboard.php        ← Admin overview
├── admin_action.php           ← Admin action handler
├── manage_users.php           ← Admin: manage customers
├── manage_providers.php       ← Admin: approve/reject providers
├── manage_bookings.php        ← Admin: view all bookings
├── connect.php                ← DB connection + helpers
├── header.php                 ← Shared navbar
├── footer.php                 ← Shared footer
├── css/
│   └── style.css              ← Full responsive stylesheet
├── js/
│   └── script.js              ← Validation + UI interactions
└── database.sql               ← Full MySQL schema + sample data
```

---

## 🚀 Local Setup (XAMPP / WAMP / MAMP)

### Step 1 – Install a local server
- Download XAMPP: https://www.apachefriends.org/
- Install and start **Apache** and **MySQL**

### Step 2 – Place project files
```
Copy the `quick-finder/` folder to:
  C:\xampp\htdocs\quick-finder\     (Windows)
  /Applications/XAMPP/htdocs/quick-finder/  (Mac)
```

### Step 3 – Create the database
1. Open browser → http://localhost/phpmyadmin
2. Click **New** → Database name: `quick_finder` → Create
3. Click the `quick_finder` database → **Import** tab
4. Choose the `database.sql` file → Click **Go**

### Step 4 – Configure DB credentials
Edit `connect.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // your MySQL username
define('DB_PASS', '');          // your MySQL password (blank by default)
define('DB_NAME', 'quick_finder');
```

### Step 5 – Access the site
Open: http://localhost/quick-finder/

---

## 🔐 Default Login Credentials

| Role     | Email                    | Password  |
|----------|--------------------------|-----------|
| Admin    | admin@quickfinder.com    | password  |
| Provider | john@example.com         | password  |
| Provider | maria@example.com        | password  |
| User     | alice@example.com        | password  |

> All passwords in `database.sql` use `password_hash()` with the value `password`.

---

## 🌐 Deploying to Live Server (cPanel / Hosting)

### Step 1 – Upload files
- Use **FileZilla** or cPanel **File Manager**
- Upload all project files to `public_html/quick-finder/`
  (or `public_html/` if it's the root domain)

### Step 2 – Create MySQL database on host
1. Go to cPanel → **MySQL Databases**
2. Create a database and a user
3. Add user to database with **ALL PRIVILEGES**

### Step 3 – Import SQL
1. Go to cPanel → **phpMyAdmin**
2. Select the new database → Import → Upload `database.sql`

### Step 4 – Update connect.php
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_cpanel_db_user');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'your_db_name');
```

### Step 5 – Visit your domain
`https://yourdomain.com/quick-finder/`

---

## 🔒 Security Notes

- All user inputs are sanitized using `mysqli_real_escape_string()`
- Passwords are hashed with PHP `password_hash()` / `password_verify()`
- Session-based authentication for all 3 roles
- Each dashboard page checks session before rendering
- Admin actions are session-protected

---

## 📱 Features Summary

### Public
- Home, Services, About, Contact pages
- Search & filter providers by category + location
- Provider public profile view

### Customer (User)
- Register & login
- Browse and search providers
- Book service with date, time, message
- View booking history with status

### Service Provider
- Register (pending approval)
- Login after admin approves
- View & manage booking requests (accept / reject / complete)
- Edit profile and change password

### Admin
- Dashboard with stats
- Approve / reject / delete providers
- Manage users (toggle status, delete)
- View and manage all bookings

---

## 🛠 Tech Stack
- **Frontend**: HTML5, CSS3 (custom), JavaScript (vanilla)
- **Backend**: PHP 8+ (procedural)
- **Database**: MySQL
- **Fonts**: Syne + DM Sans (Google Fonts)
- **Icons**: Font Awesome 6

---

## ✅ PHP Requirements
- PHP 7.4+ (PHP 8.x recommended)
- MySQLi extension enabled
- `session_start()` support (standard)

---

## 📞 Support
For issues or customization contact: hello@quickfinder.com
