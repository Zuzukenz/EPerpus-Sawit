# E-Perpustakaan Setup & Installation Guide

## 📋 Daftar Isi
1. [Prerequisites](#prerequisites)
2. [Local Development Setup (MySQL)](#local-development-setup-mysql)
3. [Production Setup (PostgreSQL + Supabase)](#production-setup-postgresql--supabase)
4. [Database Migration & Seeding](#database-migration--seeding)
5. [Troubleshooting](#troubleshooting)

---

## Prerequisites

### Requirements
- PHP 8.2+
- Composer
- Node.js & npm
- Git
- **Untuk MySQL**: MySQL Server 5.7+ atau MariaDB
- **Untuk PostgreSQL**: PostgreSQL 12+ atau Supabase Account

### Verifikasi Installation

```bash
# Check PHP version
php --version

# Check Composer
composer --version

# Check Node.js
node --version
npm --version
```

---

## Local Development Setup (MySQL)

### 1. Clone Repository

```bash
git clone https://github.com/Zuzukenz/EPerpus-Sawit.git
cd EPerpus-Sawit
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration

```bash
# Copy .env.example to .env
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup (MySQL)

#### Option A: Using MySQL CLI

```bash
# Login ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE eperpus_sawit CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

#### Option B: Using GUI (PhpMyAdmin/MySQL Workbench)
1. Buat database baru dengan nama `eperpus_sawit`
2. Character Set: `utf8mb4`
3. Collation: `utf8mb4_unicode_ci`

### 5. Update .env File

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eperpus_sawit
DB_USERNAME=root
DB_PASSWORD=your_password
```

**Catatan:** Ganti `your_password` dengan password MySQL Anda (kosong jika tidak ada password)

### 6. Run Migrations & Seeders

```bash
# Run migrations
php artisan migrate

# Seed database dengan data sample
php artisan db:seed
```

### 7. Build Frontend Assets

```bash
# Development mode
npm run dev

# Atau production mode
npm run build
```

### 8. Start Development Server

```bash
# Start Laravel development server
php artisan serve

# Server akan running di http://localhost:8000
```

### 9. Login

```
Email: admin@eperpus.local
Password: password123
```

---

## Production Setup (PostgreSQL + Supabase)

### 1. Setup Supabase Account

1. Kunjungi https://supabase.com
2. Buat akun baru atau login
3. Buat project baru:
   - Pilih region terdekat
   - Simpan password database (penting!)
4. Tunggu project selesai di-setup

### 2. Database Connection Details

Di Supabase dashboard, cari informasi koneksi di **Settings > Database**:
- **Host**: `db.xxxxx.supabase.co`
- **Port**: `5432`
- **Database**: `postgres`
- **User**: `postgres`
- **Password**: (yang Anda set saat setup)

### 3. Install PHP PostgreSQL Driver

```bash
# Untuk Windows/Mac/Linux
composer require illuminate/database

# Pastikan PHP extension pdo_pgsql terinstall
php -m | grep pdo_pgsql
```

### 4. Update .env File untuk PostgreSQL

Edit `.env` dan ubah konfigurasi database:

```env
DB_CONNECTION=pgsql
DB_HOST=db.xxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your_secure_password
DB_SSLMODE=require
```

**Penting**: Ganti `xxxxx` dengan project ID Anda dan password dengan password yang benar.

### 5. Cara Mendapatkan Connection String Supabase

Ada 2 cara:

#### Method 1: Manual (Recommended)
```env
DB_CONNECTION=pgsql
DB_HOST=db.xxxxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your_password
DB_SSLMODE=require
```

#### Method 2: Menggunakan Connection URI
```env
DB_CONNECTION=pgsql
DB_URL=postgresql://postgres:your_password@db.xxxxx.supabase.co:5432/postgres
```

### 6. Run Migrations untuk PostgreSQL

```bash
# Test koneksi terlebih dahulu
php artisan tinker
>>> DB::connection()->getPdo();
// Jika berhasil, akan muncul PDO object

# Run migrations
php artisan migrate

# Seed data
php artisan db:seed
```

### 7. Verifikasi Database di Supabase

1. Buka Supabase Dashboard > SQL Editor
2. Jalankan query untuk verifikasi:

```sql
SELECT table_name FROM information_schema.tables 
WHERE table_schema = 'public';
```

Harus ada tabel: `users`, `categories`, `books`, `members`, `borrowings`, dll.

---

## Database Migration & Seeding

### Fresh Migration (⚠️ Hati-hati! Menghapus semua data)

```bash
# Fresh migration - menghapus semua data dan migration
php artisan migrate:fresh

# Fresh migration + seeding
php artisan migrate:fresh --seed
```

### Rollback

```bash
# Rollback 1 step
php artisan migrate:rollback

# Rollback semua
php artisan migrate:reset

# Rollback + re-run
php artisan migrate:refresh

# Rollback + re-run + seed
php artisan migrate:refresh --seed
```

### Custom Seeding

Jika ingin seed hanya table tertentu:

```bash
# Seed hanya categories
php artisan db:seed --class=DatabaseSeeder

# Seed books
php artisan db:seed --class=BookSeeder

# Seed members
php artisan db:seed --class=MemberSeeder
```

### Cek Status Migrations

```bash
# Lihat status semua migrations
php artisan migrate:status
```

---

## Deployment ke Server Production

### 1. Prepare Server

```bash
# SSH ke server
ssh user@your_server_ip

# Clone repository
git clone https://github.com/Zuzukenz/EPerpus-Sawit.git
cd EPerpus-Sawit
```

### 2. Install Dependencies

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database (Supabase)

```bash
nano .env
# Update DB credentials dengan Supabase connection details
```

### 5. Run Migrations

```bash
php artisan migrate --force
php artisan db:seed --force
```

### 6. Set Permissions

```bash
chown -R www-data:www-data .
chmod -R 775 storage bootstrap/cache
```

### 7. Configure Web Server (Nginx/Apache)

**Nginx:**
```nginx
server {
    listen 80;
    server_name your_domain.com;
    root /path/to/EPerpus-Sawit/public;

    index index.php index.html;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

---

## Development Workflow untuk Tim

### Developer Baru

1. Clone repo dan checkout development branch:
```bash
git clone https://github.com/Zuzukenz/EPerpus-Sawit.git
cd EPerpus-Sawit
git checkout feature/authentication-and-crud
```

2. Setup seperti di "Local Development Setup (MySQL)"

3. Create branch baru untuk feature:
```bash
git checkout -b feature/your-feature-name
```

### Membuat Feature Baru

```bash
# 1. Create branch
git checkout -b feature/your-feature-name

# 2. Buat migration jika perlu
php artisan make:migration your_migration_name

# 3. Implement feature
# ... code ...

# 4. Test
php artisan migrate:fresh --seed

# 5. Commit
git add .
git commit -m "feat: Add your feature"

# 6. Push ke GitHub
git push origin feature/your-feature-name

# 7. Create Pull Request
```

---

## Troubleshooting

### 1. Error: "SQLSTATE[HY000] [1045] Access denied for user"

**Solusi:**
- Pastikan MySQL service running
- Check DB credentials di .env
- Test koneksi manual:
```bash
mysql -h 127.0.0.1 -u root -p
```

### 2. Error: "Base table or view not found"

**Solusi:**
```bash
php artisan migrate:fresh --seed
```

### 3. Error: "Class not found" untuk Model

**Solusi:**
```bash
composer dump-autoload
php artisan cache:clear
```

### 4. Tailwind CSS tidak ter-compile

**Solusi:**
```bash
npm run dev
# Atau untuk production
npm run build
```

### 5. PostgreSQL Connection Error

**Solusi:**
- Verify host, port, database name di .env
- Check if password contains special characters (quote them)
- Test koneksi:
```bash
psql -h db.xxxxx.supabase.co -U postgres -d postgres
```

### 6. Permission Denied Error

**Solusi:**
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

---

## Database Schema Reference

### Tables
- **users**: Admin accounts
- **categories**: Kategori buku
- **books**: Data buku
- **members**: Data anggota perpustakaan
- **borrowings**: Riwayat peminjaman
- **borrowing_book**: Junction table (many-to-many)

### Key Fields

**Borrowings:**
- `status`: `borrowed` | `returned`
- `fine`: Denda keterlambatan (Rp 5000/hari)
- `return_date`: Tanggal jatuh tempo
- `actual_return_date`: Tanggal pengembalian aktual

---

## API Reference

### Authentication
- `POST /login` - Login
- `POST /logout` - Logout

### Books
- `GET /books` - List all books
- `GET /books/{id}` - Get book details
- `POST /books` - Create book
- `PUT /books/{id}` - Update book
- `DELETE /books/{id}` - Delete book

### Members
- `GET /members` - List all members
- `GET /members/{id}` - Get member details
- `POST /members` - Create member
- `PUT /members/{id}` - Update member
- `DELETE /members/{id}` - Delete member

### Borrowings
- `GET /borrowings` - List all borrowings
- `GET /borrowings/{id}` - Get borrowing details
- `POST /borrowings` - Create borrowing
- `GET /borrowings/{id}/return-form` - Return form
- `PUT /borrowings/{id}/return` - Process return
- `DELETE /borrowings/{id}` - Delete borrowing

### Dashboard
- `GET /dashboard` - Dashboard statistics

---

## Support & Contact

Untuk pertanyaan atau issues:
1. Buat Issue di GitHub
2. Contact tim developer
3. Check troubleshooting section di atas

---

**Last Updated**: August 2026
**Version**: 1.0.0
