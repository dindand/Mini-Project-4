@extends('layouts.app')

@section('title', 'Detail Produk - ' . $product->name)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-secondary-subtle text-secondary-emphasis font-monospace fs-6 px-2 py-1">{{ $product->sku }}</span>
            <h2 class="fw-bold mb-0">{{ $product->name }}</h2>
        </div>
        <p class="text-muted mb-0">Informasi spesifikasi barang, status persediaan, dan jejak riwayat mutasi.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i> Edit Data
        </a>
    </div>
</div>

<div class="row g-4">
    {{-- Card Ringkasan Produk --}}
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-info-circle me-1 text-primary"></i> Data Utama</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Kategori:</span>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-6">
                            {{ $product->category->name }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Harga Satuan:</span>
                        <span class="fw-bold text-success fs-5">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0 align-items-center">
                        <span class="text-muted">Sisa Stok:</span>
                        <span class="badge rounded-pill fs-6 px-3 py-1 {{ $product->isLowStock() ? 'bg-danger' : 'bg-success' }}">
                            {{ $product->stock }} Unit
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Batas Minimum:</span>
                        <span class="fw-semibold text-secondary">{{ $product->minimum_stock }} Unit</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Total Nilai Stok:</span>
                        <span class="fw-bold text-dark">
                            Rp {{ number_format($product->stock * $product->price, 0, ',', '.') }}
                        </span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between px-0">
                        <span class="text-muted">Terdaftar Pada:</span>
                        <span class="text-muted small">{{ $product->created_at->format('d M Y, H:i') }}</span>
                    </li>
                </ul>

                <hr class="my-3">

                <div class="d-grid gap-2">
                    <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'IN']) }}" class="btn btn-success">
                        <i class="bi bi-box-arrow-in-down me-1"></i> Transaksi Barang Masuk (+)
                    </a>
                    <a href="{{ route('stock-movements.create', ['product_id' => $product->id, 'type' => 'OUT']) }}" class="btn btn-warning text-dark">
                        <i class="bi bi-box-arrow-up me-1"></i> Transaksi Barang Keluar (-)
                    </a>
                </div>
            </div>
        </div>

        {{-- Mitra Pemasok / Supplier --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-truck me-1 text-primary"></i> Mitra Supplier</h5>
            </div>
            <div class="card-body">
                @forelse($product->suppliers as $sup)
                    <div class="p-3 bg-light rounded-3 mb-2 border">
                        <div class="fw-bold">{{ $sup->name }}</div>
                        <div class="small text-muted">Kontak: {{ $sup->contact_person ?? '-' }}</div>
                        <div class="small text-muted">Telp: {{ $sup->phone ?? '-' }}</div>
                        @if($sup->pivot->supplier_price)
                            <div class="small text-success mt-1">Harga Beli: Rp {{ number_format($sup->pivot->supplier_price, 0, ',', '.') }}</div>
                        @endif
                    </div>
                @empty
                    <p class="text-muted small mb-0">Belum ada mitra pemasok yang dihubungkan ke produk ini.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Riwayat Mutasi Stok Produk Ini --}}
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history me-1 text-primary"></i> Riwayat Mutasi Stok</h5>
                <span class="badge bg-light text-muted border">{{ $product->stockMovements->count() }} Transaksi</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Tipe</th>
                                <th class="text-center">Jumlah</th>
                                <th>Petugas</th>
                                <th>Catatan / Keterangan</th>
                                <th class="pe-3">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($product->stockMovements as $mov)
                                <tr>
                                    <td class="ps-3">
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
                                    <td class="text-center fw-bold fs-6 {{ $mov->type === 'IN' ? 'text-success' : 'text-danger' }}">
                                        {{ $mov->type === 'IN' ? '+' : '-' }}{{ $mov->quantity }}
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $mov->user->name ?? 'Staff Gudang' }}</div>
                                        <small class="text-muted">({{ $mov->user->role ?? 'staff' }})</small>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $mov->notes ?? '-' }}</span>
                                    </td>
                                    <td class="pe-3 text-muted small">
                                        {{ $mov->created_at->format('d M Y, H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Belum ada riwayat pergerakan stok untuk produk ini.
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
