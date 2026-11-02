# MASTER PROMPT — HOMEIQ DIGITAL SMART LOCKS E-COMMERCE WEBSITE

## 1. PROJECT OVERVIEW

Build a complete, production-oriented, responsive e-commerce website for **HOMEIQ**, a brand specialising exclusively in digital door locks and smart door security products.

The website must include a polished customer-facing storefront and a fully functional admin dashboard.

**Technology requirements:**
- Frontend: HTML5, CSS3, JavaScript and Bootstrap 5 where appropriate.
- Backend: Core PHP 8.2+ using a clean, modular architecture.
- Data storage: JSON files stored on the server instead of MySQL or another database.
- Product images and uploaded files: Local file storage inside designated folders.
- Authentication: PHP sessions with secure password hashing.
- Icons: Bootstrap Icons or Lucide icons.
- Typography: Use formal, premium, commercially appropriate fonts such as Manrope for headings and Inter for body content, or another carefully selected professional font pairing.
- Payment and shipping integrations must use configurable providers and environment-specific credentials.

Do not use Laravel, React, Vue, Node.js, or a database for this project. This must be a PHP-based application with file-based data storage.

## 2. BRAND IDENTITY AND DESIGN DIRECTION

Brand: HOMEIQ

Industry: Digital door locks and smart home security.

Brand positioning: Premium, reliable, intelligent, elegant and security-focused.

Design direction:
- Create a refined premium electronics and home-security shopping experience.
- Use charcoal black, warm white, muted grey, subtle metallic tones and a restrained blue accent.
- Use generous whitespace, structured grids, clear typography and high-quality product photography.
- Create strong visual hierarchy through layout, typography, image composition and spacing.
- Use restrained shadows, fine borders and subtle transitions.
- Design the storefront to resemble an established premium security hardware brand.
- Design the admin panel to resemble a practical, professionally developed business management application.

STRICT DESIGN RESTRICTIONS:
- Do not use generic AI-generated dashboard layouts.
- Do not use excessive cards, floating glassmorphism panels, gradients everywhere, oversized rounded corners or decorative blobs.
- Do not use AI-related icons, robot icons, sparkle icons or artificial intelligence illustrations.
- Do not use playful, futuristic or excessively decorative fonts.
- Do not create every section using the same card pattern.
- Do not use random decorative elements that do not serve a business purpose.
- Do not use placeholder images, emoji icons or fake product photographs.
- Do not create an admin interface that looks like a generic AI SaaS template.
- Do not make the website look like a landing page for an AI company.
- Use consistent spacing, real product imagery and clear functional controls.

The final result should look intentionally designed by an experienced e-commerce UI/UX designer.

## 3. PRODUCT SCOPE — DIGITAL LOCKS ONLY

The website must exclusively sell digital door locks and their relevant accessories.

Allowed product categories:
1. AI Face Recognition Smart Locks
2. Fingerprint Smart Locks
3. Palm Vein Recognition Smart Locks
4. Wi-Fi and App-Controlled Smart Locks
5. PIN and RFID Digital Locks
6. Video Door Smart Locks
7. Digital Mortise Locks
8. Smart Lock Accessories

Do not add unrelated home appliances, furniture, electronics, general security services or unrelated products.

## 4. CUSTOMER-FACING WEBSITE

Create the following pages:

- Home
- Shop All Digital Locks
- Product Categories
- Product Details
- About HOMEIQ
- Wishlist
- Shopping Cart
- Checkout
- Login
- Customer Registration
- My Account
- My Orders
- Order Details and Tracking
- Contact Us
- Frequently Asked Questions
- Shipping and Delivery Policy
- Return and Refund Policy
- Privacy Policy
- Terms and Conditions
- Custom 404 Page

All navigation links, forms, filters, buttons and user actions must work.

## 5. HOME PAGE

### A. Announcement Bar

Display configurable announcements such as:
- Free Shipping on Eligible Orders
- Free Installation on Eligible Products
- Manufacturer Warranty
- Technical Support

All announcements must be editable from the admin panel.

### B. Navigation Header

Include:
- HOMEIQ logo and wordmark
- Home
- Shop Digital Locks
- Categories
- About Us
- Contact
- Search
- Account
- Wishlist
- Shopping Cart with live item count

Use a clean, responsive header with a functional mobile navigation menu.

### C. Hero Banner Slider

Create four independently configurable hero slides.

**Banner 1**

Headline: Advanced Security. Elegant Design.

