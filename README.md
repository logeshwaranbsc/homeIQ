# HOMEIQ — Digital Smart Locks E-Commerce Platform

A production-oriented, responsive e-commerce web platform engineered exclusively for **HOMEIQ**, specializing in architectural digital door locks and smart door security hardware.

Built with **pure Core PHP 8.2+** using a clean modular MVC architecture, **file-based JSON persistence** (`storage/*.json`), and **Bootstrap 5** frontend styling.

---

## 1. Technology Architecture

- **Backend:** Core PHP 8.2+ (strictly no Laravel, React, Vue, or Node.js)
- **Data Persistence:** Server-side JSON files with file locking (`flock`) and atomic file replacements (`storage/*.json`)
- **Frontend:** HTML5, CSS3, Vanilla ES6 JavaScript, Bootstrap 5.3, Bootstrap Icons
- **Typography:** Manrope (headings) & Inter (body text)
- **Security:** CSRF token verification, `password_hash()` (Argon2ID/Bcrypt), session regeneration, rate limiting, role-based backend authorization, safe multi-part upload validation, path-traversal prevention

---

## 2. Project Directory Structure

```text
HomeiQ/
├── .env.example              # Configuration template
├── .htaccess                 # Apache rewrite to public/
├── index.php                 # Development router / CLI server gateway
├── README.md                 # Complete documentation & deployment guide
├── app/
│   ├── Core/
│   │   ├── App.php           # App initialization, autoloader, and base URL resolution
│   │   ├── Router.php        # Fast regex route dispatcher with middleware pipeline
│   │   ├── Request.php       # HTTP request abstraction, inputs, and sanitization
│   │   ├── Response.php      # Response handler, headers, and JSON/redirect helpers
│   │   ├── Session.php       # Session security, CSRF tokens, flash messages
│   │   └── View.php          # View and layout renderer
│   ├── Repositories/
│   │   ├── JsonRepository.php       # Base repository: atomic writes, flock, CRUD
│   │   ├── ProductRepository.php    # Search, multi-criteria filters, stock queries
│   │   ├── CategoryRepository.php   # Category queries and hierarchy
│   │   ├── OrderRepository.php      # Order queries, revenue calculation, status counts
│   │   ├── CustomerRepository.php   # Customer queries and email lookups
│   │   ├── AdminRepository.php      # Staff & administrator records
│   │   ├── BannerRepository.php     # Configurable hero carousel slides
│   │   ├── ReviewRepository.php     # Customer ratings & moderation status
│   │   ├── CouponRepository.php     # Discount codes & cart rules
│   │   ├── InventoryRepository.php  # Stock adjustment history and movement audit
│   │   ├── SettingsRepository.php   # Store contact, announcements, shipping thresholds
│   │   └── AuditLogRepository.php   # Administrative action logging
│   ├── Services/
│   │   ├── AuthService.php          # Customer/admin authentication & rate limits
│   │   ├── CartService.php          # Server-recalculated cart prices & coupons
│   │   ├── OrderService.php         # Atomic stock deduction & order processing
│   │   ├── PaymentService.php       # Payment gateway callbacks & verification
│   │   └── ReportService.php        # Sales figures, analytics, CSV exports
│   ├── Middleware/
│   │   ├── CsrfMiddleware.php       # CSRF protection on POST/PUT requests
│   │   ├── AuthMiddleware.php       # Customer portal protection
│   │   ├── AdminAuthMiddleware.php  # Admin portal protection
│   │   └── RoleMiddleware.php       # Role-based authorization
│   ├── Helpers/
│   │   ├── helpers.php              # Global formatting, escaping, URL helpers
│   │   └── FileUploader.php         # Safe file uploads & MIME verification
│   ├── Controllers/
│   │   ├── Storefront/              # Storefront customer-facing controllers
│   │   └── Admin/                   # Administration management controllers
│   └── routes.php                   # Complete route registry
├── config/
│   └── app.php                      # Application configuration parameters
├── storage/                         # JSON databases (Protected by .htaccess)
│   ├── products.json
│   ├── categories.json
│   ├── orders.json
│   ├── customers.json
│   ├── admins.json
│   ├── banners.json
│   ├── reviews.json
│   ├── coupons.json
│   ├── inventory_movements.json
│   ├── settings.json
│   └── audit_logs.json
├── public/
│   ├── index.php                    # Public Front Controller
│   ├── .htaccess                    # Rewrite rules and security headers
│   ├── sitemap.xml                  # Dynamic XML sitemap
│   ├── robots.txt                   # Crawler rules
│   ├── assets/
│   │   ├── css/ (storefront.css, admin.css)
│   │   ├── js/ (storefront.js, admin.js)
│   │   └── images/
│   └── uploads/ (products/, categories/, banners/)
├── views/
│   ├── layouts/ (storefront.php, admin.php, auth.php)
│   ├── components/ (header, footer, announcement_bar, product_card, breadcrumbs...)
│   ├── storefront/ (home, shop, categories, product_detail, cart, checkout, invoice...)
│   └── admin/ (dashboard, products, categories, banners, orders, inventory, reports...)
├── scripts/
│   ├── setup.php                    # Database initializer & seeder
│   ├── create_admin.php             # CLI tool to create/reset admin credentials
│   ├── backup.php                   # JSON database backup tool
│   └── generate_assets.php          # High-resolution vector lock graphics generator
└── tests/
    └── run_tests.php                # Automated business rule test suite
```

