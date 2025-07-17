
# Instalasi Portfolio di XAMPP

## Langkah Instalasi:

1. **Extract files** ke folder `htdocs/portfolio/` di XAMPP

2. **Pastikan Apache berjalan** di XAMPP Control Panel

3. **Jalankan diagnostic tool** untuk cek sistem:
   ```
   http://localhost/portfolio/xampp_check.php
   ```

4. **Jalankan inisialisasi database**:
   ```
   http://localhost/portfolio/init_db.php
   ```

5. **Akses aplikasi**:
   - Portfolio: `http://localhost/portfolio/`
   - Admin Panel: `http://localhost/portfolio/admin/`

## Jika Data Tidak Muncul:

### Langkah 1: Cek Sistem
Buka: `http://localhost/portfolio/xampp_check.php`
- Pastikan SQLite extension aktif
- Cek file database ada dan bisa diakses

### Langkah 2: Cek Database
Buka: `http://localhost/portfolio/init_db.php`
- Lihat apakah ada error message
- Pastikan semua tabel terisi data

### Langkah 3: Cek API
Test manual API endpoint:
- `http://localhost/portfolio/api.php?action=profile`
- `http://localhost/portfolio/api.php?action=services`

### Langkah 4: Cek Browser Console
- Buka portfolio: `http://localhost/portfolio/`
- Tekan F12, lihat Console tab
- Cari error message

## File Penting:
- `admin/portfolio.db` - Database SQLite
- `api.php` - API endpoint  
- `admin/config.php` - Konfigurasi database
- `init_db.php` - Script inisialisasi
- `xampp_check.php` - Tool diagnostic

## Troubleshooting Common Issues:

1. **"Loading" terus menerus:**
   - Cek Console browser (F12) untuk error
   - Test API manual di browser
   - Pastikan Apache berjalan

2. **Database tidak terbuat:**
   - Cek permission folder admin/
   - Pastikan SQLite aktif di PHP
   - Jalankan xampp_check.php

3. **API tidak respond:**
   - Cek .htaccess sudah di-extract
   - Test langsung api.php di browser
   - Cek error_log Apache

4. **Admin panel tidak bisa akses:**
   - Pastikan folder admin/ ada
   - Cek file admin/index.php exist
   - Test database connection

## Quick Fix:
Jika masih bermasalah, coba sequence ini:
1. `http://localhost/portfolio/xampp_check.php`
2. `http://localhost/portfolio/init_db.php` 
3. `http://localhost/portfolio/api.php?action=profile`
4. `http://localhost/portfolio/`