Subheading: Give your home the smart upgrade it deserves.

Supporting text: Starts from ₹9,499

Benefits: Free Installation | Technical Support | Warranty Protection

CTA: SHOP SMART LOCKS

**Banner 2**

Headline: Built for Security. Designed for Peace.

Subheading: Discover premium smart locks at special prices.

Offer: UP TO 40% OFF

CTA: SHOP NOW

**Banner 3**

Headline: AI-Enabled Door Locks for Smarter Security

Subheading: Protect the people and things you love.

Benefits: AI-Powered Access | Biometric Access | Auto Lock Security

CTA: EXPLORE SMART LOCKS

**Banner 4**

Headline: Your Smart Home Upgrade Starts Here.

Subheading: Exclusive deals on HOMEIQ smart locks.

Offer: FREE SHIPPING

CTA: SHOP NOW

Banner requirements:
- Use premium digital lock and residential door photography.
- Use different image compositions for each slide.
- Maintain strong text contrast.
- Support desktop and mobile banner images.
- Include functional CTA links.
- Allow administrators to add, edit, reorder, activate and deactivate slides.
- Do not hardcode banner data in the templates.

### D. Shop by Category

Display categories relevant to digital locks only.

Each category should have a category image, title, short description and View Products link.

### E. Brand Introduction

Section heading: A NEW ERA OF SMART HOME SECURITY

Content:

HOMEIQ — where intelligent technology meets everyday living.

We’re reimagining how you secure your home with smart locks designed for greater convenience, control, and peace of mind.

Smarter Access. Safer Living.

Create an editorial layout featuring a premium door lock photograph, with text and imagery arranged in a balanced composition.

### F. Featured Products Slider

Create a functional horizontal product carousel inspired by premium electronics retail websites.

Each product item must display:
- Product image
- Product name
- Customer rating and review count, where available
- Current price
- Original MRP
- Discount percentage, calculated from actual prices
- Stock availability
- Add to Cart
- Wishlist control
- View Details

Product cards should have consistent image ratios, restrained borders and clear price hierarchy.

### G. Promotional Section

Create a distinct promotional layout for selected digital lock collections, using product photography, an offer headline and a Shop Now CTA.

All content and offer settings must be editable from the admin panel.

### H. Why Choose HOMEIQ?

Display real product benefits:
- Multiple Access Methods
- Smartphone-Based Access
- Automatic Locking
- User Access Management
- Emergency Power Support
- Manufacturer Warranty and Technical Support

Only show features applicable to the relevant products. Do not imply every lock supports every feature.

### I. Trust and Support Section

Include installation information, warranty information, delivery support and customer service.

Do not invent certifications, security guarantees, customer testimonials or ratings.

### J. Customer Reviews

Build a review section that displays verified customer reviews and ratings where available.

Allow the administrator to moderate reviews.

### K. Newsletter and Footer

Include newsletter registration, contact details, customer support links, shopping links, policies and social links.

Persist newsletter subscriptions in JSON files.

## 6. PRODUCT LISTING PAGE

Create a professional product catalogue for digital locks.

Required functionality:
- Search by product name, SKU and relevant product features.
- Filter by category, price range, access method, connectivity, colour, stock status and available features.
- Sort by price low to high, price high to low, newest, popularity and rating.
- Pagination.
- Responsive grid/list layout.
- Clear applied-filter indicators.
- Clear All Filters.
- Product count.
- Sale and out-of-stock labels.
- Add to Cart and Wishlist.
- Empty state when no matching products exist.

Filters must be generated from actual product data. Price sorting and discount calculations must be correct.

## 7. PRODUCT DETAIL PAGE

Use the following product as the initial catalogue item.

### Product Name

HOMEIQ Black AI Face Recognition Smart Door Lock

### Short Description

HOMEIQ Black AI Smart Door Lock — 9-in-1 keyless smart door lock with AI Face Recognition, Palm Vein, Fingerprint, PIN, RFID Card, Mechanical Key, Temporary Password and Tuya App Remote Access.

### Product Features

- AI Face Recognition
- Palm Vein and Fingerprint Unlock
- PIN and RFID Card Access
- Tuya App Remote Access
- Temporary Password Support
- HD Camera and Two-Way Video Calling
- Automatic Locking and Tamper Alerts
- Rechargeable Battery with Emergency USB Charging
- Premium Black Metal Finish

Suitable for homes, apartments, villas, offices and rental properties.

### Pricing

Current selling price: ₹19,999

