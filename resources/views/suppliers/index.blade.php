@extends('layouts.app')

@section('title', 'Daftar Supplier Mitra')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Supplier Mitra</h2>
        <p class="text-muted mb-0">Kelola informasi mitra pemasok dan penyedia barang inventori.</p>
    </div>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Supplier
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 50px;">No</th>
                        <th>Nama Perusahaan</th>
                        <th>Kontak Person</th>
                        <th>No. Telepon / Email</th>
                        <th>Alamat</th>
                        <th class="text-center">Produk Mitra</th>
                        <th class="text-end pe-3" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $index => $sup)
                        <tr>
                            <td class="ps-3 text-muted">{{ $suppliers->firstItem() + $index }}</td>
                            <td><strong class="text-dark">{{ $sup->name }}</strong></td>
                            <td>{{ $sup->contact_person ?? '-' }}</td>
                            <td>
                                <div><i class="bi bi-telephone text-muted me-1"></i> {{ $sup->phone ?? '-' }}</div>
                                <small class="text-muted"><i class="bi bi-envelope text-muted me-1"></i> {{ $sup->email ?? '-' }}</small>
                            </td>
                            <td><small class="text-muted">{{ $sup->address ?? '-' }}</small></td>
                            <td class="text-center">
                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-3 py-1">
                                    {{ $sup->products_count }} Produk
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('suppliers.edit', $sup) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('suppliers.destroy', $sup) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" {{ $sup->products_count > 0 ? 'disabled title="Tidak dapat dihapus karena masih terikat dengan produk"' : '' }}>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                Belum ada supplier mitra terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($suppliers->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $suppliers->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
