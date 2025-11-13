# RSPemerintah - Sistem Manajemen Rumah Sakit Pemerintah

Sistem manajemen rumah sakit berbasis web yang dibangun dengan Laravel 12 dan Livewire 3 untuk mengelola registrasi pasien, triage, dan informasi pasien.

## 🚀 Fitur

- **Registrasi Pasien** - Pendaftaran pasien baru dengan informasi lengkap
- **Manajemen Pasien** - Daftar, detail, dan edit informasi pasien
- **Sistem Triage** - Klasifikasi pasien berdasarkan ESI Level dan prioritas
- **QR Wristband Scanner** - Scan QR code pada gelang pasien untuk akses cepat
- **Dashboard** - Overview sistem dan statistik

## 🛠️ Teknologi

- **Laravel 12** - PHP Framework
- **Livewire 3** - Full-stack framework untuk Laravel
- **Tailwind CSS 4** - Utility-first CSS framework
- **SQLite** - Database (dapat diganti dengan MySQL/PostgreSQL)

## 📥 Cara Mengambil Data dari GitHub

### Prasyarat

Pastikan Anda sudah menginstall:
- **PHP** >= 8.2
- **Composer** - Dependency manager untuk PHP
- **Node.js** dan **npm** - Untuk asset management
- **Git** - Version control system

### Langkah-langkah Instalasi

#### 1. Clone Repository dari GitHub

Buka terminal/command prompt dan jalankan perintah berikut:

```bash
git clone https://github.com/Andrew2509/RSPemerintah.git
```

Atau jika Anda ingin clone ke direktori tertentu:

```bash
git clone https://github.com/Andrew2509/RSPemerintah.git nama-folder
```

#### 2. Masuk ke Direktori Proyek

```bash
cd RSPemerintah
```

#### 3. Install Dependencies PHP

```bash
composer install
```

#### 4. Install Dependencies JavaScript

```bash
npm install
```

#### 5. Setup Environment File

Salin file `.env.example` menjadi `.env`:

**Windows:**
```bash
copy .env.example .env
```

**Linux/Mac:**
```bash
cp .env.example .env
```

#### 6. Generate Application Key

```bash
php artisan key:generate
```

#### 7. Setup Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=sqlite
# atau untuk MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=rs_pemerintah
# DB_USERNAME=root
# DB_PASSWORD=
```

Jika menggunakan SQLite, pastikan file `database/database.sqlite` sudah ada. Jika belum, buat dengan:

**Windows:**
```bash
type nul > database/database.sqlite
```

**Linux/Mac:**
```bash
touch database/database.sqlite
```

#### 8. Jalankan Migration

```bash
php artisan migrate
```

#### 9. Build Assets

```bash
npm run build
```

Atau untuk development dengan hot reload:

```bash
npm run dev
```

#### 10. Jalankan Server Development

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

### Update dari GitHub (Jika Sudah Ada Lokal)

Jika Anda sudah pernah clone sebelumnya dan ingin mengambil update terbaru:

```bash
# Masuk ke direktori proyek
cd RSPemerintah

# Ambil perubahan terbaru dari GitHub
git pull origin main

# Install dependencies baru (jika ada)
composer install
npm install

# Jalankan migration baru (jika ada)
php artisan migrate

# Rebuild assets
npm run build
```

### Menggunakan Composer Scripts

Proyek ini sudah dilengkapi dengan script otomatis untuk setup:

```bash
composer setup
```

Script ini akan otomatis:
- Install composer dependencies
- Copy `.env.example` ke `.env` (jika belum ada)
- Generate application key
- Run migrations
- Install npm dependencies
- Build assets

## 📝 Struktur Proyek

```
RSPemerintah/
├── app/
│   ├── Enums/          # Enum untuk kategori, tipe layanan, triage
│   ├── Http/
│   │   └── Controllers/
│   ├── Livewire/       # Komponen Livewire
│   └── Models/         # Model Eloquent
├── database/
│   ├── migrations/     # Database migrations
│   └── seeders/       # Database seeders
├── public/
│   └── css/           # File CSS custom
├── resources/
│   ├── css/
│   ├── js/
│   └── views/         # Blade templates
└── routes/
    └── web.php        # Web routes
```

## 🔧 Development

Untuk development dengan hot reload dan monitoring:

```bash
composer dev
```

Ini akan menjalankan:
- Laravel development server
- Queue worker
- Log viewer (Pail)
- Vite dev server

## 📄 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👤 Author

**Andrew2509**

- GitHub: [@Andrew2509](https://github.com/Andrew2509)
- Repository: [RSPemerintah](https://github.com/Andrew2509/RSPemerintah)

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the [issues page](https://github.com/Andrew2509/RSPemerintah/issues).

---

**Catatan:** Pastikan semua prasyarat sudah terinstall sebelum menjalankan aplikasi. Jika mengalami masalah, pastikan versi PHP >= 8.2 dan semua dependencies sudah terinstall dengan benar.
