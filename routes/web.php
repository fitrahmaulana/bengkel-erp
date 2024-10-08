<?php

use App\Livewire\CustomerManager;
use App\Livewire\Dashboard;
use App\Livewire\InvoiceManager;
use App\Livewire\Invoices\CreateInvoice;
use App\Livewire\Invoices\EditInvoice;
use App\Livewire\Invoices\ShowInvoice;
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

// Rute untuk Dashboard
Route::get('/', Dashboard::class)->name('dashboard');

// Rute untuk Manajemen Inventaris
Route::get('/items', ItemManager::class)->name('items');
Route::get('/items/stock-movements', StockMovementManager::class)->name('stock-movements');
Route::get('/items/suppliers', SupplierManager::class)->name('suppliers');

// Rute untuk Manajemen Pelanggan
Route::get('/customers', CustomerManager::class)->name('customers');

// Rute untuk Manajemen Penjualan
Route::get('/invoices', InvoiceManager::class)->name('invoices.index');
Route::get('/invoices/create', CreateInvoice::class)->name('invoices.create');
Route::get('/invoices/{invoiceId}', ShowInvoice::class)->name('invoices.show');
Route::get('/invoices/{invoice}/edit', EditInvoice::class)->name('invoices.edit');

// Rute untuk Laporan Penjualan
Route::get('/sales-report', SalesReport::class)->name('sales-report');
