<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('name')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $category = Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', "Kategori '{$category->name}' berhasil dibuat!");
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', "Kategori '{$category->name}' berhasil diperbarui!");
    }

    public function destroy(Category $category)
    {
        // Penegakan batasan Relasional (ON DELETE RESTRICT)
        if ($category->products()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', "Kategori '{$category->name}' tidak dapat dihapus karena masih memiliki relasi produk!");
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', "Kategori '{$category->name}' berhasil dihapus!");
    }
}
