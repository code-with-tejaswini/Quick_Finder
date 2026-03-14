-- ============================================================
-- Quick Finder - Service Provider Platform
-- Database Schema
-- ============================================================

CREATE DATABASE IF NOT EXISTS quick_finder CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quick_finder;

-- Admin Table
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default admin: admin@quickfinder.com / admin123
INSERT INTO admin (name, email, password) VALUES 
('Super Admin', 'admin@quickfinder.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Service Providers Table
CREATE TABLE IF NOT EXISTS service_providers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    category VARCHAR(100) NOT NULL,
    experience INT DEFAULT 0,
    location VARCHAR(200) NOT NULL,
    bio TEXT,
    password VARCHAR(255) NOT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    profile_image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    provider_id INT NOT NULL,
    service_date DATE NOT NULL,
    service_time TIME NOT NULL,
    message TEXT,
    status ENUM('pending','accepted','rejected','completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (provider_id) REFERENCES service_providers(id) ON DELETE CASCADE
);

-- Contact Messages Table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample Data: Service Providers
INSERT INTO service_providers (name, email, phone, category, experience, location, bio, password, status) VALUES
('John Carter', 'john@example.com', '9876543210', 'Electrician', 8, 'New York, NY', 'Licensed electrician with 8 years of residential and commercial experience.', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'approved'),
('Maria Santos', 'maria@example.com', '9876543211', 'Plumber', 5, 'Los Angeles, CA', 'Expert plumber specializing in repairs and new installations.', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'approved'),
('David Kim', 'david@example.com', '9876543212', 'Carpenter', 10, 'Chicago, IL', 'Master carpenter creating custom furniture and woodwork.', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'approved'),
('Sarah Johnson', 'sarah@example.com', '9876543213', 'Tutor', 6, 'Houston, TX', 'Math & Science tutor. PhD candidate helping students excel.', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'approved'),
('Mike Thompson', 'mike@example.com', '9876543214', 'Mechanic', 12, 'Phoenix, AZ', 'ASE certified mechanic. All makes and models.', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'approved'),
('Lisa Chen', 'lisa@example.com', '9876543215', 'Cleaner', 4, 'Philadelphia, PA', 'Professional home and office cleaning services.', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'approved');

-- Sample Users
INSERT INTO users (name, email, phone, password, status) VALUES
('Alice Brown', 'alice@example.com', '5551234567', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active'),
('Bob Wilson', 'bob@example.com', '5559876543', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active');
