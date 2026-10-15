<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource (Slide 17, 32, 38).
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'suppliers'])
            ->when($request->search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($request->category_id, function ($q, $categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->when($request->stock_status === 'low', function ($q) {
                $q->whereColumn('stock', '<=', 'minimum_stock');
            })
            ->when($request->stock_status === 'safe', function ($q) {
                $q->whereColumn('stock', '>', 'minimum_stock');
            });

        // Sorting
        $allowedSort = ['name', 'price', 'stock', 'created_at', 'sku'];
        $sortBy = in_array($request->sort_by, $allowedSort) ? $request->sort_by : 'created_at';
        $sortOrder = strtolower($request->sort_order) === 'asc' ? 'asc' : 'desc';

        $products = $query->orderBy($sortBy, $sortOrder)
            ->paginate(8)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource (Slide 18).
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage (Slide 19).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'   => ['required', 'exists:categories,id'],
            'sku'           => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name'          => ['required', 'string', 'max:150'],
            'price'         => ['required', 'numeric', 'min:0'],
            'stock'         => ['nullable', 'integer', 'min:0'],
            'minimum_stock' => ['nullable', 'integer', 'min:0'],
            'suppliers'     => ['nullable', 'array'],
            'suppliers.*'   => ['exists:suppliers,id'],
        ]);

        $validated['stock'] = $validated['stock'] ?? 0;
        $validated['minimum_stock'] = $validated['minimum_stock'] ?? 5;

        $product = Product::create($validated);

        if (!empty($validated['suppliers'])) {
            $product->suppliers()->sync($validated['suppliers']);
        }

        return redirect()->route('products.index')
            ->with('success', "Produk '{$product->name}' ({$product->sku}) berhasil ditambahkan!");
    }

    /**
     * Display the specified resource (Slide 14, 18, 34).
     */
    public function show(Product $product)
    {
        $product->load(['category', 'suppliers', 'stockMovements.user']);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource (Slide 18).
     */
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage (Slide 18, 31).
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id'   => ['required', 'exists:categories,id'],
            'sku'           => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($product->id)],
            'name'          => ['required', 'string', 'max:150'],
            'price'         => ['required', 'numeric', 'min:0'],
            'stock'         => ['nullable', 'integer', 'min:0'],
            'minimum_stock' => ['nullable', 'integer', 'min:0'],
            'suppliers'     => ['nullable', 'array'],
            'suppliers.*'   => ['exists:suppliers,id'],
        ]);

        $product->update($validated);

        if ($request->has('suppliers')) {
            $product->suppliers()->sync($request->input('suppliers', []));
        }

        return redirect()->route('products.index')
            ->with('success', "Produk '{$product->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified resource from storage (Slide 18, 31).
     */
    public function destroy(Product $product)
    {
        // Business rule dari P4: Mencegah penghapusan produk berhistori transaksi
        if ($product->stockMovements()->exists()) {
            return redirect()->route('products.index')
                ->with('error', "Gagal menghapus! Produk '{$product->name}' memiliki riwayat mutasi stok (Integritas Data Relasional).");
        }

        $product->suppliers()->detach();
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', "Produk '{$product->name}' berhasil dihapus!");
    }
}
