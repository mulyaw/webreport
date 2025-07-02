<!DOCTYPE html>
<html lang="id" class="">
<head>
    <meta charset="UTF-8">
    <title>Dasbor Reseller Modern</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .dark body {
            background-color: #111827;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.07), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: background-color 0.2s, color 0.2s;
            color: #4b5563;
        }
        .sidebar-link:hover {
            background-color: #e5e7eb;
        }
        .sidebar-link.active {
            background-color: #3b82f6;
            color: white;
            font-weight: 600;
        }
        .dark .sidebar-link {
            color: #9ca3af;
        }
        .dark .sidebar-link:hover {
            background-color: #374151;
        }
        .dark .sidebar-link.active {
            background-color: #2563eb;
            color: white;
        }
        /* Styling untuk transisi sidebar */
        #sidebar {
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
        }
        .sidebar-collapsed #sidebar {
            width: 0;
            transform: translateX(-100%);
        }
        .filter-btn {
            padding: 6px 12px; border-radius: 8px; font-size: 14px; font-weight: 500;
            transition: background-color 0.2s, color 0.2s; background-color: #f3f4f6; color: #4b5563;
        }
        .dark .filter-btn { background-color: #374151; color: #d1d5db; }
        .filter-btn.active { background-color: #3b82f6; color: white; }
        .dark .filter-btn.active { background-color: #2563eb; color: white; }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900">

    <div id="main-container" class="flex h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 bg-white dark:bg-gray-800 shadow-lg w-64 p-4 flex flex-col transform md:relative md:translate-x-0 z-30">
            <div class="flex items-center justify-between mb-8 flex-shrink-0">
                <span class="text-2xl font-bold text-blue-600 dark:text-blue-500">ResellerApp</span>
                 <button id="close-sidebar-btn" class="md:hidden text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <nav class="flex-grow space-y-2">
                <a href="#" class="sidebar-link active">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('reseller.cekproduk') }}" class="sidebar-link active">

                     <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Cek Produk</span>
                </a>
                <a href="#" class="sidebar-link">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Cek Downline</span>
                </a>
                <!-- Menu Laporan dengan Dropdown -->
                <div>
                    <button id="laporan-btn" class="w-full sidebar-link text-left">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>Laporan</span>
                        <svg id="laporan-arrow" class="w-5 h-5 ml-auto transition-transform transform" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </button>
                    <div id="laporan-submenu" class="hidden mt-1 pl-8 space-y-1">
                        <a href="#" class="block text-sm py-2 text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white">Mutasi Transaksi</a>
                        <a href="#" class="block text-sm py-2 text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white">Mutasi Deposit</a>
                        <a href="#" class="block text-sm py-2 text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white">Mutasi Saldo</a>
                        <a href="#" class="block text-sm py-2 text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white">Laba Rugi</a>
                    </div>
                </div>
            </nav>
            <div class="mt-auto flex-shrink-0">
                <button id="hide-sidebar-btn" class="w-full sidebar-link text-left">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                    <span>Sembunyikan</span>
                </button>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white dark:bg-gray-800 shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <button id="open-sidebar-btn" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h1 class="text-xl font-bold text-gray-800 dark:text-white hidden sm:block">
    Halo, {{ $reseller->nama }}
</h1>
                    <form action="{{ route('reseller.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white font-semibold px-4 py-2 rounded-lg shadow-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-75 transition duration-200">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 sm:p-6 lg:p-8">
                <div class="space-y-6">
                    <!-- Ringkasan Saldo -->
                    <section>
                        <h2 class="text-xl font-semibold mb-4 text-gray-700 dark:text-gray-300">Ringkasan Saldo</h2>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="card lg:col-span-1 bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-2xl shadow-lg text-white flex flex-col justify-between">
                                <div>
                                    <p class="text-lg opacity-80">Saldo Utama</p>
                                    <p class="text-4xl font-bold mt-2">
    Rp {{ number_format($reseller->saldo, 0, ',', '.') }}
</p>
                                </div>
                                <button class="mt-6 w-full bg-white text-blue-600 font-bold py-3 px-4 rounded-lg hover:bg-blue-50 transition duration-200 flex items-center justify-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    <span>Top Up Saldo</span>
                                </button>
                            </div>
                            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="card bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center space-x-4 mb-2">
                                        <div class="p-3 bg-green-100 rounded-full"><svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16h10M7 16a2 2 0 100 4h10a2 2 0 100-4H7z"></path></svg></div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Komisi</p>
                                    </div>
                                    <div class="flex items-baseline space-x-2 mt-1">
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
    Rp {{ number_format($reseller->komisi, 0, ',', '.') }}
</p>
                                        <div class="flex items-center text-sm font-semibold text-green-500"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg><span>5.2%</span></div>
                                    </div>
                                </div>
                                <div class="card bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center space-x-4 mb-2">
                                        <div class="p-3 bg-yellow-100 rounded-full"><svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-12v4m-2-2h4m6 4v4m-2-2h4M18 21a3 3 0 100-6 3 3 0 000 6zM6 21a3 3 0 100-6 3 3 0 000 6zM6 9a3 3 0 100-6 3 3 0 000 6z"></path></svg></div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Poin</p>
                                    </div>
                                     <div class="flex items-baseline space-x-2 mt-1">
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
    {{ number_format($reseller->poin) }}
</p>

                                        <div class="flex items-center text-sm font-semibold text-red-500"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg><span>1.8%</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Statistik Transaksi -->
                    <section class="card bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700">
                        <div class="flex flex-wrap justify-between items-center mb-4 gap-3">
                            <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300">Grafik Transaksi</h2>
                            <div id="chart-filters" class="flex space-x-1 sm:space-x-2">
                                <button data-period="daily" class="filter-btn">Hari ini</button>
                                <button data-period="weekly" class="filter-btn active">7 Hari</button>
                                <button data-period="monthly" class="filter-btn">30 Hari</button>
                            </div>
                        </div>
                        <div class="h-80">
                            <canvas id="transactionChart"></canvas>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- Sidebar & Dropdown Logic ---
            const mainContainer = document.getElementById('main-container');
            const openSidebarBtn = document.getElementById('open-sidebar-btn');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const hideSidebarBtn = document.getElementById('hide-sidebar-btn');
            const sidebar = document.getElementById('sidebar');
            
            const laporanBtn = document.getElementById('laporan-btn');
            const laporanSubmenu = document.getElementById('laporan-submenu');
            const laporanArrow = document.getElementById('laporan-arrow');

            const toggleSidebar = () => {
                sidebar.classList.toggle('-translate-x-full');
                mainContainer.classList.toggle('sidebar-collapsed');
            };

            openSidebarBtn.addEventListener('click', () => sidebar.classList.remove('-translate-x-full'));
            closeSidebarBtn.addEventListener('click', () => sidebar.classList.add('-translate-x-full'));
            hideSidebarBtn.addEventListener('click', () => sidebar.classList.add('-translate-x-full'));

            laporanBtn.addEventListener('click', () => {
                laporanSubmenu.classList.toggle('hidden');
                laporanArrow.classList.toggle('rotate-180');
            });

            // --- Chart Logic ---
            const transactionCanvas = document.getElementById('transactionChart');
            if (transactionCanvas) {
                // --- Data Sampel untuk Grafik ---
                const chartData = {
                    daily: {
                        labels: ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00'],
                        data: [5, 8, 15, 25, 30, 45, 50, 60]
                    },
                    weekly: {
                        labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                        data: [120, 150, 130, 180, 210, 250, 230]
                    },
                    monthly: {
                        labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                        data: [800, 1200, 950, 1500]
                    }
                };

                const ctx = transactionCanvas.getContext('2d');
                let transactionChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartData.weekly.labels,
                        datasets: [{
                            label: 'Jumlah Transaksi',
                            data: chartData.weekly.data,
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 3,
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                            pointBorderColor: '#fff',
                            pointHoverRadius: 7,
                            pointHoverBorderWidth: 2,
                            tension: 0.4,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        scales: { 
                            y: { beginAtZero: true, grid: { color: '#e5e7eb' } }, 
                            x: { grid: { display: false } } 
                        },
                        plugins: { legend: { display: false } },
                        interaction: { intersect: false, mode: 'index' }
                    }
                });

                const chartFilters = document.getElementById('chart-filters');
                chartFilters.addEventListener('click', (e) => {
                    if (e.target.tagName === 'BUTTON') {
                        // Hapus kelas active dari semua tombol
                        chartFilters.querySelectorAll('button').forEach(btn => btn.classList.remove('active'));
                        // Tambahkan kelas active ke tombol yang diklik
                        e.target.classList.add('active');

                        const period = e.target.dataset.period;
                        transactionChart.data.labels = chartData[period].labels;
                        transactionChart.data.datasets[0].data = chartData[period].data;
                        transactionChart.update();
                    }
                });
            }
        });
    </script>

</body>
</html>
