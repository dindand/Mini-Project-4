@extends('layouts.app')

@section('title', 'Riwayat Mutasi Stok Barang')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Riwayat Mutasi Stok</h2>
        <p class="text-muted mb-0">Log transaksi barang masuk (IN) dan pengeluaran barang (OUT) secara transparan.</p>
    </div>
    <a href="{{ route('stock-movements.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Catat Transaksi Mutasi
    </a>
</div>

{{-- Filter Mutasi --}}
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('stock-movements.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4 col-sm-6">
                <div class="input-group">
                    <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama barang / SKU..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <select name="type" class="form-select">
                    <option value="">Semua Tipe Transaksi</option>
                    <option value="IN" {{ request('type') == 'IN' ? 'selected' : '' }}>🟢 Barang Masuk (IN)</option>
                    <option value="OUT" {{ request('type') == 'OUT' ? 'selected' : '' }}>🟠 Barang Keluar (OUT)</option>
                </select>
            </div>

            <div class="col-md-4 col-sm-6">
                <select name="product_id" class="form-select">
                    <option value="">Semua Produk</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}" {{ request('product_id') == $prod->id ? 'selected' : '' }}>
                            {{ $prod->sku }} - {{ $prod->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-dark w-100" title="Terapkan Filter">
                    <i class="bi bi-funnel-fill"></i>
                </button>
                @if(request()->hasAny(['search', 'type', 'product_id']))
                    <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 50px;">No</th>
                        <th>Waktu Transaksi</th>
                        <th>Jenis Mutasi</th>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th class="text-center">Jumlah</th>
                        <th>Petugas Logistik</th>
                        <th class="pe-3">Catatan / Referensi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $index => $mov)
                        <tr>
                            <td class="ps-3 text-muted">{{ $movements->firstItem() + $index }}</td>
                            <td>
                                <div>{{ $mov->created_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $mov->created_at->format('H:i:s') }}</small>
                            </td>
                            <td>
                                @if($mov->type === 'IN')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                        <i class="bi bi-arrow-down-left"></i> MASUK (IN)
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                                        <i class="bi bi-arrow-up-right"></i> KELUAR (OUT)
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('products.show', $mov->product) }}" class="fw-semibold text-decoration-none text-dark d-block">
                                    {{ $mov->product->name }}
                                </a>
                                <small class="text-muted font-monospace">{{ $mov->product->sku }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $mov->product->category->name ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center fw-bold fs-6 {{ $mov->type === 'IN' ? 'text-success' : 'text-danger' }}">
                                {{ $mov->type === 'IN' ? '+' : '-' }}{{ number_format($mov->quantity) }}
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $mov->user->name ?? 'Staff Gudang' }}</div>
                                <small class="text-muted">{{ $mov->user->role ?? 'staff' }}</small>
                            </td>
                            <td class="pe-3 text-muted">
                                {{ $mov->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-clock-history fs-1 d-block mb-2 text-secondary"></i>
                                <h5>Belum ada riwayat mutasi stok.</h5>
                                <p class="mb-3">Mulai catat penerimaan atau pengeluaran barang inventori.</p>
                                <a href="{{ route('stock-movements.create') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-plus-lg me-1"></i> Catat Mutasi Baru
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($movements->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $movements->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
