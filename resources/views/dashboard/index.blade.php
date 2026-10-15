@extends('layouts.app')

@section('title', 'Dashboard Ringkasan Inventori')

@section('content')
<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <h2 class="fw-bold mb-1">Dashboard Inventori</h2>
        <p class="text-muted mb-0">Pemantauan stok barang, valuasi aset, dan status persediaan secara real-time.</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <a href="{{ route('stock-movements.create') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left-right me-1"></i> Catat Mutasi
        </a>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Produk
        </a>
    </div>
</div>

{{-- 4 Kartu Widget Agregat --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Total Produk</span>
                    <h3 class="fw-bold my-1 text-primary">{{ number_format($totalProducts) }}</h3>
                    <small class="text-muted"><i class="bi bi-boxes me-1"></i> {{ $totalStock }} total unit stok</small>
                </div>
                <div class="rounded-circle bg-primary-subtle p-3 text-primary">
                    <i class="bi bi-box-seam fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card h-100 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Nilai Aset Stok</span>
                    <h3 class="fw-bold my-1 text-success">Rp {{ number_format($totalValuation, 0, ',', '.') }}</h3>
                    <small class="text-muted"><i class="bi bi-cash-stack me-1"></i> Valuasi persediaan</small>
                </div>
                <div class="rounded-circle bg-success-subtle p-3 text-success">
                    <i class="bi bi-wallet2 fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card h-100 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Stok Kritis / Menipis</span>
                    <h3 class="fw-bold my-1 {{ $lowStockCount > 0 ? 'text-danger' : 'text-secondary' }}">
                        {{ $lowStockCount }}
                    </h3>
                    <small class="text-muted"><i class="bi bi-exclamation-triangle me-1"></i> Perlu restock segera</small>
                </div>
                <div class="rounded-circle {{ $lowStockCount > 0 ? 'bg-danger-subtle text-danger' : 'bg-light text-secondary' }} p-3">
                    <i class="bi bi-bell-fill fs-4"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card h-100 p-3 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small text-uppercase fw-semibold">Kategori & Rekanan</span>
                    <h3 class="fw-bold my-1 text-info">{{ $totalCategories }} / {{ $totalSuppliers }}</h3>
                    <small class="text-muted"><i class="bi bi-tags me-1"></i> Kategori / Supplier</small>
                </div>
                <div class="rounded-circle bg-info-subtle p-3 text-info">
                    <i class="bi bi-grid fs-4"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Tabel Produk Stok Menipis / Kritis --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold mb-0 text-danger d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-octagon-fill"></i> Peringatan Stok Kritis (Under Min. Stock)
                </h5>
                <a href="{{ route('products.index', ['stock_status' => 'low']) }}" class="btn btn-sm btn-outline-danger">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th class="text-center">Sisa Stok</th>
                                <th class="text-center">Min. Stok</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $low)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $low->name }}</div>
                                        <small class="text-muted">{{ $low->sku }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $low->category->name }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger rounded-pill px-2 py-1 fs-6">{{ $low->stock }}</span>
                                    </td>
                                    <td class="text-center text-muted">
                                        {{ $low->minimum_stock }}
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('stock-movements.create', ['product_id' => $low->id, 'type' => 'IN']) }}" class="btn btn-sm btn-success" title="Tambah Stok">
                                            <i class="bi bi-plus-lg"></i> Restock
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-check-circle text-success fs-3 d-block mb-1"></i>
                                        Seluruh stok produk berada dalam batas aman!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Riwayat Mutasi Stok Terakhir --}}
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary"></i> Mutasi Stok Terakhir
                </h5>
                <a href="{{ route('stock-movements.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th>Petugas</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMovements as $mov)
                                <tr>
                                    <td>
                                        @if($mov->type === 'IN')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                                <i class="bi bi-arrow-down-left"></i> MASUK
                                            </span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                                                <i class="bi bi-arrow-up-right"></i> KELUAR
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-truncate" style="max-width: 180px;">{{ $mov->product->name ?? '-' }}</div>
                                        <small class="text-muted">{{ $mov->product->sku ?? '' }}</small>
                                    </td>
                                    <td class="text-center fw-bold {{ $mov->type === 'IN' ? 'text-success' : 'text-danger' }}">
                                        {{ $mov->type === 'IN' ? '+' : '-' }}{{ $mov->quantity }}
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $mov->user->name ?? 'Staff' }}</small>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $mov->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Belum ada riwayat mutasi stok.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
