-- ============================================================
--  AURELIA HOTEL — Hotel Management System
--  Database Schema
--  Import this file first (phpMyAdmin -> Import, or:
--  mysql -u root -p < schema.sql)
-- ============================================================

CREATE DATABASE IF NOT EXISTS aurelia_hotel
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE aurelia_hotel;

-- ------------------------------------------------------------
-- Admins (hotel staff who manage the system)
-- ------------------------------------------------------------
CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('super_admin','manager','receptionist') DEFAULT 'receptionist',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default admin login -> email: admin@aurelia.com | password: Admin@123
-- (This hash is a real, working bcrypt hash for "Admin@123" — change the password after first login!)
INSERT INTO admins (full_name, email, password, role) VALUES
('Aurelia Manager', 'admin@aurelia.com', '$2b$10$EtY7P1Joxit36cQy7KfK8O6DOvaRtRnrryb.sBxWTM/feJlIrlxJW', 'super_admin');

-- ------------------------------------------------------------
-- Customers (guests who register on the public site)
-- ------------------------------------------------------------
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  phone VARCHAR(30),
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Room categories (Standard, Deluxe, Suite ...)
-- ------------------------------------------------------------
CREATE TABLE room_types (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) NOT NULL,
  description TEXT,
  base_price DECIMAL(10,2) NOT NULL,
  capacity INT NOT NULL DEFAULT 2,
  size_sqm INT DEFAULT 25,
  amenities TEXT COMMENT 'comma separated list'
) ENGINE=InnoDB;

INSERT INTO room_types (name, description, base_price, capacity, size_sqm, amenities) VALUES
('Classic Room','Warm, quiet, and comfortable — the essential Aurelia stay.', 85.00, 2, 22, 'Queen bed,Free Wi-Fi,City view,Air conditioning,Work desk'),
('Deluxe Room','More space and a private balcony overlooking the courtyard.', 130.00, 2, 30, 'King bed,Free Wi-Fi,Balcony,Mini bar,Rain shower,Air conditioning'),
('Executive Suite','A separate lounge, garden views, and thoughtful extra touches.', 210.00, 3, 45, 'King bed,Lounge area,Free Wi-Fi,Garden view,Bathtub,Nespresso machine'),
('Aurelia Grand Suite','Our signature suite — two rooms, a private terrace, and a dining nook.', 340.00, 4, 70, 'Two bedrooms,Private terrace,Free Wi-Fi,Dining area,Jacuzzi,Butler service');

-- ------------------------------------------------------------
-- Individual physical rooms
-- ------------------------------------------------------------
CREATE TABLE rooms (
  id INT AUTO_INCREMENT PRIMARY KEY,
  room_number VARCHAR(10) NOT NULL UNIQUE,
  room_type_id INT NOT NULL,
  floor INT DEFAULT 1,
  status ENUM('available','maintenance','inactive') DEFAULT 'available',
  image_url VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (room_type_id) REFERENCES room_types(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO rooms (room_number, room_type_id, floor, status) VALUES
('101',1,1,'available'),('102',1,1,'available'),('103',1,1,'available'),
('201',2,2,'available'),('202',2,2,'available'),('203',2,2,'available'),
('301',3,3,'available'),('302',3,3,'available'),
('401',4,4,'available'),('402',4,4,'available');

-- ------------------------------------------------------------
-- Bookings
-- ------------------------------------------------------------
CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_code VARCHAR(20) NOT NULL UNIQUE,
  user_id INT NOT NULL,
  room_id INT NOT NULL,
  check_in DATE NOT NULL,
  check_out DATE NOT NULL,
  guests INT NOT NULL DEFAULT 1,
  nights INT NOT NULL,
  total_price DECIMAL(10,2) NOT NULL,
  status ENUM('pending','confirmed','checked_in','checked_out','cancelled') DEFAULT 'pending',
  special_requests TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Payments
-- ------------------------------------------------------------
CREATE TABLE payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_id INT NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  method ENUM('card','cash','bank_transfer') DEFAULT 'card',
  status ENUM('pending','paid','refunded') DEFAULT 'pending',
  paid_at TIMESTAMP NULL DEFAULT NULL,
  FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Contact / enquiry messages from the public site
-- ------------------------------------------------------------
CREATE TABLE contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  subject VARCHAR(150),
  message TEXT NOT NULL,
  is_read TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Guest reviews
-- ------------------------------------------------------------
CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
  comment TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- Seeded admin login: admin@aurelia.com / Admin@123
-- Please log in once and change this password, or generate a new
-- hash yourself with:
--   php -r "echo password_hash('yourNewPassword', PASSWORD_DEFAULT);"
-- then: UPDATE admins SET password = '<hash>' WHERE email='admin@aurelia.com';
-- ============================================================
