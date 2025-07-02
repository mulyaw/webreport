<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard Reseller')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .dark body { background-color: #111827; }
        .sidebar-link.active { background-color: #3b82f6; color: white; font-weight: 600; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 dark:bg-gray-900">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white dark:bg-gray-800 shadow-lg p-4">
            <div class="text-2xl font-bold text-blue-600 mb-6">ResellerApp</div>
            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('reseller.cekproduk') }}" class="sidebar-link {{ request()->routeIs('reseller.cekproduk') ? 'active' : '' }}">Cek Produk</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white dark:bg-gray-800 shadow p-4 flex justify-between items-center">
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">@yield('title', 'Dashboard')</h1>
                <form action="{{ route('reseller.logout') }}" method="POST">@csrf
                    <button class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
                </form>
            </header>

            <main class="p-6 flex-1 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
