<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('products')->orderBy('name')->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:150'],
            'contact_person' => ['nullable', 'string', 'max:100'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'email'          => ['nullable', 'email', 'max:100'],
            'address'        => ['nullable', 'string', 'max:500'],
        ]);

        $supplier = Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', "Supplier '{$supplier->name}' berhasil ditambahkan!");
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:150'],
            'contact_person' => ['nullable', 'string', 'max:100'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'email'          => ['nullable', 'email', 'max:100'],
            'address'        => ['nullable', 'string', 'max:500'],
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', "Data supplier '{$supplier->name}' berhasil diperbarui!");
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->products()->exists()) {
            return redirect()->route('suppliers.index')
                ->with('error', "Supplier '{$supplier->name}' tidak dapat dihapus karena masih terikat dengan produk aktif!");
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', "Supplier '{$supplier->name}' berhasil dihapus!");
    }
}