MRP: ₹24,999

Displayed review information: 4.8 rating and 244 reviews, subject to confirmation or import of actual review data.

### Product Detail Layout

Include:
- Breadcrumb navigation
- Large product image gallery
- Thumbnail image selection
- Image zoom or enlarged preview
- Product name
- Rating and review count
- Current price, MRP and discount
- Stock availability
- Quantity selector
- Add to Cart
- Buy Now
- Wishlist
- Delivery PIN code checker, if delivery serviceability is configured
- Installation and warranty information
- Product highlights
- Full description
- Technical specifications
- What's included
- Customer reviews
- Frequently asked questions
- Related digital locks

### Full Product Description

Smart Security. Keyless Convenience. Complete Access Control.

The HOMEIQ Black Smart Door Lock is a premium biometric digital door lock designed for modern homes, apartments, villas, offices and rental properties.

With 9-in-1 keyless access, users can unlock their doors using AI Face Recognition, Palm Vein Recognition, Fingerprint, PIN Password, RFID Card, Mechanical Key, Temporary Password, Tuya App Remote Access or Combination Unlock.

Its biometric access methods, smart app control, automatic locking, access history and security alerts provide a convenient and connected door security experience.

### 9-in-1 Keyless Access

Choose the unlocking method that works best for you:
- AI Face Recognition
- Palm Vein Recognition
- Fingerprint Unlock
- PIN / Password
- RFID Card
- Mechanical Key
- Tuya Smart App
- Temporary Password
- Combination Unlock

### Advanced Security Features

- Semiconductor fingerprint sensor
- AI Face Recognition
- Palm Vein Recognition
- Anti-peeping password protection
- Automatic locking
- Tamper alerts
- Encrypted user management, if supported by the product
- Access history monitoring
- Low battery alerts
- Emergency USB charging

### Smart Tuya App Control

Where supported by the supplied product:
- Remote unlocking
- Temporary password creation
- Unlock history
- Family member and user management
- Lock notifications
- Smartphone access management

Clearly distinguish remote-access features from features that require Bluetooth, Wi-Fi, a gateway or other equipment.

### Fast and Reliable Unlocking

The supplied product specification states that the semiconductor fingerprint sensor supports unlocking in less than 0.5 seconds. Display this claim only after product specification verification.

The lock includes a rechargeable lithium battery, low-battery alerts and emergency USB charging support.

### Premium Design

Featuring a premium black metal finish, the HOMEIQ Smart Door Lock is designed to complement modern interiors while providing a practical security solution.

### What's Included

- Front and Back Lock
- Mortise
- Rechargeable Battery
- RFID Cards
- Mechanical Keys
- Installation Accessories
- User Manual

### Product Specifications

- Brand: HOMEIQ
- Lock Type: Mortise Lock
- Colour: Black
- Access Methods: 9-in-1 Keyless Access
- Fingerprint Sensor: Semiconductor
- App Support: Tuya Smart App
- Battery: Rechargeable Lithium Battery
- Emergency Charging: USB
- Dimensions: 40 × 17 × 9 cm — verify whether these are packaging or installed product dimensions
- Weight: 1.5 kg — verify whether this is product or package weight

Display specifications using a clear, readable table.

Do not fabricate missing dimensions, compatibility, certifications, warranty terms or product specifications. Allow administrators to edit all product data.

## 8. ABOUT US PAGE

Create a dedicated HOMEIQ brand page.

Include:
- Brand introduction
- Digital security product philosophy
- Product quality and usability
- Smart access technology
- Customer support
- Installation and after-sales support

Use professional editorial layouts and relevant product photography.

Do not invent company history, founding dates, awards or certifications.

## 9. SHOPPING CART

Implement a fully functional cart.

Requirements:
- Add products to cart.
- Increase and decrease quantities.
- Remove items.
- Display product images, names, SKUs and prices.
- Calculate subtotal and discounts.
- Calculate shipping according to configured rules.
- Display the final payable amount.
- Validate stock before updating quantities.
- Persist the cart in a PHP session and optionally associate it with a signed-in customer.
- Prevent negative quantities and prices supplied by the browser from being trusted.
- Include Continue Shopping and Proceed to Checkout.

Recalculate all prices on the server.

## 10. CHECKOUT AND ORDERS

Create a structured, mobile-friendly checkout process.

Collect:
- Customer name
- Email
- Mobile number
- Shipping address
- City
- State
- PIN code
- Billing address, if different
- Payment method
- Order notes, optional

