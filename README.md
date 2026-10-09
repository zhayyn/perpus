# 📚 Aplikasi Perpustakaan Digital

Aplikasi manajemen perpustakaan berbasis web yang dibangun dengan:
- **Laravel 11** + **PHP 8.3**
- **Filament v3** (Admin Panel)
- **Livewire 3** (Komponen Interaktif)
- **MySQL 8.0** (Database)
- **Redis 7** (Cache & Queue)
- **Docker / OrbStack** (Environment)

---

## 🚀 Panduan Handover & Instalasi (Clone dari GitHub)

Jika kamu (atau temanmu) ingin menjalankan aplikasi ini di komputer/satker lain, syarat utamanya **hanyalah menginstal Docker Desktop / OrbStack**. Tidak perlu repot install PHP, Composer, atau MySQL secara manual!

Ikuti langkah instalasi bersih ini:

### 1. Clone Repository
```bash
git clone <URL_GITHUB_KAMU> perpustakaan
cd perpustakaan
```

### 2. Siapkan File Konfigurasi (Environment)
```bash
# Salin konfigurasi environment default
cp .env.example src/.env
```
*(Catatan: Buka file `src/.env` dan pastikan kredensial database sudah sesuai dengan yang ada di `docker-compose.yml`)*

### 3. Build & Jalankan Container
```bash
# Perintah sakti untuk menjalankan Docker dan menginstall seluruh package Laravel
make up
```

### 4. Setup Database Pertama Kali
Karena ini instalasi baru, database-nya masih kosong. Jalankan perintah ini untuk melakukan migrasi tabel, seeding pengaturan default, dan akun admin:
```bash
make fresh
```

*(Setelah selesai, kamu bisa login ke `http://localhost:8080/admin` dengan akun admin default yang dibuat dari proses seeding)*

---

## 📋 Daftar Perintah (Makefile Shortcuts)

Di aplikasi ini sudah disediakan `Makefile` agar kamu tidak perlu mengetik perintah docker/artisan yang panjang. Cukup ketik:

```bash
make help          # Lihat semua perintah
make up            # Jalankan semua container (otomatis install composer & npm jika belum)
make down          # Hentikan semua container
make shell         # Masuk ke shell PHP container
make artisan cmd="..." # Jalankan artisan command (Contoh: make artisan cmd="make:model Buku")
make migrate       # Jalankan migrasi database
make fresh         # Reset & seed ulang database
make logs          # Lihat log server secara real-time
```

## 🌐 URL Akses

| URL | Keterangan |
|-----|------------|
| http://localhost:8080 | Aplikasi Utama |
| http://localhost:8080/admin | Admin Panel (Filament) |
| http://localhost:8081 | phpMyAdmin (Manajemen Database UI) |
| http://localhost:8025 | Mailpit (Inboks Email Testing Lokal) |

## 📁 Struktur Proyek

```
perpustakaan/
├── docker/                 # Resep container (Nginx, PHP, MySQL)
├── src/                    # KODE SUMBER LARAVEL
│   ├── app/                # Logika utama (Model, Controller, Filament)
│   ├── routes/             # Routing aplikasi
│   ├── database/           # Migrasi & Seeders
│   └── ...                 
├── .agents/                # Aturan pengembangan agent AI (SenopaTEA)
├── docker-compose.yml      # Orkestrasi Docker
├── Makefile                # Shortcut commands
└── README.md               # Dokumentasi ini
```

## 💡 Keamanan (Git)
Aplikasi ini sudah dikonfigurasi untuk **tidak** mengirimkan file sensitif (seperti kredensial `.env` dan folder `vendor/` atau `node_modules/`) ke GitHub. Aman untuk diunggah ke repositori publik maupun privat.
