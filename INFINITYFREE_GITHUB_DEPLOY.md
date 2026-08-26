# Automated Deployment of Laravel 13 to InfinityFree via GitHub Actions & FTP

This guide provides step-by-step instructions for automatically deploying this Laravel 13 application to **InfinityFree** hosting using **GitHub Actions** and **FTP**.

---

## 🏗️ Architecture & Server Structure

InfinityFree allows public website files directly inside the `/htdocs/` folder. To ensure high security and clean separation between public assets and private application code, the application is structured on the server as:

```text
htdocs/
├── index.php                 # Modified front controller pointing to laravel/
├── .htaccess                 # Main Apache URL rewrite rules
├── build/                    # Compiled Vite assets (CSS, JS, fonts)
├── favicon.ico               # Favicon icon
└── laravel/                  # Core Laravel Application (Protected)
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── database/
    ├── resources/
    ├── routes/
    ├── storage/
    ├── vendor/               # Production PHP dependencies (PHP 8.3)
    ├── .htaccess             # Restricts direct browser access to /laravel/
    ├── artisan
    ├── composer.json
    └── composer.lock
```

---

## 🔐 Step 1: Create GitHub Secrets

Never commit passwords or credentials to repository source code. Instead, set up GitHub Secrets:

1. Open your project on GitHub: `https://github.com/USERNAME/REPOSITORY`.
2. Go to **Settings** → **Secrets and variables** → **Actions**.
3. Click **New repository secret**.
4. Add the following secrets:

| Secret Name | Description / Value |
| :--- | :--- |
| `FTP_USERNAME` | Your InfinityFree FTP Username (e.g., `if0_38123456`) |
| `FTP_PASSWORD` | Your InfinityFree FTP Password (found in InfinityFree Client Area) |

---

## ⚙️ Step 2: Verify InfinityFree FTP Credentials

Log in to [InfinityFree Client Area](https://app.infinityfree.com):
- **FTP Server / Hostname**: `ftpupload.net`
- **FTP Username**: Listed under Account Details (starts with `if0_...`)
- **FTP Password**: Click "Show/Hide Password" under Account Details.
- **Server Directory**: `/htdocs/`

---

## 🗄️ Step 3: Setup Production Database & `.env` on InfinityFree

Because InfinityFree does not support SSH or Composer CLI commands:

### 1. Database Setup
1. Log in to **Control Panel (vPanel)** from InfinityFree.
2. Click **MySQL Databases** and create a new database.
3. Note the following details:
   - MySQL Host Name (e.g., `sql123.infinityfree.com`)
   - Database Name (e.g., `if0_38123456_crm`)
   - MySQL Username (e.g., `if0_38123456`)
   - MySQL Password (same as account password)
4. Open **phpMyAdmin**, select your database, and import your database schema (`.sql` file) or migrations export.

### 2. Server `.env` Configuration
Via InfinityFree Online File Manager or an FTP client (FileZilla):
1. Navigate to `/htdocs/laravel/` (or `/htdocs/`).
2. Create a file named `.env` containing your production configuration:

```env
APP_NAME="InnovaCRM"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_APP_KEY_HERE
APP_DEBUG=false
APP_URL=http://yourdomain.infinityfreeapp.com

LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=sql123.infinityfree.com
DB_PORT=3306
DB_DATABASE=if0_38123456_crm
DB_USERNAME=if0_38123456
DB_PASSWORD=your_mysql_password

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

CACHE_STORE=file
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
```

> ⚠️ **IMPORTANT**: The GitHub deployment workflow is configured with `exclude: | **/.env` so it will **NEVER** overwrite or delete your server `.env` file!

---

## 🚀 Step 4: Deploying via GitHub Actions

To trigger automatic deployment:

```bash
git add .
git commit -m "Configure GitHub Actions deployment for InfinityFree"
git push origin main
```

Or trigger manually:
1. Go to **Actions** tab in your GitHub repository.
2. Select **Deploy Laravel 13 to InfinityFree**.
3. Click **Run workflow** → **Run workflow**.

---

## 🛠️ Troubleshooting Guide

### 1. 500 Internal Server Error
- **Cause**: PHP version mismatch, missing `.env` key, or permission issues.
- **Fix**: Verify PHP version in InfinityFree vPanel is set to **PHP 8.3**. Ensure `APP_KEY` is present in your server `.env`.

### 2. Assets (CSS/JS) Not Loading (404)
- **Cause**: `public_path()` mismatch or incorrect base URL.
- **Fix**: The custom `index.php` explicitly sets `$app->usePublicPath(__DIR__);`. Assets are compiled into `htdocs/build/assets/` during the GitHub Actions build.

### 3. File Uploads / Avatar Images 404
- **Cause**: Missing symlink on shared hosting.
- **Fix**: A fallback route `GET /storage/{path}` is included in `routes/web.php`. It serves files directly from `storage/app/public/` securely without needing SSH or artisan symlinks.

### 4. FTP Deployment Timeout or Errors
- **Cause**: Slow connection or large vendor folder upload.
- **Fix**: The workflow runs `composer install --no-dev --optimize-autoloader` to keep the vendor package lightweight. Timeout is set to 60000ms (10 minutes).

---

## ⚡ Local Validation & Build Commands

Before pushing, verify local dependencies:

```bash
# 1. Validate Composer Configuration
composer validate

# 2. Check Platform Requirements (PHP 8.3 compatibility)
composer check-platform-reqs

# 3. Clean npm install & Vite build
npm ci
npm run build

# 4. Check Laravel Status
php artisan about
```
