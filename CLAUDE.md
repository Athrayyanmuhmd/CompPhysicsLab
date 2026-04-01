# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Start development (server + queue + logs + vite concurrently)
composer dev

# Start server only
php artisan serve

# Run tests
composer test
# or
php artisan test

# Run a single test
php artisan test --filter=TestClassName

# Lint (Laravel Pint)
./vendor/bin/pint

# Database
php artisan migrate
php artisan db:seed
php artisan migrate:fresh --seed

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan cache:clear
```

## Architecture Overview

This is a **Laravel 12** lab management system for a university physics laboratory. PHP 8.2+, MySQL, Tailwind CSS (no build step — CDN-based), vanilla JS.

### Route Structure & Middleware

Routes are in `routes/web.php` with two main groups:

- **Public** — no auth: home, articles, facilities, equipment browsing, loan/visit request forms, and public tracking pages (`/equipment/track/{id}`, `/visit/track/{id}`)
- **Admin** (`/admin/*`) — `AdminMiddleware`: dashboard, CRUD for staff, equipment, visits, loans, articles, facilities, vision/mission
- **Super Admin** (`/admin/users`, `/admin/system`) — `SuperAdminMiddleware`: user account management

Middleware: `app/Http/Middleware/AdminMiddleware.php` checks for `admin` or `super_admin` role; `SuperAdminMiddleware` checks for `super_admin` only.

### Key Domain Models

| Model | Table | Purpose |
|-------|-------|---------|
| `Alat` | `alat` | Equipment inventory with stock tracking |
| `KategoriAlat` | `kategori_alat` | Equipment categories |
| `Peminjaman` | `peminjaman` | Equipment loan records |
| `PeminjamanItem` | `peminjaman_item` | Line items per loan |
| `Kunjungan` | `kunjungan` | Lab visit bookings (UUID PKs) |
| `ScheduleAvailability` | `schedule_availability` | Bookable time slots |
| `Facility` | `facility` | Lab facilities |
| `Artikel` | `artikel` | Blog/news articles |
| `Gambar` | `gambar` | Gallery images (linked to facilities) |
| `BiodataPengurus` | `biodata_pengurus` | Staff profiles |

### Equipment Stock System (`Alat` model)

The `Alat` model has a sophisticated stock management system. Key columns: `stok` (total), `jumlah_tersedia` (available), `jumlah_dipinjam` (borrowed), `jumlah_rusak` (damaged). The model includes:
- Scopes: `available()`, `borrowed()`, `maintenance()`, `unavailable()`
- Methods: `canBeBorrowed($qty)`, `validateStockConsistency()`, `returnItemPartial()`, `writeOffDamaged()`

### Status Workflows

**Equipment Loans (`Peminjaman`):** `PENDING → APPROVED → ACTIVE → COMPLETED / CANCELLED`

**Lab Visits (`Kunjungan`):** `PENDING → PROCESSING → COMPLETED / CANCELLED`

`Kunjungan` uses UUID primary keys and supports document attachments (`dokumen_surat`) that are auto-deleted when the record is removed.

### Frontend

No npm/Vite build step. Tailwind CSS loaded from CDN in layouts. Views are in `resources/views/` organized as:
- `layouts/app.blade.php` — main layout
- `components/` — reusable page sections (navbar, footer, about, articles)
- `admin/` — admin dashboard views
- `services/` — service-specific views (equipment-tracking, visit-scheduling, visit-tracking)

Images are stored under `public/images/` (staff, equipment, gallery) and `storage/equipment/` for uploaded equipment images.

### Controllers

**Public:** `HomeController`, `FacilitiesController`, `ArticleController`, `EquipmentLoanController`, `VisitSchedulingController`, `TestingServicesController`, `LoginController`

**Admin (`app/Http/Controllers/Admin/`):** `AdminController` (dashboard), `AdminEquipmentController`, `AdminPeminjamanController`, `AdminVisitController`, `AdminScheduleController`, `AdminStaffController`, `AdminArticleController`, `AdminFacilityController`, `AdminVisiMisiController`, `AdminUserController` (super admin only)

## Environment Setup

```bash
cp .env.example .env
php artisan key:generate
# Configure DB_DATABASE=basic_physics_lab, DB_USERNAME, DB_PASSWORD in .env
php artisan migrate
php artisan db:seed   # optional
php artisan serve
```

Session, queue, and cache are all database-driven (`SESSION_DRIVER=database`, `QUEUE_CONNECTION=database`, `CACHE_STORE=database`).
