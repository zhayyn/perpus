#!/bin/bash
# =============================================================
# Script Instalasi Otomatis - Aplikasi Perpustakaan
# Jalankan sekali setelah docker build selesai
# =============================================================

set -e  # Stop jika ada error

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

echo -e "${CYAN}========================================${NC}"
echo -e "${CYAN}  📚 Setup Aplikasi Perpustakaan        ${NC}"
echo -e "${CYAN}========================================${NC}"
echo ""

# Step 1: Pastikan container database sudah ready
echo -e "${YELLOW}[1/8]${NC} Menunggu database MySQL siap..."
docker compose up -d db redis
sleep 10

# Step 2: Install Laravel 11 ke folder src
echo -e "${YELLOW}[2/8]${NC} Install Laravel 11..."
docker compose run --rm -u root app composer create-project laravel/laravel . --prefer-dist
echo -e "${GREEN}✅ Laravel berhasil diinstall${NC}"

# Step 3: Copy .env
echo -e "${YELLOW}[3/8]${NC} Setup environment file..."
cp /var/www/html/.env.example /var/www/html/.env || true
docker compose run --rm app php artisan key:generate

# Step 4: Install packages utama
echo -e "${YELLOW}[4/8]${NC} Install Filament v3 & Livewire..."
docker compose run --rm app composer require filament/filament:"^3.2" --no-interaction
echo -e "${GREEN}✅ Filament v3 berhasil diinstall${NC}"

# Step 5: Install package tambahan
echo -e "${YELLOW}[5/8]${NC} Install packages tambahan..."
docker compose run --rm app composer require \
    spatie/laravel-permission \
    spatie/laravel-translatable \
    maatwebsite/excel \
    barryvdh/laravel-dompdf \
    simplesoftwareio/simple-qrcode \
    --no-interaction
echo -e "${GREEN}✅ Packages tambahan berhasil diinstall${NC}"

# Step 6: Publish & setup Filament
echo -e "${YELLOW}[6/8]${NC} Setup Filament admin panel..."
docker compose run --rm app php artisan filament:install --panels --no-interaction

# Step 7: Update .env sesuai Docker
echo -e "${YELLOW}[7/8]${NC} Update konfigurasi environment..."
docker compose run --rm app sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/' .env
docker compose run --rm app sed -i 's/DB_DATABASE=laravel/DB_DATABASE=perpustakaan/' .env
docker compose run --rm app sed -i 's/DB_USERNAME=root/DB_USERNAME=perpustakaan_user/' .env
docker compose run --rm app sed -i 's/DB_PASSWORD=/DB_PASSWORD=perpustakaan_secret/' .env
docker compose run --rm app sed -i 's/CACHE_DRIVER=file/CACHE_DRIVER=redis/' .env
docker compose run --rm app sed -i 's/QUEUE_CONNECTION=sync/QUEUE_CONNECTION=redis/' .env
docker compose run --rm app sed -i 's/SESSION_DRIVER=file/SESSION_DRIVER=redis/' .env
docker compose run --rm app sed -i 's/REDIS_HOST=127.0.0.1/REDIS_HOST=redis/' .env
docker compose run --rm app sed -i 's/MAIL_HOST=mailhog/MAIL_HOST=mailpit/' .env
docker compose run --rm app sed -i 's/MAIL_PORT=1025/MAIL_PORT=1025/' .env

# Step 8: Jalankan semua container
echo -e "${YELLOW}[8/8]${NC} Menjalankan semua container..."
docker compose up -d

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  ✅ INSTALASI SELESAI!                 ${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "  🌐 Aplikasi  : ${CYAN}http://localhost:8080${NC}"
echo -e "  🔧 phpMyAdmin: ${CYAN}http://localhost:8081${NC}"
echo -e "  📧 Mailpit   : ${CYAN}http://localhost:8025${NC}"
echo ""
