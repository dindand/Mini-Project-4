@extends('layouts.app')

@section('title', 'Katalog & Daftar Produk')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Daftar Produk</h2>
        <p class="text-muted mb-0">Kelola katalog produk, stok persediaan, kategori, dan supplier mitra.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('api.products.json') }}" target="_blank" class="btn btn-outline-secondary" title="Slide 20: Response JSON">
            <i class="bi bi-code-slash me-1"></i> API JSON
        </a>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
        </a>
    </div>
</div>

{{-- Filter, Search, dan Sorting Form (Slide 32, 38, 41) --}}
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('products.index') }}" class="row g-2 align-items-center">
            {{-- Search by Name / SKU --}}
            <div class="col-md-4 col-sm-6">
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama produk / SKU..." value="{{ request('search') }}">
                </div>
            </div>

            {{-- Filter Kategori --}}
            <div class="col-md-3 col-sm-6">
                <select name="category_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Status Stok --}}
            <div class="col-md-2 col-sm-6">
                <select name="stock_status" class="form-select">
                    <option value="">Semua Status Stok</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>⚠️ Stok Kritis</option>
                    <option value="safe" {{ request('stock_status') == 'safe' ? 'selected' : '' }}>✓ Stok Aman</option>
                </select>
            </div>

            {{-- Sorting --}}
            <div class="col-md-2 col-sm-6">
                <select name="sort_by" class="form-select">
                    <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                    <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nama Produk</option>
                    <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Harga</option>
                    <option value="stock" {{ request('sort_by') == 'stock' ? 'selected' : '' }}>Jumlah Stok</option>
                    <option value="sku" {{ request('sort_by') == 'sku' ? 'selected' : '' }}>SKU</option>
                </select>
            </div>

            {{-- Tombol Submit & Reset --}}
            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-dark w-100" title="Terapkan Filter">
                    <i class="bi bi-funnel-fill"></i>
                </button>
                @if(request()->hasAny(['search', 'category_id', 'stock_status', 'sort_by']))
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Tabel Data Produk (Slide 39) --}}
<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 50px;">No</th>
                        <th>Kode SKU</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga Satuan</th>
                        <th class="text-center">Sisa Stok</th>
                        <th>Supplier Terkait</th>
                        <th class="text-end pe-3" style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Blade Forelse Loop (Slide 24, 39) --}}
                    @forelse($products as $index => $product)
                        <tr>
                            <td class="ps-3 text-muted">
                                {{ $products->firstItem() + $index }}
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary-emphasis font-monospace px-2 py-1">
                                    {{ $product->sku }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('products.show', $product) }}" class="fw-semibold text-decoration-none text-dark d-block">
                                    {{ $product->name }}
                                </a>
                                @if($product->isLowStock())
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle small py-0 px-1">
                                        <i class="bi bi-exclamation-triangle-fill"></i> Under Min. Stock ({{ $product->minimum_stock }})
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                    {{ $product->category->name ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            <td class="fw-semibold text-dark">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <span class="badge rounded-pill fs-6 px-3 py-1 {{ $product->isLowStock() ? 'bg-danger' : 'bg-success' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>
                                @forelse($product->suppliers as $sup)
                                    <span class="badge bg-light text-dark border me-1">{{ $sup->name }}</span>
                                @empty
                                    <span class="text-muted small">-</span>
                                @endforelse
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-danger" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                {{-- Modal Konfirmasi Hapus Produk (Slide 13, 18, 31) --}}
                                <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $product->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered text-start">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-danger" id="deleteModalLabel{{ $product->id }}">
                                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus Produk
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus produk ini?</p>
                                                <div class="p-3 bg-light rounded-3 mb-2">
                                                    <strong>{{ $product->name }}</strong><br>
                                                    <small class="text-muted">SKU: {{ $product->sku }} | Stok: {{ $product->stock }}</small>
                                                </div>
                                                <p class="small text-muted mb-0">
                                                    <em>Catatan: Produk yang memiliki riwayat mutasi stok tidak dapat dihapus untuk menjaga integritas data.</em>
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Ya, Hapus Produk</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                <h5>Belum ada produk yang ditemukan.</h5>
                                <p class="mb-3">Silakan sesuaikan filter pencarian atau tambahkan data produk baru.</p>
                                <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Produk Sekarang
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination Footer (Slide 24, 38) --}}
    @if($products->hasPages())
        <div class="card-footer bg-white py-3 d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} total produk
            </small>
            <div>
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
