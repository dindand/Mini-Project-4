<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockMovementController extends Controller
{
    /**
     * Tampilkan riwayat mutasi stok barang dengan filter & pagination
     */
    public function index(Request $request)
    {
        $query = StockMovement::with(['product.category', 'user'])
            ->when($request->type, function ($q, $type) {
                if (in_array($type, ['IN', 'OUT'])) {
                    $q->where('type', $type);
                }
            })
            ->when($request->product_id, function ($q, $productId) {
                $q->where('product_id', $productId);
            })
            ->when($request->search, function ($q, $search) {
                $q->whereHas('product', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            });

        $movements = $query->latest()->paginate(10)->withQueryString();
        $products = Product::orderBy('name')->get();

        return view('stock_movements.index', compact('movements', 'products'));
    }

    /**
     * Form pencatatan transaksi barang masuk / barang keluar
     */
    public function create(Request $request)
    {
        $products = Product::with('category')->orderBy('name')->get();
        $selectedProductId = $request->query('product_id');
        $defaultType = $request->query('type', 'IN');

        return view('stock_movements.create', compact('products', 'selectedProductId', 'defaultType'));
    }

    /**
     * Simpan transaksi mutasi stok dengan transaksi atomik ACID (DB::transaction & lockForUpdate)
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'type'       => ['required', 'in:IN,OUT'],
            'quantity'   => ['required', 'integer', 'min:1'],
            'notes'      => ['nullable', 'string', 'max:500'],
        ]);

        $productId = $request->product_id;
        $type = $request->type;
        $qty = (int) $request->quantity;
        $notes = $request->notes;
        $userId = Auth::id() ?? 1; // Default ke user admin bila tanpa login

        DB::transaction(function () use ($productId, $type, $qty, $notes, $userId) {
            // Pessimistic locking row untuk cegah race condition persis P4
            $product = Product::lockForUpdate()->findOrFail($productId);

            // Penegakan Aturan Bisnis P4: Barang Keluar tidak boleh melebihi sisa stok
            if ($type === 'OUT' && $qty > $product->stock) {
                throw ValidationException::withMessages([
                    'quantity' => "Jumlah pengeluaran ({$qty}) melebihi sisa stok yang tersedia ({$product->stock}) untuk produk '{$product->name}'!"
                ]);
            }

            // Update stok produk
            if ($type === 'IN') {
                $product->stock += $qty;
            } else {
                $product->stock -= $qty;
            }
            $product->save();

            // Catat log histori transaksi
            StockMovement::create([
                'product_id' => $product->id,
                'user_id'    => $userId,
                'type'       => $type,
                'quantity'   => $qty,
                'notes'      => $notes,
            ]);
        });

        $typeLabel = $type === 'IN' ? 'Barang Masuk (+)' : 'Barang Keluar (-)';

        return redirect()->route('stock-movements.index')
            ->with('success', "Transaksi {$typeLabel} sebanyak {$qty} unit berhasil dicatat dan stok telah diperbarui!");
    }
}
