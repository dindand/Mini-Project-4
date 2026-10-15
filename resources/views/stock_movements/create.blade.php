@extends('layouts.app')

@section('title', 'Catat Mutasi Stok Barang')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="fw-bold mb-1">Catat Mutasi Stok</h2>
                <p class="text-muted mb-0">Input penerimaan barang masuk (IN) atau pengeluaran barang (OUT).</p>
            </div>
            <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
            </a>
        </div>

        {{-- Callout Aturan Bisnis P4 --}}
        <div class="alert alert-info border-info-subtle bg-info-subtle d-flex align-items-start gap-2 mb-4 rounded-3">
            <i class="bi bi-shield-check fs-5 text-info mt-1"></i>
            <div>
                <strong>Penegakan Aturan Bisnis Stok (Slide 37, 41):</strong>
                <ul class="mb-0 ps-3 small">
                    <li><strong>Barang Masuk (IN):</strong> Menambah stok produk secara otomatis.</li>
                    <li><strong>Barang Keluar (OUT):</strong> Mengurangi stok produk. Jumlah keluar <strong>tidak boleh melebihi sisa stok</strong> yang tersedia di gudang.</li>
                    <li>Operasi dibungkus dalam <strong>Database Transaction</strong> atomik (ACID) untuk mencegah race condition.</li>
                </ul>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('stock-movements.store') }}">
                    @csrf

                    {{-- Pilih Produk --}}
                    <div class="mb-3">
                        <label for="product_id" class="form-label fw-semibold">
                            Pilih Produk <span class="text-danger">*</span>
                        </label>
                        <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required onchange="updateStockInfo()">
                            <option value="">-- Pilih Produk Inventori --</option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}" 
                                    data-stock="{{ $prod->stock }}" 
                                    data-sku="{{ $prod->sku }}" 
                                    data-price="{{ $prod->price }}"
                                    {{ old('product_id', $selectedProductId) == $prod->id ? 'selected' : '' }}>
                                    [{{ $prod->sku }}] {{ $prod->name }} &mdash; (Sisa Stok: {{ $prod->stock }} unit)
                                </option>
                            @endforeach
                        </select>
                        <div id="stockNotice" class="form-text text-primary mt-1"></div>
                        @error('product_id')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Jenis Mutasi (IN / OUT) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold d-block">
                            Jenis Mutasi <span class="text-danger">*</span>
                        </label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="type" id="typeIN" value="IN" {{ old('type', $defaultType) === 'IN' ? 'checked' : '' }} autocomplete="off">
                            <label class="btn btn-outline-success py-2" for="typeIN">
                                <i class="bi bi-box-arrow-in-down me-1"></i> Barang Masuk (IN / Tambah Stok)
                            </label>

                            <input type="radio" class="btn-check" name="type" id="typeOUT" value="OUT" {{ old('type', $defaultType) === 'OUT' ? 'checked' : '' }} autocomplete="off">
                            <label class="btn btn-outline-warning text-dark py-2" for="typeOUT">
                                <i class="bi bi-box-arrow-up me-1"></i> Barang Keluar (OUT / Kurang Stok)
                            </label>
                        </div>
                        @error('type')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Jumlah / Quantity --}}
                    <div class="mb-3">
                        <label for="quantity" class="form-label fw-semibold">
                            Jumlah Barang (Quantity) <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="quantity" id="quantity" min="1" class="form-control form-control-lg @error('quantity') is-invalid @enderror" placeholder="Masukkan jumlah unit" value="{{ old('quantity') }}" required>
                        @error('quantity')
                            <div class="invalid-feedback d-block fw-semibold">
                                <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Catatan / Keterangan --}}
                    <div class="mb-4">
                        <label for="notes" class="form-label fw-semibold">Catatan Transaksi / Alasan Mutasi</label>
                        <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Contoh: Pengadaan batch 2, Pengiriman divisi operasional, Retur barang rusak, dll...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold">
                            <i class="bi bi-check-circle me-1"></i> Simpan Transaksi Mutasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateStockInfo() {
        const select = document.getElementById('product_id');
        const selected = select.options[select.selectedIndex];
        const notice = document.getElementById('stockNotice');
        if (selected && selected.value) {
            const stock = selected.getAttribute('data-stock');
            notice.innerHTML = `<i class="bi bi-info-circle me-1"></i> Sisa stok saat ini untuk produk ini: <strong>${stock} unit</strong>.`;
        } else {
            notice.innerHTML = '';
        }
    }
    document.addEventListener('DOMContentLoaded', updateStockInfo);
</script>
@endpush
@endsection