Payment options:
- Cash on Delivery, if enabled
- Online payment through a configured payment gateway

Important:
- Never simulate a successful payment.
- Never store raw card numbers, CVVs or payment credentials.
- Verify payment through trusted gateway callbacks or server-to-server verification.
- Store only appropriate payment references and transaction status.
- Validate stock and order totals on the server.
- Generate a unique order ID.
- Save the order to JSON.
- Display an order confirmation page.
- Generate order invoices.
- Provide order history and order tracking.

Order statuses should include Pending, Confirmed, Processing, Shipped, Delivered, Cancelled and Returned, as applicable.

Do not mark orders as paid merely because a customer returns to a success URL.

## 11. CUSTOMER ACCOUNT

Implement:
- Customer registration
- Login and logout
- Secure password hashing
- Session-based authentication
- Profile management
- Saved addresses
- Order history
- Order details
- Order tracking
- Wishlist
- Password change
- Account validation

Customers must only be able to access their own orders, addresses and account information.

Add server-side validation, CSRF protection, session ID regeneration after login and secure logout.

## 12. ADMIN PANEL

Create a separate admin interface under `/admin`.

The design must be professional, practical and visually distinct from the storefront.

Use:
- Compact sidebar navigation
- Clear active menu indicators
- Neutral background
- Structured data tables
- Small, purposeful summary cards
- Functional search, filters and pagination
- Consistent form layouts
- Clear success and error messages
- Confirmation dialogs for destructive actions

Avoid oversized dashboard cards, excessive charts, unnecessary gradients, artificial illustrations and decorative widgets.

### Admin Login

Create a secure admin login page.

Requirements:
- No public admin registration.
- Seed the first administrator through a setup script or command-line PHP script.
- Hash passwords using `password_hash()`.
- Verify passwords using `password_verify()`.
- Regenerate session IDs after login.
- Rate-limit login attempts.
- Add CSRF protection.
- Prevent direct access to protected admin pages.
- Provide logout.

Never store a default plaintext administrator password in source code or public files.

### Admin Dashboard

Display real data from stored JSON files:
- Total Products
- Active Products
- Low Stock Products
- Out-of-Stock Products
- Total Orders
- Pending Orders
- Completed Orders
- Total Customers
- Total Revenue from eligible paid orders
- Recent Orders
- Best-Selling Digital Locks

Use simple charts only when they provide meaningful business information.

Do not show fabricated metrics or static sample numbers after setup.

### Product Management

Implement complete CRUD functionality:
- Add Product
- Edit Product
- View Product
- Delete Product
- Activate / Deactivate Product
- Product SKU
- Product Name
- Slug
- Category
- Short Description
- Full Description
- Regular Price
- Sale Price
- Stock Quantity
- Product Images
- Gallery Images
- Product Features
- Technical Specifications
- Product Variants
- Weight and Dimensions
- Warranty Information
- Installation Information
- Featured Product
- SEO Title
- SEO Description
- Publication Status

Validate every field on the server.

Generate unique slugs and SKUs where appropriate.

Support image upload, preview, replacement and deletion. Validate image extensions, MIME types, file size and dimensions. Generate safe filenames and store uploads outside executable PHP locations where practical.

### Category Management

Allow administrators to:
- Add categories
- Edit categories
- Delete categories
- Upload category images
- Edit descriptions and slugs
- Control visibility
- Set category display order

Prevent deleting categories that still contain products unless products are reassigned.

### Banner Management

Allow administrators to:
- Add, edit and delete banners
- Upload desktop and mobile images
- Edit headlines and descriptions
- Edit offer text
- Edit CTA labels and URLs
- Change display order
- Schedule publication if configured
- Activate or deactivate banners

### Order Management

Implement:
- Order listing
- Search by order ID, customer name and mobile number
- Filter by date and status
- View complete order details
- View customer and shipping information
- Update order status
- Add shipment and tracking information
- Record payment status
- Generate and print invoices
- View order history
- Process cancellations and returns
- Record refunds and refund references

Every significant status change must be saved and audited.

Do not claim a refund has been completed without a confirmed refund result.

### Customer Management

Allow authorized administrators to:
- View customers
- Search and filter customers
- View customer profiles
- View order history
- Update permitted customer details
- Deactivate accounts where necessary

Protect customer data and restrict access to authorized staff.

### Offer and Discount Management

Provide:
- Sale pricing
- Discount configuration
- Promotional banners
- Coupon codes, if enabled
- Start and end dates
- Minimum order value
- Usage limits
- Active and inactive states

