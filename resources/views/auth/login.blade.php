@extends('layouts.app')

@section('title', 'Masuk ke Sistem Inventori')

@section('content')
<div class="row justify-content-center mt-4">
    <div class="col-md-5 col-lg-4">
        <div class="text-center mb-4">
            <div class="badge bg-primary p-3 rounded-4 mb-2 shadow-sm">
                <i class="bi bi-box-seam fs-1"></i>
            </div>
            <h3 class="fw-bold">Inventory System</h3>
            <p class="text-muted small">Silakan masuk menggunakan akun Admin atau Staff Anda.</p>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', 'admin') }}" required autofocus>
                        </div>
                        @error('username')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Kata Sandi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" value="password123" required>
                        </div>
                        @error('password')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember" checked>
                        <label class="form-check-label small text-muted" for="remember">Ingat Sesi Saya</label>
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary py-2 fw-semibold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
                        </button>
                    </div>
                </form>

                {{-- Quick Demo Credentials Card --}}
                <div class="p-3 bg-light rounded-3 border">
                    <small class="text-muted fw-bold d-block mb-2 text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em;">Akun Demo Pengujian:</small>
                    <div class="d-flex justify-content-between align-items-center mb-1 small">
                        <span><strong>Admin:</strong> <code>admin</code> / <code>password123</code></span>
                        <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" onclick="setCreds('admin')">Isi</button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center small">
                        <span><strong>Staff:</strong> <code>staff</code> / <code>password123</code></span>
                        <button type="button" class="btn btn-outline-secondary btn-sm py-0 px-2" onclick="setCreds('staff')">Isi</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function setCreds(user) {
        document.getElementById('username').value = user;
        document.getElementById('password').value = 'password123';
    }
</script>
@endpush
@endsection
