<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Dashboard;
use App\Livewire\Pos\OrderBoard;
use App\Livewire\Orders\OrderList;
use App\Livewire\Orders\OrderDetail;
use App\Livewire\Admin\MenuManagement;
use App\Livewire\Admin\CategoryManagement;
use App\Livewire\Admin\InventoryManagement;
use App\Livewire\Reports\SalesReport;
use App\Livewire\Kitchen\KitchenDisplay;
use App\Livewire\Admin\TableManagement;
use App\Livewire\Guest\GuestOrder;
use App\Http\Controllers\ReportExportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('login');
})->middleware('auth')->name('logout');

// Public guest ordering route (no auth required)
Route::get('/order/{token}', GuestOrder::class)->name('guest.order');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/pos', OrderBoard::class)->name('pos');
    Route::get('/kitchen', KitchenDisplay::class)->name('kitchen');
    Route::get('/orders', OrderList::class)->name('orders');
    Route::get('/orders/{order}', OrderDetail::class)->name('orders.detail');

    // Admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/menus', MenuManagement::class)->name('menus');
        Route::get('/categories', CategoryManagement::class)->name('categories');
        Route::get('/inventory', InventoryManagement::class)->name('inventory');
        Route::get('/reports', SalesReport::class)->name('reports');
        Route::get('/reports/export/pdf', [ReportExportController::class, 'pdf'])->name('reports.pdf');
        Route::get('/reports/export/excel', [ReportExportController::class, 'excel'])->name('reports.excel');
        Route::get('/users', Register::class)->name('users');
        Route::get('/tables', TableManagement::class)->name('tables');
    });
});