Calculate discounts on the server and prevent invalid combinations.

### Inventory Management

Implement:
- Stock quantity
- Low-stock threshold
- Stock adjustment
- Stock movement history
- Out-of-stock indicators
- Inventory reports

Prevent overselling through safe stock validation and controlled file updates.

### Reviews Management

Allow administrators to:
- View submitted reviews
- Approve or reject reviews
- Manage reported reviews
- Display verified-purchase indicators only when verified against order records

Do not fabricate customer reviews.

### Reports and Analytics

Include:
- Sales report
- Order report
- Revenue report
- Customer report
- Product performance report
- Inventory report

Allow date-range filtering and CSV export.

Clearly define revenue calculations and exclude unpaid or cancelled orders where appropriate.

### SEO Management

Allow editing of:
- Meta title
- Meta description
- URL slug
- Canonical URL
- Open Graph metadata
- Product structured data
- Category metadata

Generate an XML sitemap and `robots.txt` using the active website data.

Escape all user-provided metadata when rendering it.

### Admin and Staff Management

Allow the administrator to:
- Create staff accounts
- Assign roles
- Edit user details
- Deactivate accounts
- Reset passwords securely
- Control access permissions

Suggested roles:
- Super Admin
- Product Manager
- Order Manager
- Inventory Manager
- Content Manager

Enforce role permissions in PHP backend authorization checks. Hiding menu items is not sufficient security.

## 13. FILE-BASED DATA STORAGE

Use JSON files as the primary data store. Do not use MySQL, SQLite or another database.

Create a structure similar to:

```text
storage/
    products.json
    categories.json
    orders.json
    customers.json
    admins.json
    banners.json
    reviews.json
    coupons.json
    inventory_movements.json
    newsletter_subscribers.json
    settings.json
    audit_logs.json

public/
    assets/
        css/
        js/
        images/
    uploads/
        products/
        categories/
        banners/

app/
    Core/
    Controllers/
    Services/
    Repositories/
    Middleware/
    Helpers/
    Validators/

config/
    app.php

admin/
    index.php
    login.php
    dashboard.php
    products/
    categories/
    orders/
    customers/
    banners/
    inventory/
    reviews/
    reports/
    settings/
    users/

scripts/
    setup.php
    create_admin.php
```

Storage and configuration files must not be publicly accessible through a browser.

Store JSON files outside the public web root whenever hosting permits. If the hosting structure makes this impractical, deny direct HTTP access through server configuration and verify that protection.

### File Storage Rules

Create a reusable JSON repository/service layer.

Each repository should support:
- Read all records
- Read a record by ID
- Create a record
- Update a record
- Delete a record
- Validate stored data
- Handle missing or malformed files
- Return useful errors
- Preserve valid records when an operation fails

Use JSON encoding with appropriate Unicode support.

Use file locking and atomic writes, such as writing to a temporary file and safely replacing the original file, to reduce corruption and concurrent-write issues.

Use unique IDs for all records.

For inventory and order updates, coordinate related file writes and handle failures explicitly. Do not assume multiple JSON file writes form a database transaction.

Add a backup mechanism before major data modifications and a documented restoration procedure.

Never write passwords in plaintext. Store only password hashes.

Do not expose raw storage paths, internal errors or sensitive records to visitors.

## 14. APPLICATION ARCHITECTURE

Use a modular, maintainable PHP architecture.

Recommended responsibilities:
- Controllers: Handle requests and responses.
- Services: Contain business rules.
- Repositories: Manage JSON file persistence.
- Validators: Validate input.
- Middleware: Handle authentication, authorization and CSRF checks.
- Helpers: Reusable formatting and utility functions.
- Views: Render the storefront and admin interface.

Separate public and admin layouts.

Use reusable components for:
- Header
- Footer
- Product cards
- Price display
- Breadcrumbs
- Pagination
- Forms
- Flash messages
- Admin sidebar
- Admin header
- Data tables
- Confirmation dialogs

Do not put the entire application into a single PHP file.

Use a front controller and clean URLs where supported. Provide working fallback routing for shared hosting environments.

## 15. SECURITY REQUIREMENTS

Implement:
- Server-side input validation
- Output escaping to prevent XSS
- CSRF tokens for state-changing forms
- Secure password hashing
- Session cookie security
- Session ID regeneration
- Authentication and authorization middleware
- Login rate limiting
- Upload validation
- Protection against path traversal
- Protection against unauthorized file downloads
- Safe JSON file operations
- Server-side pricing and stock verification
- Customer order ownership checks
- Admin audit logs
- Secure error handling
- Security headers
- Environment-based secrets
- Protection against direct execution of uploaded files