---

## 3. Quick Start & Setup

### Prerequisites
- PHP 8.2 or higher installed and accessible in your system `PATH`.
- PHP extensions enabled: `json`, `mbstring`, `fileinfo`, `openssl`.

### Step 1: Run Database Initializer & Seeder
Initialize the JSON storage files, seed all 8 digital lock categories, the flagship **HOMEIQ Black AI Face Recognition Smart Door Lock**, active hero banners, and the default Super Admin:

```bash
php scripts/setup.php
```

### Step 2: Start Development Server
You can launch the built-in PHP development server directly from the project root:

```bash
php -S localhost:8000
```
Or pointing directly to the `public/` webroot:
```bash
php -S localhost:8000 -t public
```

### Step 3: Access Storefront & Admin Portal
- **Storefront:** [http://localhost:8000](http://localhost:8000)
- **Admin Management Panel:** [http://localhost:8000/admin](http://localhost:8000/admin)

---

## 4. Default Credentials

### Administrator Account
- **URL:** `/admin/login`
- **Username:** `admin` (or `admin@homeiq.com`)
- **Password:** `Admin@HOMEIQ2026!`
- **Role:** Super Admin

To create additional staff accounts or reset passwords via command line:
```bash
php scripts/create_admin.php <username> <email> <password> [role]
# Example:
php scripts/create_admin.php operations ops@homeiq.com SecretPassword123! "Order Manager"
```

Available administrative roles:
- `Super Admin` (Full unrestricted system control)
- `Product Manager` (Product catalog & categories)
- `Order Manager` (Order fulfillment & courier tracking)
- `Inventory Manager` (Stock adjustments & warehouse logs)
- `Content Manager` (Hero banners, reviews & SEO)

---

## 5. Core Feature Matrix

### Storefront
- **Configurable Announcement Bar:** Editable from the admin panel (discounts, warranties, installation notices).
- **Hero Slider:** 4 configurable slides with custom headlines, subheadings, offer badges, and CTA links.
- **8 Dedicated Lock Categories:**
  1. AI Face Recognition Smart Locks
  2. Fingerprint Smart Locks
  3. Palm Vein Recognition Smart Locks
  4. Wi-Fi and App-Controlled Smart Locks
  5. PIN and RFID Digital Locks
  6. Video Door Smart Locks
  7. Digital Mortise Locks
  8. Smart Lock Accessories
- **Product Catalog & Filters:** Keyword search, category filtering, access method selector, connectivity type, price range, in-stock toggle, and sorting (Price, Newest, Rating).
- **Product Detail Page:** Interactive multi-view gallery, delivery PIN code checker, detailed technical specifications table, box contents checklist, customer review submission, and related products.
- **Cart & Server Recalculation:** Subtotal, discount calculations, promotional coupons (`WELCOME10`, `HOMEIQ500`), free shipping threshold logic (orders ≥ ₹5,000 get free express shipping).
- **Checkout & Order Processing:** Collects shipping and billing details, Cash on Delivery (COD) or Online Payment, stock validation, and invoice generation.
- **Order Tracking:** Public order lookup by Order ID + Email/Phone, displaying live shipment milestones and courier tracking links.
- **Customer Account Portal:** Order history, profile management, saved addresses, password changes, and wishlist.

### Admin Dashboard (`/admin`)
- **Real Analytics:** Metrics dynamically computed from JSON databases (Revenue, Orders, Products, Low Stock).
- **Product CRUD:** Add/edit smart locks, upload images, manage technical specifications, and toggle active status.
- **Category CRUD:** Add/edit categories, manage display orders, and upload icons. Prevents deletion if products remain assigned.
- **Banner Management:** Create, reorder, and activate/deactivate homepage hero carousel slides.
- **Order Fulfillment:** Search/filter orders, update fulfillment statuses (Pending &rarr; Confirmed &rarr; Processing &rarr; Shipped &rarr; Delivered &rarr; Cancelled), update payment status, and assign courier AWB numbers.
- **Automatic Stock Restoration:** Cancelling an order automatically returns inventory to stock and records an audit movement.
- **Inventory Control:** Manual stock adjustments with reasons (Restock, Return, Damaged) and complete movement audit history.
- **Coupon Manager:** Percentage or fixed discount codes with expiration dates and minimum order values.
- **Review Moderation:** Approve or reject customer reviews before public display.
- **Financial Reports & CSV Export:** Date range filtering (Today, 7 Days, 30 Days, 1 Year, All Time) with one-click CSV report download.
- **Dynamic SEO & Sitemap:** Generates compliant `sitemap.xml` and `robots.txt` reflecting active products and categories.

---

## 6. Running Tests

Run the automated test suite to verify all business rules, JSON persistence, and calculation logic:

```bash
php tests/run_tests.php
```

Tests cover:
- Atomic JSON writes and file locking (`flock`)
- Digital lock catalog & flagship specification integrity
- Cart pricing recalculation & free shipping threshold calculation
- Coupon validation, percentage discounting, and minimum cart checks
- Atomic inventory deduction during checkout
- Automatic stock restoration upon order cancellation
- Password hashing & admin rate limiting

---

## 7. Backups & Restoration

### Create Backup
Run the backup script to create a timestamped archive of all JSON storage files in `storage/backups/`:

```bash
php scripts/backup.php
```

### Restore Backup
To restore data from a previous backup snapshot:
1. Locate the backup folder in `storage/backups/backup_YYYYMMDD_HHMMSS/`.
2. Copy the `.json` files from that backup folder back into the `storage/` directory.

---

## 8. Deployment Guidelines

### Apache / cPanel / Shared Hosting
1. Point your domain's Document Root to the `public/` folder.
2. If the hosting provider does not permit setting the document root to a subfolder, keep files in the root; the included root `.htaccess` and `index.php` will safely route incoming requests into `public/` while protecting `storage/`.
3. Verify that `storage/` and `public/uploads/` have write permissions (`chmod 755` or `chmod 775`).
4. Ensure `storage/.htaccess` is present to block direct HTTP access to JSON files.

### Nginx Configuration
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/HomeiQ/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    # Deny access to hidden files and storage
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 9. License & Commercial Rights
Designed and engineered for **HOMEIQ Technologies Pvt Ltd**. All product designs, vector graphics, and source code are proprietary.

"# homeIQ" 
