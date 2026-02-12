<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DepartementController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\IncomingItemController;
use App\Http\Controllers\Admin\OutgoingItemController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\RequesterController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Supervisor\DashboardController;
use App\Http\Controllers\Supervisor\LoanApprovalController;
use App\Http\Controllers\Supervisor\StockController;
use App\Http\Controllers\Supervisor\LoanMonitorController;
use App\Http\Controllers\Supervisor\ReportController as SupervisorReportController;
use App\Http\Controllers\Supervisor\ProfileController as SupervisorProfileController;
use App\Http\Controllers\Admin\AjaxController;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Redirect After Login (Role Based)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->get('/dashboard', function (Request $request) {
    $user = $request->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'supervisor') {
        return redirect()->route('supervisor.dashboard');
    }

    abort(403, 'Role not recognized');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Master Data
        Route::resource('categories', CategoryController::class);
        Route::resource('units', UnitController::class);

        // Duplicate check digunakan oleh import preview
        Route::post('items/check-duplicate', [\App\Http\Controllers\Admin\ItemController::class, 'checkDuplicate'])
            ->name('items.check-duplicate');

        // Import routes
        Route::get('items/import', [\App\Http\Controllers\Admin\ItemController::class, 'showImport'])->name('items.import.show');
        Route::post('items/import', [\App\Http\Controllers\Admin\ItemController::class, 'import'])->name('items.import');

        // Resource routes
        Route::resource('items', ItemController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('units', UnitController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('departement', DepartementController::class);

        // API untuk SELECT2 AJAX (Item Search)
        Route::get('api/items/search', [ItemController::class, 'searchItems'])->name('api.items.search');
        Route::get('api/categories/search', [ItemController::class, 'searchCategories'])->name('api.categories.search');
        Route::get('api/units/search', [ItemController::class, 'searchUnits'])->name('api.units.search');
        Route::get('api/suppliers/search', [SupplierController::class, 'searchSuppliers'])->name('api.suppliers.search');
        Route::get('api/departements/search', [DepartementController::class, 'searchDepartements'])->name('api.departements.search');

        // Transactions
        Route::resource('incoming', IncomingItemController::class);
        Route::resource('outgoing', OutgoingItemController::class);
        Route::resource('loans', LoanController::class);

        // Loan Approval (Admin can approve/reject)
        Route::put('loans/{loan}/approve', [LoanController::class, 'approve'])
            ->name('loans.approve');
        Route::put('loans/{loan}/reject', [LoanController::class, 'reject'])
            ->name('loans.reject');

        // Reports
        Route::get('reports/stock', [ReportController::class, 'stock'])
            ->name('reports.stock');
        Route::get('reports/movement', [ReportController::class, 'movement'])
            ->name('reports.movement');
        Route::get('reports/loan', [ReportController::class, 'loan'])
            ->name('reports.loan');

        // Profile
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::patch('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.update-password');
        Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('profile.destroy');

        // Real-time name check routes for each resource
        Route::post('items/check-name', [AjaxController::class, 'checkName'])->name('items.check-name');
        Route::post('categories/check-name', [AjaxController::class, 'checkName'])->name('categories.check-name');
        Route::post('units/check-name', [AjaxController::class, 'checkName'])->name('units.check-name');
        Route::post('suppliers/check-name', [AjaxController::class, 'checkName'])->name('suppliers.check-name');
        Route::post('departement/check-name', [AjaxController::class, 'checkName'])->name('departement.check-name');

        // Debug Import
        Route::post('items/debug-import', [ItemController::class, 'debugImport'])->name('items.debug-import');
        Route::post('items/debug-file-type', [ItemController::class, 'debugFileType'])
            ->name('items.debug-file-type')
            ->middleware(['auth', 'role:admin']);
    });

/*
|--------------------------------------------------------------------------
| SUPERVISOR ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:supervisor'])
    ->prefix('supervisor')
    ->name('supervisor.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile', [SupervisorProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [SupervisorProfileController::class, 'update'])->name('profile.update');
        Route::patch('/profile/password', [SupervisorProfileController::class, 'updatePassword'])->name('profile.update-password');
        Route::delete('/profile', [SupervisorProfileController::class, 'destroy'])->name('profile.destroy');

        // Loan Approvals
        Route::get('loan-approvals', [LoanApprovalController::class, 'index'])->name('loan-approvals.index');
        Route::get('loan-approvals/{id}', [LoanApprovalController::class, 'show'])->name('loan-approvals.show');
        Route::post('loan-approvals/{id}/approve', [LoanApprovalController::class, 'approve'])->name('loan-approvals.approve');
        Route::post('loan-approvals/{id}/reject', [LoanApprovalController::class, 'reject'])->name('loan-approvals.reject');

        // Stock Monitoring
        Route::get('stock', [StockController::class, 'index'])->name('stock.index');
        Route::get('stock/low', [StockController::class, 'lowStock'])->name('stock.low');
        Route::get('loans/monitor', [LoanMonitorController::class, 'index'])->name('loan.monitor');

        // Reports
        Route::get('reports/loan', [SupervisorReportController::class, 'loanReport'])->name('reports.loan');
        Route::get('reports/stock', [SupervisorReportController::class, 'stockReport'])->name('reports.stock');
    });

/*
|--------------------------------------------------------------------------
| Profile Routes (All Roles)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
