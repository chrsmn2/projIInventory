// Admin
Route::prefix('admin')->group(function(){
    Route::get('/dashboard',[AdminController::class,'dashboard'])->name('admin.dashboard');
    Route::resource('incoming', IncomingItemController::class);
    Route::resource('outgoing', OutgoingItemController::class);
    Route::resource('loan', LoanController::class);
});

// Supervisor
Route::prefix('supervisor')->group(function(){
    Route::get('/dashboard',[SupervisorController::class,'dashboard'])->name('supervisor.dashboard');
    Route::resource('outgoing', OutgoingItemController::class);
    Route::resource('loan', LoanController::class);
    Route::get('items',[SupervisorController::class,'items'])->name('items.index');
});