<?php

use App\Livewire\CustomerManager;
use App\Livewire\InvoiceManager;
use Illuminate\Support\Facades\Route;
use App\Livewire\ItemManager;
use App\Livewire\SalesReport;
use App\Livewire\StockMovementManager;
use App\Livewire\SupplierManager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Rute untuk Manajemen Inventaris
Route::get('/items', ItemManager::class)->name('items');
Route::get('/items/stock-movements', StockMovementManager::class)->name('stock-movements');
Route::get('/items/suppliers', SupplierManager::class)->name('suppliers');

// Rute untuk Manajemen Pelanggan
Route::get('/customers', CustomerManager::class)->name('customers');

// Rute untuk Manajemen Penjualan
Route::get('/invoices', InvoiceManager::class)->name('invoices');

// Rute untuk Laporan Penjualan
Route::get('/sales-report', SalesReport::class)->name('sales-report');
