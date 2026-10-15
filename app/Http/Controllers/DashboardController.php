<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan Ringkasan & Widget Agregat Sistem Inventori (P4 ke Laravel)
     */
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $totalStock = Product::sum('stock');

        // Agregat Total Nilai Valuasi Aset Inventori (stock * price)
        $totalValuation = Product::selectRaw('SUM(stock * price) as total_val')->value('total_val') ?? 0;

        // Produk dengan Stok Menipis / Kritis (stock <= minimum_stock)
        $lowStockCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock')
            ->take(5)
            ->get();

        // Mutasi Stok Terbaru
        $recentMovements = StockMovement::with(['product', 'user'])
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'totalStock',
            'totalValuation',
            'lowStockCount',
            'lowStockProducts',
            'recentMovements'
        ));
    }
}
