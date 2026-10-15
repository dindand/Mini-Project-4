<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Pertemuan 5 - Laravel Fundamental)
| Alur: Request -> Route -> Controller -> Model -> Database -> Response
|--------------------------------------------------------------------------
*/

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Resource Routes sesuai materi Slide 12, 16, 18:
// Route::resource('products', ProductController::class);
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class)->except(['show']);
Route::resource('suppliers', SupplierController::class)->except(['show']);

// Transaksi Mutasi Stok (P4 Business Rules)
Route::resource('stock-movements', StockMovementController::class)->only(['index', 'create', 'store']);

// Contoh Response JSON sesuai Slide 20
Route::get('/api/products-json', function () {
    return response()->json(Product::with(['category', 'suppliers'])->get());
})->name('api.products.json');

// Demo helper routes untuk dokumentasi visual & screenshot validasi (Slide 25, 41, 48)
Route::get('/demo/validation-error', function () {
    return redirect()->route('products.create')
        ->withInput([
            'sku'   => 'PRD-ELEC-001',
            'price' => -5000,
        ])
        ->withErrors([
            'sku'   => 'Kode SKU PRD-ELEC-001 sudah digunakan oleh produk lain.',
            'name'  => 'Nama produk wajib diisi.',
            'price' => 'Harga satuan harus berupa angka minimal 0.',
        ]);
})->name('demo.validation.error');

Route::get('/demo/stock-error', function () {
    return redirect()->route('stock-movements.create', ['product_id' => 2, 'type' => 'OUT'])
        ->withInput([
            'product_id' => 2,
            'type'       => 'OUT',
            'quantity'   => 50,
            'notes'      => 'Pengiriman proyek cabang luar kota',
        ])
        ->withErrors([
            'quantity' => 'Jumlah pengeluaran (50) melebihi sisa stok yang tersedia (4) untuk produk \'Monitor Dell 24 Inch IPS Full HD\'! (Aturan Bisnis P4)'
        ]);
})->name('demo.stock.error');
