<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\FacilitiesController;
use App\Http\Controllers\EquipmentLoanController;
use App\Http\Controllers\TestingServicesController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\VisitSchedulingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\AdminVisitController;
use App\Http\Controllers\Admin\AdminScheduleController;
use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminVisiMisiController;
use App\Http\Controllers\Admin\AdminEquipmentController;
use App\Http\Controllers\Admin\AdminPeminjamanController;
use App\Http\Controllers\Admin\AdminFacilityController;

/*
|--------------------------------------------------------------------------
| Main Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/equipment', [HomeController::class, 'equipment'])->name('equipment');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

/*
|--------------------------------------------------------------------------
| Staff & Facilities Routes
|--------------------------------------------------------------------------
*/
Route::get('/staff', [StaffController::class, 'index'])->name('staff');
Route::get('/facilities', [FacilitiesController::class, 'index'])->name('facilities');

/*
|--------------------------------------------------------------------------
| Articles & Gallery Routes (Simple Names)
|--------------------------------------------------------------------------
*/
// Articles
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show')->where('id', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

/*
|--------------------------------------------------------------------------
| Services Routes
|--------------------------------------------------------------------------
*/
// Services - Equipment Loan (Updated with tracking)
Route::prefix('services/equipment-loan')->name('equipment.')->group(function () {
    Route::get('/', [EquipmentLoanController::class, 'index'])->name('loan');
    Route::get('/{id}', [EquipmentLoanController::class, 'show'])->name('detail');
    Route::post('/request', [EquipmentLoanController::class, 'requestLoan'])->name('request');
    Route::get('/availability/check', [EquipmentLoanController::class, 'checkAvailability'])->name('check-availability');
    Route::get('/history', [EquipmentLoanController::class, 'getLoanHistory'])->name('history');
});

