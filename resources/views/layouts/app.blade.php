<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Manajemen Inventori') - Inventory Laravel</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }
        .table thead th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom-width: 1px;
        }
        .table td {
            vertical-align: middle;
        }
        .badge-subtle-success {
            background-color: #dcfce7;
            color: #15803d;
        }
        .badge-subtle-danger {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .badge-subtle-primary {
            background-color: #e0e7ff;
            color: #4338ca;
        }
        .badge-subtle-warning {
            background-color: #fef3c7;
            color: #b45309;
        }
        .pagination {
            margin-bottom: 0;
        }
        .footer {
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 1.25rem 0;
            font-size: 0.875rem;
            color: #64748b;
        }
    </style>
    @stack('styles')
</head>
<body>
    {{-- Komponen Navigasi Utama (Slide 22) --}}
    @include('layouts.navigation')

    <main class="py-4">
        <div class="container">
            {{-- Flash Notification Alerts menggunakan Blade Component (Slide 23) --}}
            @if(session('success'))
                <x-alert type="success">
                    {{ session('success') }}
                </x-alert>
            @endif

            @if(session('error'))
                <x-alert type="danger">
                    {{ session('error') }}
                </x-alert>
            @endif

            @if(session('info'))
                <x-alert type="info">
                    {{ session('info') }}
                </x-alert>
            @endif

            @if($errors->any())
                <x-alert type="danger">
                    <strong class="d-block mb-1">Terjadi kesalahan input:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            {{-- Slot Konten Halaman (Slide 22) --}}
            @yield('content')
        </div>
    </main>

    <footer class="footer mt-auto text-center">
        <div class="container">
            <p class="mb-1"><strong>Inventory Management System</strong> &bull; Migrasi P4 ke Laravel Fundamental (P5)</p>
            <p class="mb-0 text-muted small">Implementasi Arsitektur MVC &bull; Request &rarr; Route &rarr; Controller &rarr; Model &rarr; Database &rarr; Response</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
