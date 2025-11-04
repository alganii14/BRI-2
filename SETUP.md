# Aktivitas Dashboard Pipeline - Setup & Testing

## Fitur yang Sudah Dibuat

### 1. Sistem Authentication
- ✅ Halaman Login dengan desain modern
- ✅ Proses Login & Logout
- ✅ Protected Routes (hanya bisa diakses setelah login)
- ✅ Session Management

### 2. Dashboard Layout
- ✅ Sidebar navigasi dengan menu:
  - Dashboard
  - Aktivitas
  - Nasabah
  - Uker
- ✅ Navbar dengan informasi user
- ✅ Tombol Logout
- ✅ Responsive design

### 3. Halaman Utama
- ✅ Dashboard (dengan statistik cards)
- ✅ Aktivitas (template siap dikembangkan)
- ✅ Nasabah (template siap dikembangkan)
- ✅ Uker (template siap dikembangkan)

## Cara Menjalankan Aplikasi

### 1. Pastikan XAMPP sudah berjalan
- Start Apache
- Start MySQL

### 2. Jalankan development server
```bash
cd c:\xampp\htdocs\aktivitas_pipeline
php artisan serve
```

### 3. Akses aplikasi di browser
```
http://localhost:8000
```

Anda akan otomatis diarahkan ke halaman login.

## Akun Testing

### Admin Account
- **Email**: admin@admin.com
- **Password**: password

### Test User Account
- **Email**: test@test.com
- **Password**: password

## Alur Aplikasi

1. **Login** → Masukkan email dan password
2. **Dashboard** → Akan muncul setelah login berhasil
3. **Navigasi** → Gunakan sidebar untuk berpindah menu:
   - Dashboard: Halaman utama dengan statistik
   - Aktivitas: Manajemen aktivitas pipeline
   - Nasabah: Manajemen data nasabah
   - Uker: Manajemen unit kerja
4. **Logout** → Klik tombol Logout di navbar

## Struktur File yang Dibuat

```
app/Http/Controllers/
├── AuthController.php          # Handle login & logout

resources/views/
├── auth/
│   └── login.blade.php         # Halaman login
├── layouts/
│   └── app.blade.php           # Layout utama dengan sidebar
├── dashboard/
│   └── index.blade.php         # Halaman dashboard
├── aktivitas/
│   └── index.blade.php         # Halaman aktivitas
├── nasabah/
│   └── index.blade.php         # Halaman nasabah
└── uker/
    └── index.blade.php         # Halaman unit kerja

routes/
└── web.php                     # Routing aplikasi

database/seeders/
└── DatabaseSeeder.php          # Seeder untuk user testing
```

## Pengembangan Selanjutnya

Halaman-halaman berikut sudah siap untuk dikembangkan:
1. **Aktivitas**: CRUD aktivitas pipeline
2. **Nasabah**: CRUD data nasabah
3. **Uker**: CRUD unit kerja
4. **Dashboard**: Tambah chart dan statistik real-time

## Troubleshooting

### Database tidak ditemukan
```bash
php create_database.php
php artisan migrate:fresh --seed
```

### Session error
```bash
php artisan config:clear
php artisan cache:clear
```

### Route tidak ditemukan
```bash
php artisan route:clear
php artisan route:cache
```

## Teknologi yang Digunakan

- **Backend**: Laravel 9.x
- **Database**: MySQL
- **Frontend**: Blade Templates, CSS Custom
- **Authentication**: Laravel Built-in Auth
