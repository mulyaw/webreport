@extends('reseller.layout')

@section('title', 'Cek Produk')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">Daftar Produk</h2>
        <input type="text" id="search" class="border px-3 py-2 rounded w-full mb-4" placeholder="Cari produk...">

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
                <!-- AJAX content will be injected here -->
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<script>
    async function fetchProduk() {
        const response = await fetch('/api/reseller/produk'); // pastikan rute ini ada dan mengembalikan JSON
        const data = await response.json();
        const tbody = document.getElementById('produk-body');
        tbody.innerHTML = '';

        data.forEach(p => {
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
    }

    document.addEventListener('DOMContentLoaded', fetchProduk);
</script>
@endpush
