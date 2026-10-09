# =============================================================
# MAKEFILE - Shortcut Commands Aplikasi Perpustakaan
# Analogi: Tombol-tombol pintar di panel kontrol gedung
# Usage: make <command>
# =============================================================

.PHONY: help up down build restart logs shell artisan composer migrate fresh seed

# Warna output
GREEN  := $(shell tput -Txterm setaf 2)
YELLOW := $(shell tput -Txterm setaf 3)
CYAN   := $(shell tput -Txterm setaf 6)
RESET  := $(shell tput -Txterm sgr0)

## ——— 🏗️  SETUP & INFRASTRUKTUR ——————————————————————————————
help: ## Tampilkan semua perintah yang tersedia
	@echo ''
	@echo '${CYAN}📚 Aplikasi Perpustakaan - Perintah Tersedia${RESET}'
	@echo ''
	@grep -E '^[a-zA-Z_0-9-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "${GREEN}  %-20s${RESET} %s\n", $$1, $$2}'
	@echo ''

build: ## Build semua Docker image
	docker compose build

up: ## Jalankan semua container (background)
	docker compose up -d
	@echo '${GREEN}✅ Aplikasi berjalan di http://localhost:8080${RESET}'
	@echo '${GREEN}📧 Mailpit: http://localhost:8025${RESET}'
	@echo '${GREEN}🔧 phpMyAdmin: http://localhost:8081${RESET}'

down: ## Hentikan semua container
	docker compose down

restart: ## Restart semua container
	docker compose restart

logs: ## Lihat log real-time semua service
	docker compose logs -f

logs-app: ## Lihat log container PHP
	docker compose logs -f app

## ——— 🛠️  DEVELOPMENT ——————————————————————————————————————
shell: ## Masuk ke shell container PHP (seperti SSH)
	docker compose exec app bash

artisan: ## Jalankan artisan command (contoh: make artisan cmd="migrate")
	docker compose exec app php artisan $(cmd)

composer: ## Jalankan composer command (contoh: make composer cmd="install")
	docker compose exec app composer $(cmd)

## ——— 🗄️  DATABASE ——————————————————————————————————————————
migrate: ## Jalankan migrasi database
	docker compose exec app php artisan migrate

fresh: ## Reset database & jalankan ulang semua migrasi
	docker compose exec app php artisan migrate:fresh --seed

seed: ## Isi database dengan data dummy
	docker compose exec app php artisan db:seed

## ——— ⚡  QUEUE & CACHE ——————————————————————————————————————
queue: ## Jalankan queue worker
	docker compose exec app php artisan queue:work --sleep=3 --tries=3

cache-clear: ## Bersihkan semua cache
	docker compose exec app php artisan optimize:clear

## ——— 🏁  INSTALL ——————————————————————————————————————————
install: ## Install Laravel baru (hanya dijalankan sekali)
	docker compose run --rm app composer create-project laravel/laravel . --prefer-dist
	docker compose run --rm app composer require filament/filament:"^3.2" livewire/livewire league/flysystem-local
	docker compose run --rm app composer require spatie/laravel-permission spatie/laravel-translatable
	docker compose run --rm app composer require maatwebsite/excel barryvdh/laravel-dompdf
	docker compose run --rm app composer require simplesoftwareio/simple-qrcode
	docker compose run --rm app php artisan filament:install --panels
	@echo '${GREEN}✅ Laravel + Filament berhasil diinstall!${RESET}'

filament-user: ## Buat user admin Filament
	docker compose exec app php artisan make:filament-user