Use HTTPS in production.

Never store payment gateway secrets or private credentials in public directories.

JSON storage is appropriate for a modest, low-concurrency store. Design for small-scale deployment and document its limitations rather than pretending it offers full database transaction guarantees.

## 16. RESPONSIVE DESIGN

The website must work properly on:
- Desktop
- Laptop
- Tablet
- Mobile

Customer experience:
- Responsive navigation
- Touch-friendly buttons
- Mobile product gallery
- Responsive filters
- Compact cart layout
- Easy checkout forms
- Readable specifications
- No horizontal overflow

Admin experience:
- Collapsible sidebar
- Responsive tables
- Mobile-friendly form controls
- Clear action menus
- Readable order details
- Accessible confirmation dialogs

Test the layout at common desktop, tablet and mobile widths.

## 17. PERFORMANCE AND ACCESSIBILITY

Implement:
- Optimized product images
- Lazy loading for non-critical images
- Width and height attributes for images
- Sensible caching for static assets
- Pagination for large product and order lists
- Minimal JavaScript dependencies
- Accessible form labels
- Keyboard navigation
- Visible focus indicators
- Semantic HTML
- Appropriate colour contrast
- Useful empty and error states

Avoid unnecessary animations and large frontend libraries.

## 18. REQUIRED DELIVERABLES

Generate the complete working PHP project with:

1. All storefront pages
2. All admin pages
3. Reusable PHP components
4. JSON repository and service classes
5. Initial product and category data
6. Sample banners using editable text and replaceable image paths
7. Admin setup script
8. Customer authentication
9. Shopping cart
10. Checkout and order processing
11. Product and category CRUD
12. Banner and content management
13. Order and inventory management
14. Customer management
15. Reviews management
16. Reports and CSV exports
17. SEO management
18. Security implementation
19. Responsive CSS and JavaScript
20. README with setup and deployment instructions
21. Sample `.env.example` or equivalent configuration template
22. Backup and restore instructions
23. Basic tests for important business rules

## 19. IMPLEMENTATION WORKFLOW

Follow this order:

**Phase 1:** Inspect the existing project and identify its framework, current files, hosting assumptions and reusable components. Preserve useful existing functionality.

**Phase 2:** Establish the folder structure, configuration, routing, layouts and JSON storage services.

**Phase 3:** Implement authentication, authorization, validation, security middleware and admin setup.

**Phase 4:** Implement product and category management.

**Phase 5:** Build the storefront, home page, banners, product listing and product details.

**Phase 6:** Implement search, filters, wishlist, cart and customer accounts.

**Phase 7:** Implement checkout, order creation, payment verification hooks and order tracking.

**Phase 8:** Implement inventory, banners, customers, reviews, coupons, reports and SEO management.

**Phase 9:** Refine the visual design and responsive behaviour.

**Phase 10:** Test all major flows, fix errors and document deployment.

Do not stop after creating the homepage or admin dashboard.

## 20. ACCEPTANCE CRITERIA

The project is complete only when:

- The website runs using PHP without a database.
- All persistent business records are stored in JSON files.
- Product images are stored in local upload directories.
- Admin-created products appear automatically in the storefront.
- Admin changes to product prices and stock appear correctly.
- Banner changes appear on the home page.
- Search, filters, sorting and pagination work.
- Cart totals are calculated correctly.
- Checkout validates customer data and stock.
- Orders are persisted and visible in the admin panel.
- Customer accounts can only access their own data.
- Admin role permissions are enforced server-side.
- File writes handle errors and concurrent updates safely.
- Payment status is verified through the configured provider.
- The application has no broken links or non-functional primary buttons.
- The storefront and admin panel work on desktop and mobile.
- No unrelated products appear anywhere in the application.
- The design uses professional fonts, purposeful icons and premium product photography.
- No fake analytics, fake payment confirmations or fabricated reviews are shown.
- Setup and deployment instructions are complete.

Begin by inspecting the existing project files. Then implement the application incrementally, verifying each phase before moving on. Reuse existing working code where appropriate, and provide complete implementations rather than pseudocode, static mockups or isolated snippets.

The final product must feel like a real premium digital lock retail business, with a practical admin interface that an actual store owner can operate daily.
