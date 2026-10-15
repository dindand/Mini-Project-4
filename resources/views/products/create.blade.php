@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="fw-bold mb-1">Tambah Produk Baru</h2>
                <p class="text-muted mb-0">Isi formulir di bawah ini untuk mendaftarkan barang baru ke sistem inventori.</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                {{-- Form Create Produk (Slide 19, 25) --}}
                <form method="POST" action="{{ route('products.store') }}">
                    @csrf

                    <div class="row g-3">
                        {{-- Kategori Produk (Relasi One-to-Many) --}}
                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-semibold">
                                Kategori Produk <span class="text-danger">*</span>
                            </label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- SKU Produk (Unique Identifier) --}}
                        <div class="col-md-6">
                            <label for="sku" class="form-label fw-semibold">
                                Kode SKU <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="sku" id="sku" class="form-control font-monospace @error('sku') is-invalid @enderror" placeholder="Contoh: PRD-ELEC-003" value="{{ old('sku') }}" required>
                            @error('sku')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Nama Produk --}}
                        <div class="col-12">
                            <label for="name" class="form-label fw-semibold">
                                Nama Produk <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Monitor LG 27 Inch UltraFine 4K" value="{{ old('name') }}" required>
                            @error('name')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Harga Satuan --}}
                        <div class="col-md-4">
                            <label for="price" class="form-label fw-semibold">
                                Harga Satuan (Rp) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="0.01" min="0" name="price" id="price" class="form-control @error('price') is-invalid @enderror" placeholder="0" value="{{ old('price') }}" required>
                            </div>
                            @error('price')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Stok Awal --}}
                        <div class="col-md-4">
                            <label for="stock" class="form-label fw-semibold">
                                Stok Awal (Unit)
                            </label>
                            <input type="number" min="0" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" placeholder="0" value="{{ old('stock', 0) }}">
                            @error('stock')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Batas Stok Minimum (Threshold Alert) --}}
                        <div class="col-md-4">
                            <label for="minimum_stock" class="form-label fw-semibold">
                                Minimum Stok (Alert)
                            </label>
                            <input type="number" min="0" name="minimum_stock" id="minimum_stock" class="form-control @error('minimum_stock') is-invalid @enderror" placeholder="5" value="{{ old('minimum_stock', 5) }}">
                            <small class="text-muted">Notifikasi jika stok &le; angka ini</small>
                            @error('minimum_stock')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Relasi Many-to-Many: Pemasok / Supplier --}}
                        <div class="col-12 mt-4">
                            <label class="form-label fw-semibold d-block">
                                Mitra Supplier (Relasi Many-to-Many)
                            </label>
                            <div class="row g-2 p-3 bg-light rounded-3 border">
                                @forelse($suppliers as $supplier)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="suppliers[]" value="{{ $supplier->id }}" id="supplier_{{ $supplier->id }}" {{ in_array($supplier->id, old('suppliers', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="supplier_{{ $supplier->id }}">
                                                {{ $supplier->name }}
                                                <small class="text-muted d-block">{{ $supplier->contact_person }} ({{ $supplier->phone }})</small>
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-muted small">
                                        Belum ada supplier terdaftar. Tambahkan supplier melalui menu Supplier terlebih dahulu.
                                    </div>
                                @endforelse
                            </div>
                            @error('suppliers')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
