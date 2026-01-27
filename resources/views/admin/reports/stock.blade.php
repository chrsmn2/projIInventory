@extends('layouts.admin')

@section('title', 'Stock Report')

@section('content')

<style>
    /* Mengatur tampilan layar (Screen) agar tetap modern */
    .stats-section { transition: all 0.3s ease; }
    
    @media print {
        /* 1. Sembunyikan elemen UI yang tidak perlu */
        .no-print, form, button, nav, aside, .stats-section, .legend-section, .pagination-nav {
            display: none !important;
        }

        /* 2. Setup Halaman A4 */
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }

        body {
            background: white !important;
            font-family: 'Arial', sans-serif;
            color: #000 !important;
        }

        /* 3. Header Laporan (Judul Atas & Center) */
        .print-header-top {
            display: block !important;
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .print-header-top h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .print-header-top h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 5px 0;
            text-decoration: underline;
        }

        /* 4. Tabel Bersih & Teks Hitam */
        .print-content {
            padding: 0 !important;
            margin: 0 !important;
        }

        table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #000 !important; /* Border Hitam */
            padding: 8px !important;
            color: #000 !important; /* Paksa Teks Hitam */
            background: transparent !important;
            font-size: 10pt !important;
        }

        th {
            background-color: #f2f2f2 !important;
            text-transform: uppercase;
        }

        /* Menghilangkan warna pada badge saat print */
        .badge-print-clean {
            background: none !important;
            color: #000 !important;
            padding: 0 !important;
            font-weight: normal;
        }

        /* 5. Signature / Pengesahan */
        .signature-container {
            display: grid !important;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 50px;
            page-break-inside: avoid;
        }

        .sig-box {
            border: 1px solid #000;
            text-align: center;
            height: 120px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sig-line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 0 auto 5px auto;
        }
    }
</style>

<div class="hidden print:block print-header-top">
    <h1>PT. INVENTORY MANAGEMENT</h1>
    <h2>LAPORAN MUTASI STOK</h2>
    <p class="text-sm">Periode: {{ now()->format('d F Y') }}</p>
</div>

<div class="bg-white rounded-xl shadow border border-gray-200 no-print">
    <div class="flex items-center justify-between px-6 py-4 rounded-t-xl bg-gradient-to-r from-gray-700 to-gray-800">
        <div>
            <h2 class="text-xl font-semibold text-white">Stock Report</h2>
            <p class="text-sm text-gray-300">Current inventory stock levels and analysis</p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full md:w-auto">
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search item..."
                    class="px-3 py-2 border rounded-lg text-sm w-full sm:w-48 focus:ring focus:ring-blue-200">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Search</button>
            </form>

            <button onclick="window.print()" class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm hover:bg-gray-700">
                📄 Export PDF
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-6 border-b border-gray-200 stats-section">
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
            <p class="text-xs text-gray-500 font-medium uppercase">Total Items</p>
            <p class="text-2xl font-bold text-gray-900">{{ $totalItems }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
            <p class="text-xs text-gray-500 font-medium uppercase">Total Stock</p>
            <p class="text-2xl font-bold text-green-600">{{ $totalStock }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
            <p class="text-xs text-gray-500 font-medium uppercase">Low Stock</p>
            <p class="text-2xl font-bold text-yellow-500">{{ $lowStockCount }}</p>
        </div>
        <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
            <p class="text-xs text-gray-500 font-medium uppercase">Out of Stock</p>
            <p class="text-2xl font-bold text-red-600">{{ $outOfStockCount }}</p>
        </div>
    </div>
</div>

<div class="p-0 sm:p-6 bg-white print-content mt-4">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-3">No</th>
                <th class="px-4 py-3 text-left">Kode Barang</th>
                <th class="px-4 py-3 text-left">Nama Barang</th>
                <th class="px-4 py-3 text-left">Kategori</th>
                <th class="px-4 py-3 text-center">Kondisi</th>
                <th class="px-4 py-3 text-center">Stok</th>
                <th class="px-4 py-3 text-center no-print">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
            <tr>
                <td class="text-center">{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}</td>
                <td class="badge-print-clean">{{ $item->item_code }}</td>
                <td class="font-medium">{{ $item->item_name }}</td>
                <td>{{ $item->category->name ?? '-' }}</td>
                <td class="text-center">{{ $item->condition }}</td>
                <td class="text-center font-bold {{ $item->stock < 5 ? 'text-red-600' : '' }}">{{ $item->stock }}</td>
                <td class="text-center no-print">
                    <span class="px-2 py-0.5 rounded text-xs font-medium 
                        {{ $item->stock == 0 ? 'bg-red-100 text-red-800' : ($item->stock < 5 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                        {{ $item->stock == 0 ? 'Out of Stock' : ($item->stock < 5 ? 'Low Stock' : 'Available') }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-10 text-gray-500">No items found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="hidden print:block mt-4">
        <p class="text-xs italic text-red-600">*Baris dengan angka stok merah menandakan di bawah minimum.</p>
        
        <div class="signature-container">
            <div class="sig-box">
                <span class="text-xs font-bold">Dibuat Oleh (Admin)</span>
                <div class="sig-line"></div>
            </div>
            <div class="sig-box">
                <span class="text-xs font-bold">Diperiksa Oleh (Supervisor)</span>
                <div class="sig-line"></div>
            </div>
            <div class="sig-box">
                <span class="text-xs font-bold">Disetujui Oleh (Manager)</span>
                <div class="sig-line"></div>
            </div>
        </div>
    </div>
</div>

<div class="px-6 pb-6 no-print">
    {{ $items->links() }}
</div>

@endsection