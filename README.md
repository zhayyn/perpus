# 📚 Sistem Informasi Perpustakaan Digital

Selamat datang! Aplikasi ini adalah solusi modern untuk manajemen perpustakaan yang didesain agar sangat mudah dikelola, aman, dan cepat. 

Mengibaratkan perpustakaan fisik, aplikasi ini adalah **Pustakawan Digital** Anda. Ia bertugas mencatat siapa yang meminjam buku, kapan buku tersebut harus dikembalikan, menghitung denda secara presisi, hingga menyediakan katalog yang rapi—semuanya tanpa Anda harus menyentuh laci-laci kayu pendaftaran lagi.

---

## 🚀 Panduan Menjalankan Aplikasi (Deployment)

Aplikasi ini sudah dikemas rapi menggunakan **Docker**. Artinya, Anda tidak perlu repot-repot menginstal PHP, MySQL, atau Composer secara manual yang seringkali rawan *error*. Cukup pastikan **Docker Desktop** (atau OrbStack) sudah terinstal dan menyala di komputer/server Anda.

Ikuti 3 langkah sederhana berikut:

### 1. Unduh Proyek
Buka terminal/command prompt Anda dan ketik perintah berikut untuk mengunduh proyek:
```bash
git clone https://github.com/zhayyn/perpus.git perpustakaan
cd perpustakaan
```

### 2. Nyalakan Mesin
Di dalam proyek ini sudah kami siapkan sebuah *remote control* otomatis bernama `Makefile`. Anda cukup menekan satu "tombol" untuk menghidupkan seluruh sistem:
```bash
make up
```
*(Tunggu beberapa saat, sistem sedang otomatis merakit server, PHP, dan Database untuk Anda)*

### 3. Siapkan Laci Data
Setelah mesin menyala, kita perlu membuat laci-laci database dan mengisinya dengan pengaturan bawaan (seperti membuat akun admin dan logo default):
```bash
make fresh
```

Selesai! Anda siap beroperasi. 🎉

---

## 🌐 Cara Mengakses Aplikasi

Buka browser kesayangan Anda dan ketik alamat berikut:

- **Halaman Depan Publik:** [http://localhost:8080](http://localhost:8080)
- **Panel Admin / Pustakawan:** [http://localhost:8080/admin](http://localhost:8080/admin)
- **Manajemen Database (phpMyAdmin):** [http://localhost:8081](http://localhost:8081)

*(Catatan: Untuk masuk ke Panel Admin, gunakan akun email dan password default yang dihasilkan pada langkah `make fresh` tadi).*

---

*salam hangat from dbprakom*
