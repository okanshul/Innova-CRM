<p align="center">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" width="80" height="80"><path fill="rgb(117, 0, 255)" d="M296.5 69.2C311.4 62.3 328.6 62.3 343.5 69.2L562.1 170.2C570.6 174.1 576 182.6 576 192C576 201.4 570.6 209.9 562.1 213.8L343.5 314.8C328.6 321.7 311.4 321.7 296.5 314.8L77.9 213.8C69.4 209.8 64 201.3 64 192C64 182.7 69.4 174.1 77.9 170.2L296.5 69.2zM112.1 282.4L276.4 358.3C304.1 371.1 336 371.1 363.7 358.3L528 282.4L562.1 298.2C570.6 302.1 576 310.6 576 320C576 329.4 570.6 337.9 562.1 341.8L343.5 442.8C328.6 449.7 311.4 449.7 296.5 442.8L77.9 341.8C69.4 337.8 64 329.3 64 320C64 310.7 69.4 302.1 77.9 298.2L112 282.4zM77.9 426.2L112 410.4L276.3 486.3C304 499.1 335.9 499.1 363.6 486.3L527.9 410.4L562 426.2C570.5 430.1 575.9 438.6 575.9 448C575.9 457.4 570.5 465.9 562 469.8L343.4 570.8C328.5 577.7 311.3 577.7 296.4 570.8L77.9 469.8C69.4 465.8 64 457.3 64 448C64 438.7 69.4 430.1 77.9 426.2z"/></svg>
</p>

<h1 align="center">InnovaCRM</h1>
<p align="center"><strong>Modern, Enterprise-Grade Customer Relationship Management System</strong></p>

<p align="center">
  <img src="https://img.shields.io/badge/Innova-CRM-6366F1?style=for-the-badge&logo=layers&logoColor=white" alt="InnovaCRM">
  <img src="https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap 5.3">
  <img src="https://img.shields.io/badge/Vite-8.x-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
  <img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License">
</p>

InnovaCRM is a powerful, modern, enterprise-grade Customer Relationship Management (CRM) platform built on **Laravel 13**, **Bootstrap 5.3**, and **Spatie Permissions**. Designed for flexibility, performance, and seamless user experience across desktop and mobile devices.

---

## Key Features

- 👥 **Staff & Team Management**
  - Complete CRUD functionality for staff members with status tracking (Active/Inactive), position titles, and department tagging.
  - Search, filter by department/status, pagination, bulk deletion, and CSV export capabilities.
  
- 🛡️ **Granular Permission Matrix**
  - Flexible Role-Based Access Control (RBAC) powered by **Spatie Laravel Permission**.
  - Direct user-level permission overrides for fine-grained access control across modules (`Staff`, `Contacts`, `Deals`, `Pipeline`, `Reports`, `Tasks`, `Settings`).
  - Interactive permissions modal and form matrix with "Select All" and per-action controls (View, Create, Edit, Delete).

- 🎨 **Modern Responsive UI/UX**
  - Split-screen branded authentication interface with password visibility toggle and responsive card layouts.
  - Offcanvas collapsible navigation sidebar with plan upgrade CTA and user profile menus.
  - Responsive mobile bottom navigation bar (`<768px`) for effortless mobile access.
  - Unified Blade Component architecture (`x-breadcrumb`, `x-page-header`, `x-modal`, `x-button.*`, `x-form.*`, `x-badge.*`).

- 📊 **Dashboard & Insights**
  - Interactive pipeline widgets, deal statistics, contacts breakdown, and metrics visualization powered by **Chart.js**.

---

## Tech Stack

| Domain | Technology |
| :--- | :--- |
| **Backend** | PHP 8.3+, Laravel 13, Laravel Sanctum, Spatie Laravel Permission |
| **Frontend** | Laravel Blade Components, Bootstrap 5.3, SCSS (Custom Theme), Vite 8 |
| **Icons & UI** | Font Awesome 6, Bootstrap Icons, SweetAlert2 |
| **Data & Charts** | Chart.js, Vanilla JS (AJAX & App Utilities) |
| **Testing** | Pest PHP, PHPUnit |

---

## System Requirements

- **PHP**: `^8.3`
- **Composer**: `^2.x`
- **Node.js**: `^18.x` or `^20.x` & **npm**: `^9.x`
- **Database**: MySQL `^8.0` / PostgreSQL / SQLite

---

## Quick Start & Installation

### 1. Clone the Repository
```bash
git clone https://github.com/okanshul/Innova-CRM.git crm
cd crm
```

### 2. Install PHP & Node Dependencies
```bash
composer install
npm install
```

### 3. Environment Configuration
Copy `.env.example` to `.env` and generate your application key:
```bash
cp .env.example .env
php artisan key:generate
```

Configure your database connection inside `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_crm
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations & Seed Data
```bash
php artisan migrate --seed
```

### 5. Launch the Development Server
You can launch the server, queue listener, and Vite compiler concurrently using:
```bash
composer run dev
```

Or run them individually:
```bash
php artisan serve
npm run dev
```

Visit the application at `http://127.0.0.1:8000`.

---

## Project Structure

```
crm/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Staff, Auth, Dashboard, and API Controllers
│   │   └── Middleware/        # Auth and Permission Guard Middleware
│   ├── Models/                # User, Staff, Deal, Contact Models
│   └── View/Components/       # Custom Blade Component Classes
├── database/
│   ├── migrations/            # Database Schema Migrations
│   └── seeders/               # Roles, Permissions, and Default User Seeders
├── public/
│   ├── js/                    # Application Utilities (app-utils.js, staff.js)
├── resources/
│   ├── js/                    # Core JS & Dashboard Scripts
│   ├── scss/                  # SCSS Theme Definitions & Utility Classes
│   └── views/                 # Blade Views
│       ├── auth/              # Login & Password Views
│       ├── components/        # Reusable Blade UI Components (modal, form, button, header)
│       ├── layouts/           # Main Layout Wrapper (app.blade.php)
│       ├── partials/          # Header, Sidebar, Footer, Mobile Navigation
│       └── staff/             # Staff Index, Create, Edit, Show Views & Partials
├── routes/
│   ├── web.php                # Application Web Routes
│   └── api.php                # CRM API Endpoint Routes
└── tests/                     # Pest & Feature Tests
```

---

## Useful Commands

```bash
# Clear Application & View Cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Run Test Suite
composer test
# or
php artisan test

# Build Production Assets
npm run build
```

---

## License

This project is open-source software licensed under the [MIT License](LICENSE).