// Equipment Tracking Routes (Public Access)
Route::prefix('equipment')->name('equipment.')->group(function () {
    // Track loan status (public access)
    Route::get('/track/{id}', [EquipmentLoanController::class, 'track'])
        ->name('track')
        ->where('id', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

    // Get loan data (for API or AJAX)
    Route::get('/loan-data/{id}', [EquipmentLoanController::class, 'getLoanData'])
        ->name('loan-data')
        ->where('id', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
});

// Services - Visit Scheduling (Updated with new features)
Route::prefix('services/visit-scheduling')->name('visit.')->group(function () {
    Route::get('/', [VisitSchedulingController::class, 'index'])->name('index');
    Route::post('/schedule', [VisitSchedulingController::class, 'store'])->name('store');
    Route::get('/available-slots', [VisitSchedulingController::class, 'getAvailableSlots'])->name('available-slots');
});

// Visit Tracking & Document Routes (Public Access)
Route::prefix('visit')->name('visit.')->group(function () {
    // Track visit status (public access)
    Route::get('/track/{id}', [VisitSchedulingController::class, 'track'])
        ->name('track')
        ->where('id', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

    // Download document (public access for visitor)
    Route::get('/document/{kunjungan}', [VisitSchedulingController::class, 'downloadDocument'])
        ->name('document')
        ->where('kunjungan', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');

    // Get schedule data (for API or AJAX)
    Route::get('/schedule-data', [VisitSchedulingController::class, 'getSchedule'])->name('schedule-data');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['web', 'auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Staff Management
    Route::resource('staff', AdminStaffController::class);
    Route::get('/staff/{staff}/edit', [AdminStaffController::class, 'edit'])->name('staff.edit');

    // Equipment Management
    Route::prefix('equipment')->name('equipment.')->group(function () {
        Route::get('/', [AdminEquipmentController::class, 'index'])->name('index');
        Route::get('/create', [AdminEquipmentController::class, 'create'])->name('create');
        Route::post('/', [AdminEquipmentController::class, 'store'])->name('store');
        Route::get('/{equipment}', [AdminEquipmentController::class, 'show'])->name('show');
        Route::get('/{equipment}/edit', [AdminEquipmentController::class, 'edit'])->name('edit');
        Route::put('/{equipment}', [AdminEquipmentController::class, 'update'])->name('update');
        Route::delete('/{equipment}', [AdminEquipmentController::class, 'destroy'])->name('destroy');
    });

    // Visit Management (Updated with new features)
    Route::prefix('visits')->name('visits.')->group(function () {
        Route::get('/', [AdminVisitController::class, 'index'])->name('index');
        Route::get('/{kunjungan}', [AdminVisitController::class, 'show'])->name('show');
        Route::get('/{kunjungan}/edit', [AdminVisitController::class, 'edit'])->name('edit');
        Route::get('/{kunjungan}/document', [AdminVisitController::class, 'downloadDocument'])->name('download-document');
        Route::put('/{kunjungan}', [AdminVisitController::class, 'update'])->name('update');
        Route::put('/{kunjungan}/status', [AdminVisitController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{kunjungan}', [AdminVisitController::class, 'destroy'])->name('destroy');

        // Calendar view for visits
        Route::get('/calendar/view', [AdminVisitController::class, 'calendar'])->name('calendar');
        Route::get('/calendar/data', [AdminVisitController::class, 'getCalendarData'])->name('calendar-data');

        // Export functionality
        Route::get('/export/data', [AdminVisitController::class, 'export'])->name('export');

        // Get available slots for admin (with exclude option for editing)
        Route::get('/slots/available', [AdminVisitController::class, 'getAvailableSlots'])->name('available-slots');
    });

    // Schedule Availability Management
    Route::prefix('schedule')->name('schedule.')->group(function () {
        Route::get('/', [AdminScheduleController::class, 'index'])->name('index');
        Route::get('/show', [AdminScheduleController::class, 'show'])->name('show');
        Route::post('/update-slot', [AdminScheduleController::class, 'updateSlot'])->name('update-slot');
        Route::post('/batch-update', [AdminScheduleController::class, 'batchUpdateSlots'])->name('batch-update');
        Route::post('/copy', [AdminScheduleController::class, 'copySchedule'])->name('copy');
        Route::get('/calendar/data', [AdminScheduleController::class, 'getCalendarData'])->name('calendar-data');
    });

    // Peminjaman Management (UPDATED - Fixed routes)
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', [AdminPeminjamanController::class, 'index'])->name('index');
        Route::get('/{peminjaman}', [AdminPeminjamanController::class, 'show'])->name('show');
        Route::put('/{peminjaman}/status', [AdminPeminjamanController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{peminjaman}', [AdminPeminjamanController::class, 'destroy'])->name('destroy');

        // Export dan Bulk Operations (FIXED)
        Route::get('/export', [AdminPeminjamanController::class, 'export'])->name('export');
        Route::post('/bulk-update', [AdminPeminjamanController::class, 'bulkUpdateStatus'])->name('bulk-update');

        // Dashboard data untuk statistik
        Route::get('/dashboard/data', [AdminPeminjamanController::class, 'getDashboardData'])->name('dashboard-data');
    });

    // Article Management
    Route::resource('articles', AdminArticleController::class);
    Route::delete('/articles/image/{gambar}', [AdminArticleController::class, 'destroyImage'])->name('articles.image.destroy');

    // Vision & Mission Management
    Route::prefix('visimisi')->name('visimisi.')->group(function () {
        Route::get('/', [AdminVisiMisiController::class, 'index'])->name('index');
        Route::put('/profil', [AdminVisiMisiController::class, 'updateProfil'])->name('update-profil');
        Route::post('/misi', [AdminVisiMisiController::class, 'storeMisi'])->name('store-misi');
        Route::put('/misi/{misi}', [AdminVisiMisiController::class, 'updateMisi'])->name('update-misi');
        Route::delete('/misi/{misi}', [AdminVisiMisiController::class, 'destroyMisi'])->name('destroy-misi');
    });

    // Facility Management
    Route::prefix('facilities')->name('facilities.')->group(function () {
        Route::get('/', [AdminFacilityController::class, 'index'])->name('index');
        Route::get('/edit', [AdminFacilityController::class, 'edit'])->name('edit');
        Route::put('/update', [AdminFacilityController::class, 'update'])->name('update');
    });

    /*
    |--------------------------------------------------------------------------
    | Super Admin Only Routes - User Management
    |--------------------------------------------------------------------------
    */
    Route::middleware([\App\Http\Middleware\SuperAdminMiddleware::class])->group(function () {
        // User Management (Admin users) - Full CRUD with additional features
        Route::prefix('users')->name('users.')->group(function () {
            // Main CRUD routes
            Route::get('/', [AdminUserController::class, 'index'])->name('index');
            Route::get('/create', [AdminUserController::class, 'create'])->name('create');
            Route::post('/', [AdminUserController::class, 'store'])->name('store');
            Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [AdminUserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [AdminUserController::class, 'update'])->name('update');
            Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');

            // Additional API/AJAX routes for enhanced functionality
            Route::get('/api/stats', [AdminUserController::class, 'getStats'])->name('api.stats');
            Route::get('/{user}/api/deletable', [AdminUserController::class, 'checkDeletable'])->name('api.deletable');
        });

        // Alternative resource route (if you prefer Laravel resource convention)
        // Route::resource('users', AdminUserController::class);

        // Additional Super Admin routes can be added here
        Route::prefix('system')->name('system.')->group(function () {
            // System settings, logs, etc. (for future development)
            // Route::get('/settings', [AdminSystemController::class, 'settings'])->name('settings');
            // Route::get('/logs', [AdminSystemController::class, 'logs'])->name('logs');
        });
    });
});
