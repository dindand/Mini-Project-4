@extends('layouts.app')

@section('title', 'Kategori Produk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Kategori Produk</h2>
        <p class="text-muted mb-0">Kelola pengelompokan produk inventori.</p>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3" style="width: 50px;">No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-center">Jumlah Produk</th>
                        <th class="text-end pe-3" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $cat)
                        <tr>
                            <td class="ps-3 text-muted">{{ $categories->firstItem() + $index }}</td>
                            <td>
                                <strong class="text-dark">{{ $cat->name }}</strong>
                            </td>
                            <td class="text-muted">{{ $cat->description ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1">
                                    {{ $cat->products_count }} Produk
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" {{ $cat->products_count > 0 ? 'disabled title="Tidak dapat dihapus karena memiliki produk terkait"' : '' }}>
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Belum ada kategori tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($categories->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $categories->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
