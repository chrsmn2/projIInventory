<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\IncomingItemController;
use App\Http\Controllers\Admin\OutgoingItemController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\RequesterController;
use App\Http\Controllers\Supervisor\DashboardController;
use App\Http\Controllers\Supervisor\LoanApprovalController;
use App\Http\Controllers\Supervisor\StockController;
use App\Http\Controllers\Supervisor\LoanMonitorController;
use App\Http\Controllers\Supervisor\ReportController as SupervisorReportController;

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
Route::middleware('auth')->get('/dashboard', function () {
    $user = auth()->user();

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

        // Dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Master Data
        Route::resource('categories', CategoryController::class);
        Route::resource('items', ItemController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('requesters', RequesterController::class);

        // Transactions
        Route::resource('incoming', IncomingItemController::class)->except(['show']);
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
        Route::get('reports/loan', [ReportController::class, 'loan'])
            ->name('reports.loan');
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
