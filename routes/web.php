<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ItemManager;
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

Route::get('/inventory', ItemManager::class)->name('inventory.index');
Route::get('/suppliers', SupplierManager::class)->name('suppliers.index');

// Optional: Routes for stock management
Route::get('/inventory/stock-in/{id}', [ItemManager::class, 'stockIn'])->name('inventory.stock-in');
Route::get('/inventory/stock-out/{id}', [ItemManager::class, 'stockOut'])->name('inventory.stock-out');
