<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cek Harga Produk - Paynara</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://placehold.co/32x32/6366F1/FFFFFF?text=P" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-thumb {
            background: #a78bfa;
            border-radius: 10px;
        }
    </style>
</head>
<body class="p-4 md:p-8">

<script>
    function productsTable() {
        return {
            search: '',
            itemsPerPage: 10,
            currentPage: 1,
            products: [],
            loading: true,
            error: false,

            async fetchData() {
                this.loading = true;
                this.error = false;
                try {
                    const res = await fetch(`{{ route('reseller.cekproduk.data') }}?search=${this.search}&per_page=1000`);
                    const result = await res.json();
                    this.products = result.data.map(p => ({
                        kode: p.kode,
                        nama: p.nama,
                        nominal: p.nominal,
                        harga: parseInt(p.harga_jual),
                        status: p.gangguan == 1 ? 'Gangguan' : 'Tersedia'
                    }));
                } catch (e) {
                    this.error = true;
                } finally {
                    this.loading = false;
                }
            },

            init() {
                this.fetchData();
                this.$watch('itemsPerPage', () => {
                    this.currentPage = 1;
                    this.fetchData();
                });
                this.$watch('search', () => {
                    this.currentPage = 1;
                    this.fetchData();
                });
            },

            get filteredProducts() {
                if (!this.search) return this.products;
                return this.products.filter(p =>
                    Object.values(p).some(val =>
                        String(val).toLowerCase().includes(this.search.toLowerCase())
                    )
                );
            },

            get paginatedProducts() {
                const start = (this.currentPage - 1) * this.itemsPerPage;
                return this.filteredProducts.slice(start, start + parseInt(this.itemsPerPage));
            },

            get totalPages() {
                return Math.ceil(this.filteredProducts.length / this.itemsPerPage);
            },

            changePage(p) {
                if (p >= 1 && p <= this.totalPages) {
                    this.currentPage = p;
                }
            },

            get pageNumbers() {
                return Array.from({ length: this.totalPages }, (_, i) => i + 1);
            },

            formatCurrency(amount) {
                if (isNaN(amount)) return '0';
                 return new Intl.NumberFormat('id-ID', {
        minimumFractionDigits: 0
                }).format(amount);
            },

            exportToExcel() {
                const dataToExport = this.filteredProducts.map(p => ({
                    'Kode': p.kode,
                    'Nama Produk': p.nama,
                    'Nominal': p.nominal,
                    'Harga': p.harga,
                    'Status': p.status
                }));
                const ws = XLSX.utils.json_to_sheet(dataToExport);
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, "Produk");
                XLSX.writeFile(wb, "Produk_Paynara.xlsx");
            }
        }
    }
</script>

<div x-data="productsTable()" x-init="init()" class="w-full max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-indigo-600 transition-colors" title="Kembali">
                <i class="fa-solid fa-arrow-left fa-lg"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-800 text-center md:text-left">Cek Harga Produk</h1>
        </div>
        <div class="flex w-full md:w-auto items-center gap-2">
            <div class="relative flex-grow">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="fa-solid fa-search"></i>
                </span>
                <input
                    type="text"
                    x-model.debounce.500ms="search"
                    placeholder="Cari produk..."
                    class="w-full pl-10 pr-4 py-2.5 bg-white rounded-full shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                />
            </div>
            <button @click="exportToExcel()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-4 rounded-full shadow-md transition-all duration-300 flex items-center gap-2">
                <i class="fa-solid fa-file-excel"></i>
                <span class="hidden sm:inline">Export</span>
            </button>
        </div>
    </div>

    <!-- Tabel -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Kode</th>
                        <th class="px-6 py-3">Nama Produk</th>
                        <th class="px-6 py-3">Nominal</th>
                        <th class="px-6 py-3 text-right">Harga</th>
                        <th class="px-6 py-3 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="loading">
                        <tr><td colspan="5" class="text-center py-10 text-gray-500">Memuat data produk...</td></tr>
                    </template>
                    <template x-if="error && !loading">
                        <tr><td colspan="5" class="text-center py-10 text-red-500">Gagal memuat data produk.</td></tr>
                    </template>
                    <template x-for="product in paginatedProducts" :key="product.kode">
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900" x-text="product.kode"></td>
                            <td class="px-6 py-4" x-text="product.nama"></td>
                            <td class="px-6 py-4" x-text="product.nominal"></td>
                            <td class="px-6 py-4 text-right font-semibold" x-text="formatCurrency(product.harga)"></td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    :class="{
                                        'bg-green-100 text-green-800': product.status === 'Tersedia',
                                        'bg-red-100 text-red-800': product.status === 'Gangguan'
                                    }"
                                    class="px-3 py-1 text-xs font-medium rounded-full"
                                    x-text="product.status"
                                ></span>
                            </td>
                        </tr>
                    </template>
                    <template x-if="!loading && filteredProducts.length === 0 && !error">
                        <tr><td colspan="5" class="text-center py-10 text-gray-500">Produk tidak ditemukan.</td></tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination dan Filter -->
        <div class="flex flex-col md:flex-row justify-between items-center p-4 gap-4">
            <div class="flex items-center gap-2 text-sm">
                <span>Tampilkan</span>
                <select x-model="itemsPerPage" @change="currentPage = 1; fetchData()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-1.5">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span>entri</span>
            </div>
            <!-- Navigasi Halaman -->
<nav>
    <div class="flex items-center gap-2">
        <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
            class="flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded disabled:opacity-50">
            <i class="fa-solid fa-chevron-left mr-2"></i> Sebelumnya
        </button>
        <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
            class="flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded disabled:opacity-50">
            Berikutnya <i class="fa-solid fa-chevron-right ml-2"></i>
        </button>
    </div>
</nav>

        </div>
    </div>

    <p class="text-center text-xs text-gray-500 mt-6">
        &copy; 2024 Paynara. All rights reserved.
    </p>
</div>

</body>
</html>
