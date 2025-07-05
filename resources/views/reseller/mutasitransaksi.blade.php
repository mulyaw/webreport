<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Mutasi Saldo - Paynara</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- PENTING: Menambahkan CSRF Token untuk request API -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="https://placehold.co/32x32/6366F1/FFFFFF?text=P" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Library untuk export ke Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    
    <!-- Library untuk Date Range Picker (Litepicker) -->
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css"/>

    <!-- The data function must be defined before Alpine is initialized -->
    <script>
        function mutationsTable() {
            return {
                search: '',
                itemsPerPage: 10,
                currentPage: 1,
                startDate: new Date(), // default: hari ini
                endDate: new Date(),   // default: hari ini
                dateFilterText: '',
                picker: null,
                mutations: [],
                loading: true,

                init() {
                    // Format teks awal filter
                    this.dateFilterText = this.formatDate(this.startDate) + ' - ' + this.formatDate(this.endDate);

                    this.fetchData();

                    // Setup Litepicker
                    this.picker = new Litepicker({
                        element: this.$refs.datepicker,
                        singleMode: false,
                        format: 'DD MMM YYYY',
                        lang: 'id-ID',
                        buttonText: {
                            previousMonth: `<i class="fa-solid fa-chevron-left"></i>`,
                            nextMonth: `<i class="fa-solid fa-chevron-right"></i>`,
                            reset: `<i class="fa-solid fa-eraser"></i>`,
                            apply: 'Terapkan',
                        },
                        setup: (picker) => {
                            picker.on('selected', (date1, date2) => {
                                this.startDate = date1.dateInstance;
                                this.endDate = date2.dateInstance;
                                this.dateFilterText = `${picker.getStartDate().format('DD MMM YYYY')} - ${picker.getEndDate().format('DD MMM YYYY')}`;
                                this.fetchData();
                            });
                        }
                    });

                    this.$watch('search', () => { this.currentPage = 1; });
                    this.$watch('itemsPerPage', () => { this.currentPage = 1; });
                },

                async fetchData() {
                    this.loading = true;
                    try {
                        const start = this.startDate.toISOString().split('T')[0];
                        const end = this.endDate.toISOString().split('T')[0];
                        
                        // FIX: Menggunakan helper route() untuk menghasilkan URL absolut, memperbaiki error "Failed to parse URL"
                        const url = `{{ route('mutasi.transaksi.data') }}?start=${start}&end=${end}`;

                        const response = await fetch(url, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        });
                        
                        if (!response.ok) {
                            // Menampilkan pesan error yang lebih spesifik
                            const errorText = await response.text();
                            throw new Error(`HTTP error! Status: ${response.status}, Pesan: ${errorText}`);
                        }

                        const data = await response.json();
                        this.mutations = data;
                    } catch (e) {
                        console.error("Gagal memuat data mutasi:", e);
                        this.mutations = [];
                    } finally {
                        this.loading = false;
                    }
                },

                clearDateFilter() {
                    this.startDate = new Date();
                    this.endDate = new Date();
                    this.dateFilterText = this.formatDate(this.startDate) + ' - ' + this.formatDate(this.endDate);
                    this.picker.clearSelection();
                    this.fetchData();
                },

                formatDate(date) {
                    return new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }).format(date);
                },

                get searchedMutations() {
                    if (!this.mutations) return []; // Menjaga agar tidak error jika mutations null
                    return this.mutations.filter(m => {
                        if (!this.search) return true;
                        const keyword = this.search.toLowerCase();
                        return (m.tanggal || '').toLowerCase().includes(keyword) ||
                               (m.keterangan || '').toLowerCase().includes(keyword) ||
                               String(m.jumlah).includes(keyword) ||
                               String(m.saldo_akhir).includes(keyword);
                    });
                },

                get paginatedMutations() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    return this.searchedMutations.slice(start, start + parseInt(this.itemsPerPage));
                },

                get totalPages() {
                    return Math.ceil(this.searchedMutations.length / this.itemsPerPage);
                },

                changePage(page) {
                    if (page !== '...' && page >= 1 && page <= this.totalPages) {
                        this.currentPage = page;
                    }
                },

                get pageNumbers() {
                    const maxPages = 7;
                    if (this.totalPages <= maxPages) {
                        return Array.from({ length: this.totalPages }, (_, i) => i + 1);
                    }
                    
                    const sidePages = Math.floor((maxPages - 3) / 2);
                    let startPage = this.currentPage - sidePages;
                    let endPage = this.currentPage + sidePages;

                    if (startPage < 1) {
                        endPage += (1 - startPage);
                        startPage = 1;
                    }

                    if (endPage > this.totalPages) {
                        startPage -= (endPage - this.totalPages);
                        endPage = this.totalPages;
                    }
                    
                    let pages = [];
                    for (let i = startPage; i <= endPage; i++) {
                        pages.push(i);
                    }

                    if (startPage > 1) {
                        if (startPage > 2) {
                           pages.unshift('...');
                        }
                        pages.unshift(1);
                    }
                    
                    if (endPage < this.totalPages) {
                        if (endPage < this.totalPages - 1) {
                            pages.push('...');
                        }
                        pages.push(this.totalPages);
                    }
                    
                    return pages;
                },

                formatCurrency(amount) {
                    if (isNaN(amount)) return 'Rp0';
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
                    }).format(amount);
                },

                formatAmount(amount) {
                    if (isNaN(amount)) return 'Rp0';
                    const formatted = this.formatCurrency(Math.abs(amount));
                    return amount > 0 ? `+ ${formatted}` : `- ${formatted}`;
                },

                exportToExcel() {
                    const dataToExport = this.searchedMutations.map(m => ({
                        'Tanggal': m.tanggal,
                        'Keterangan': m.keterangan,
                        'Jumlah': m.jumlah,
                        'Saldo Akhir': m.saldo_akhir
                    }));
                    const ws = XLSX.utils.json_to_sheet(dataToExport);
                    const wb = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(wb, ws, "Mutasi");
                    XLSX.writeFile(wb, "Mutasi_Saldo_Paynara.xlsx");
                }
            }
        }
    </script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #e5e7eb; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #a78bfa; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #8b5cf6; }
    </style>
