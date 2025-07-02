@extends('reseller.layout')

@section('title', 'Cek Produk')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Daftar Produk</h2>

    <div class="flex justify-between mb-4">
        <input type="text" id="search" class="border px-3 py-2 rounded w-full md:w-1/3" placeholder="Cari produk...">
        <select id="per-page" class="ml-4 border px-2 py-1 rounded">
            <option value="10">10 data</option>
            <option value="25">25 data</option>
            <option value="50">50 data</option>
        </select>
    </div>

    <table class="w-full text-left text-sm">
        <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-2">Kode</th>
                <th class="px-4 py-2">Nama</th>
                <th class="px-4 py-2">Nominal</th>
                <th class="px-4 py-2">Harga</th>
                <th class="px-4 py-2">Status</th>
            </tr>
        </thead>
        <tbody id="produk-body" class="bg-white dark:bg-gray-800">
            <!-- data dari JS -->
        </tbody>
    </table>

    <div id="pagination" class="mt-4 flex flex-wrap justify-start"></div>
</div>
@endsection

@push('scripts')
<script>
    let currentPage = 1;

    async function fetchProduk(page = 1) {
        const perPage = document.getElementById('per-page').value;
        const response = await fetch(`{{ route('reseller.cekproduk.data') }}?page=${page}&per_page=${perPage}`);
        const result = await response.json();

        const tbody = document.getElementById('produk-body');
        tbody.innerHTML = '';

        result.data.forEach(p => {
            tbody.innerHTML += `
                <tr class="border-t dark:border-gray-700">
                    <td class="px-4 py-2">${p.kode}</td>
                    <td class="px-4 py-2">${p.nama}</td>
                    <td class="px-4 py-2">${p.nominal}</td>
                    <td class="px-4 py-2">${p.harga_jual}</td>
                    <td class="px-4 py-2">${p.gangguan ? 'Gangguan' : 'Normal'}</td>
                </tr>
            `;
        });

        renderPagination(result);
    }

    function renderPagination(paginated) {
        const paginationDiv = document.getElementById('pagination');
        paginationDiv.innerHTML = '';

        for (let i = 1; i <= paginated.last_page; i++) {
            const button = document.createElement('button');
            button.innerText = i;
            button.className = `px-3 py-1 m-1 border rounded ${i === paginated.current_page ? 'bg-blue-600 text-white' : 'bg-white text-blue-600'}`;
            button.onclick = () => {
                currentPage = i;
                fetchProduk(i);
            };
            paginationDiv.appendChild(button);
        }
    }

    document.getElementById('per-page').addEventListener('change', () => fetchProduk(1));

    document.addEventListener('DOMContentLoaded', () => {
        fetchProduk();
    });
</script>
@endpush
