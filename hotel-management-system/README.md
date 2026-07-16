# Aurelia Hotel — Hotel Management System

A full hotel booking & management system: a public website where guests browse
rooms and book stays, a guest dashboard to manage bookings, and an admin
console to manage rooms, bookings, customers, and enquiries.

**Stack:** HTML / CSS / vanilla JS on the front end, PHP (PDO) + MySQL on the back end. No frameworks required.

## 1. Requirements

- PHP 7.4+ (8.x recommended) with the `pdo_mysql` extension
- MySQL 5.7+ / MariaDB 10.3+
- Any local server stack works: XAMPP, MAMP, Laragon, WAMP, or `php -S`

## 2. Setup

1. **Copy the project** into your server's web root, e.g. for XAMPP:
   `htdocs/hotel-management-system/`

2. **Create the database.** Import `sql/schema.sql`:
   - phpMyAdmin → Import → choose `sql/schema.sql`, or
   - command line:
     ```
     mysql -u root -p < sql/schema.sql
     ```
   This creates the `aurelia_hotel` database, all tables, 4 room types,
   10 sample rooms, and one seed admin account.

3. **Set your DB credentials** in `config/database.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'aurelia_hotel');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

4. **`BASE_URL` is auto-detected** in `config/config.php` — you don't need to
   edit it. It works whether the project sits at your server's root
   (`http://localhost/`) or in a subfolder
   (`http://localhost/hotel-management-system/`). If your host has an
   unusual setup and detection fails, you can force it manually by
   replacing the auto-detect block with a single line, e.g.:
   `define('BASE_URL', '/hotel-management-system/');`

5. **Run it.**
   - XAMPP/MAMP: visit `http://localhost/hotel-management-system/`
   - Or from the project folder: `php -S localhost:8000` and visit
     `http://localhost:8000/`

## 3. Logins

**Guest account:** register your own from the "Create account" page.

**Admin console:** `http://localhost/hotel-management-system/admin/login.php`
- Email: `admin@aurelia.com`
- Password: `Admin@123`

Change this password after your first login (edit it from a future account
settings page, or update it directly in the `admins` table with a new
bcrypt hash — see the note at the bottom of `sql/schema.sql`).

## 4. Folder structure

```
hotel-management-system/
├── admin/                  Admin console (staff-only, session-protected)
│   ├── login.php / logout.php
│   ├── index.php           KPIs + recent bookings
│   ├── rooms.php / room-save.php / room-delete.php   Room CRUD
│   ├── bookings.php / booking-update.php             Booking management
│   ├── customers.php       Guest list + lifetime spend
│   └── messages.php        Contact form inbox
├── dashboard/              Guest-facing account area (login required)
│   ├── index.php           Overview + recent bookings
│   ├── my-bookings.php     Full booking history + cancel
│   ├── cancel-booking.php  Cancel handler
│   └── profile.php         Edit name/phone/password
├── includes/
│   ├── header.php / footer.php        Public site chrome
│   ├── admin-sidebar.php              Admin nav
│   ├── dashboard-sidebar.php          Guest dashboard nav
│   └── functions.php                  Helpers (auth, formatting, availability check)
├── config/
│   ├── config.php          Session bootstrap, site constants
│   └── database.php        PDO connection
├── assets/
│   ├── css/style.css       Public + shared design system
│   ├── css/admin.css       Admin console styling
│   ├── js/main.js          Public site interactions
│   └── js/admin.js         Admin modals, table search, confirmations
├── sql/schema.sql          Full database schema + seed data
├── index.php               Homepage
├── rooms.php                Room search / listing
├── room-details.php         Single room type + live availability
├── about.php / contact.php
├── login.php / register.php / logout.php
├── book-room.php             Booking form
└── booking-confirmation.php  Booking receipt
```

## 5. How booking availability works

Each **room type** (Classic, Deluxe, Executive Suite, Grand Suite) has
several physical **rooms**. When a guest searches dates, the system checks
the `bookings` table for date overlaps per physical room
(`isRoomAvailable()` in `includes/functions.php`) so the same room can never
be double-booked. Cancelling a booking immediately frees the room again.

## 6. Notes & next steps

- Room photography currently links to placeholder Unsplash images —
  swap in your own photos via each room's `image_url` field.
- Payments are modeled as `pending` → `paid` / `refunded` in the `payments`
  table but no real payment gateway is wired in; add Stripe/PayPal in
  `book-room.php` and `admin/booking-update.php` when you're ready to take
  live payments.
- All forms use prepared statements (PDO) and `password_hash()` /
  `password_verify()` — do not remove these when extending the app.
