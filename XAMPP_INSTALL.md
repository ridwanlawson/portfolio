
# Instalasi Portfolio di XAMPP

## Langkah Instalasi:

1. **Extract files** ke folder `htdocs/portfolio/` di XAMPP

2. **Jalankan inisialisasi database** dengan membuka:
   ```
   http://localhost/portfolio/init_db.php
   ```

3. **Akses aplikasi**:
   - Portfolio: `http://localhost/portfolio/`
   - Admin Panel: `http://localhost/portfolio/admin/`

## Jika Data Tidak Muncul:

1. Pastikan Apache sudah jalan di XAMPP
2. Cek permission folder (775) dan file database (666)
3. Jalankan `init_db.php` untuk cek status database
4. Pastikan PHP extension SQLite aktif di XAMPP

## File Penting:
- `admin/portfolio.db` - Database SQLite
- `api.php` - API endpoint
- `admin/config.php` - Konfigurasi database
- `init_db.php` - Script inisialisasi (jalankan sekali)

## Troubleshooting:
- Jika "loading" terus: cek Console di browser (F12) untuk error
- Jika admin tidak bisa akses: cek permission folder admin/
- Jika database kosong: jalankan init_db.php
