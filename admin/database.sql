-- Lost and Found Items System Database Schema
-- LFIS Database Setup

-- Create Database
CREATE DATABASE IF NOT EXISTS lfis_db;
USE lfis_db;

-- Lost Reports Table
CREATE TABLE IF NOT EXISTS lost_reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item_name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    date_lost DATE NOT NULL,
    time_lost TIME,
    location VARCHAR(255) NOT NULL,
    building VARCHAR(100),
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    institution VARCHAR(255),
    reward DECIMAL(10, 2),
    photo_url VARCHAR(500),
    status ENUM('pending', 'approved', 'rejected', 'recovered') DEFAULT 'pending',
    notify_matches BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_category (category),
    INDEX idx_email (email),
    INDEX idx_created_at (created_at)
);

-- Found Reports Table
CREATE TABLE IF NOT EXISTS found_reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item_name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    date_found DATE NOT NULL,
    time_found TIME,
    location VARCHAR(255) NOT NULL,
    building VARCHAR(100),
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    institution VARCHAR(255),
    item_condition ENUM('Excellent', 'Good', 'Fair', 'Poor') DEFAULT 'Good',
    photo VARCHAR(500) NOT NULL,
    notes TEXT,
    status ENUM('pending', 'approved', 'rejected', 'recovered') DEFAULT 'pending',
    notify_matches BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_category (category),
    INDEX idx_email (email),
    INDEX idx_created_at (created_at)
);

-- Matches Table
CREATE TABLE IF NOT EXISTS matches (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lost_report_id INT NOT NULL,
    found_report_id INT NOT NULL,
    match_score INT DEFAULT 0,
    status ENUM('pending_verification', 'approved', 'rejected') DEFAULT 'pending_verification',
    admin_notes TEXT,
    verified_by VARCHAR(255),
    verified_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (lost_report_id) REFERENCES lost_reports(id) ON DELETE CASCADE,
    FOREIGN KEY (found_report_id) REFERENCES found_reports(id) ON DELETE CASCADE,
    INDEX idx_status (status),
    INDEX idx_match_score (match_score),
    INDEX idx_created_at (created_at)
);

-- Users Table (for future authentication system)
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20),
    institution VARCHAR(255),
    password_hash VARCHAR(255),
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_status (status)
);

-- Admin Logs Table
CREATE TABLE IF NOT EXISTS admin_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    action VARCHAR(255) NOT NULL,
    description TEXT,
    admin_username VARCHAR(255),
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_created_at (created_at),
    INDEX idx_admin_username (admin_username)
);

-- Contact Messages Table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sender_name VARCHAR(255) NOT NULL,
    sender_email VARCHAR(255) NOT NULL,
    sender_phone VARCHAR(20),
    receiver_email VARCHAR(255) NOT NULL,
    match_id INT,
    message TEXT NOT NULL,
    status ENUM('sent', 'read', 'replied') DEFAULT 'sent',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE SET NULL,
    INDEX idx_created_at (created_at),
    INDEX idx_status (status)
);

-- Settings Table
CREATE TABLE IF NOT EXISTS settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(255) NOT NULL UNIQUE,
    setting_value LONGTEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert Default Settings
INSERT INTO settings (setting_key, setting_value) VALUES
('site_name', 'LFIS - Lost and Found Items System'),
('site_email', 'admin@lfis.com'),
('max_upload_size', '5'),
('items_per_page', '10'),
('session_timeout', '30'),
('match_threshold', '85'),
('enable_email_notifications', '1'),
('enable_automatic_matching', '1'),
('enable_user_registration', '1');

-- Admin default user
INSERT INTO users (full_name, email, password_hash, status)
VALUES ('admin', 'admin@lfis.com', MD5('admin123'), 'active');