</head>
<body class="p-4 md:p-8">

    <div
        x-data="mutationsTable()"
        x-init="init()"
        class="w-full max-w-7xl mx-auto"
    >
        <!-- Header, Tombol Kembali, dan Kontrol Filter -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-indigo-600 transition-colors" title="Kembali">
                    <i class="fa-solid fa-arrow-left fa-lg"></i>
                </a>
                <h1 class="text-3xl font-bold text-gray-800 text-center md:text-left">Mutasi Saldo</h1>
            </div>
            <!-- Grup untuk Filter -->
            <div class="flex flex-col sm:flex-row w-full md:w-auto items-center gap-2">
                <!-- Filter Tanggal -->
                <div class="relative w-full sm:w-auto">
                    <button x-ref="datepicker" type="button" class="w-full bg-white rounded-full shadow-md pl-10 pr-4 py-2.5 text-left text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <span x-text="dateFilterText"></span>
                    </button>
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fa-solid fa-calendar-day"></i>
                    </span>
                </div>
                <!-- Filter Pencarian -->
                <div class="relative flex-grow w-full sm:w-auto">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fa-solid fa-search"></i>
                    </span>
                    <input
                        type="text"
                        x-model="search"
                        placeholder="Cari mutasi..."
                        class="w-full pl-10 pr-4 py-2.5 bg-white rounded-full shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                    >
                </div>
                <!-- Tombol Export -->
                <button @click="exportToExcel()" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-full shadow-md transition-all duration-300 flex items-center justify-center gap-2" title="Export ke Excel">
                    <i class="fa-solid fa-file-excel"></i>
                    <span class="hidden sm:inline">Export</span>
                </button>
            </div>
        </div>

        <!-- Container Tabel -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3">Keterangan</th>
                            <th scope="col" class="px-6 py-3 text-right">Jumlah</th>
                            <th scope="col" class="px-6 py-3 text-right">Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Indikator Loading -->
                        <template x-if="loading">
                            <tr>
                                <td colspan="4" class="text-center py-10">
                                    <div class="flex justify-center items-center gap-3 text-gray-500">
                                        <svg class="animate-spin h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Memuat data...</span>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <!-- Data atau Pesan Kosong -->
                        <template x-if="!loading">
                            <template x-if="searchedMutations.length > 0">
                                <template x-for="(mutation, index) in paginatedMutations" :key="index">
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4" x-text="mutation.tanggal"></td>
                                        <td class="px-6 py-4" x-text="mutation.keterangan"></td>
                                        <td class="px-6 py-4 text-right font-semibold"
                                            :class="{'text-green-600': mutation.jumlah > 0, 'text-red-600': mutation.jumlah < 0}"
                                            x-text="formatAmount(mutation.jumlah)"></td>
                                        <td class="px-6 py-4 text-right font-semibold" x-text="formatCurrency(mutation.saldo_akhir)"></td>
                                    </tr>
                                </template>
                            </template>
                            <template x-if="searchedMutations.length === 0">
                                <tr>
                                    <td colspan="4" class="text-center py-10 text-gray-500">
                                        <i class="fa-solid fa-box-open fa-2x mb-2"></i>
                                        <p>Tidak ada mutasi pada rentang tanggal ini.</p>
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                </table>
            </div>
            <!-- Paginasi -->
            <div class="flex flex-col md:flex-row justify-between items-center p-4 gap-4" x-show="!loading && totalPages > 0">
                <div class="flex items-center gap-2 text-sm">
                    <span>Tampilkan</span>
                    <select x-model="itemsPerPage" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-1.5">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>entri</span>
                </div>
                <nav>
                    <ul class="inline-flex items-center -space-x-px text-sm">
                        <li>
                            <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                        </li>
                        <template x-for="(page, index) in pageNumbers" :key="index">
                             <li>
                                <button @click="changePage(page)"
                                    :class="{
                                        'text-indigo-600 border-indigo-500 bg-indigo-50': page === currentPage,
                                        'text-gray-500 bg-white': page !== currentPage,
                                        'cursor-default': page === '...'
                                    }"
                                    :disabled="page === '...'"
                                    class="flex items-center justify-center px-3 h-8 leading-tight border border-gray-300 hover:bg-gray-100 hover:text-gray-700"
                                    x-text="page"></button>
                            </li>
                        </template>
                        <li>
                            <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
         <p class="text-center text-xs text-gray-500 mt-6">
            &copy; 2024 Paynara. All rights reserved.
        </p>
    </div>

</body>
</html>
