@extends('layouts.app')

@section('title', 'Supervisor Dashboard')

@section('content')

<!-- ================= SUMMARY CARDS ================= -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

    <!-- Pending Approval -->
    <div class="bg-white p-5 rounded-xl shadow border-l-4 border-yellow-500">
        <h3 class="text-gray-500 text-sm">Pending Loan Approval</h3>
        <p class="text-3xl font-bold text-gray-800">
            {{ $pendingCount }}
        </p>
    </div>

    <!-- Low Stock -->
    <div class="bg-white p-5 rounded-xl shadow border-l-4 border-red-500">
        <h3 class="text-gray-500 text-sm">Low Stock Items</h3>
        <p class="text-3xl font-bold text-gray-800">
            {{ count($lowStockItems) }}
        </p>
    </div>

    <!-- Total Items -->
    <div class="bg-white p-5 rounded-xl shadow border-l-4 border-blue-500">
        <h3 class="text-gray-500 text-sm">Total Items</h3>
        <p class="text-3xl font-bold text-gray-800">
            {{ $totalItems ?? 0 }}
        </p>
    </div>

</div>

<!-- ================= LOAN APPROVAL ================= -->
<div class="bg-white rounded-xl shadow p-6 mb-6">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Loan Approval</h2>

        <a href="{{ route('supervisor.loan-approvals.index') }}"
           class="text-sm text-blue-600 hover:underline">
            View All
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-sm">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-3">Borrower</th>
                    <th class="p-3">Loan Date</th>
                    <th class="p-3">Items</th>
                    <th class="p-3 text-center">Total Qty</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($pendingLoans as $loan)
                <tr class="border-b hover:bg-gray-50">

                    <td class="p-3 font-medium">
                        @if($loan->borrower)
                            {{ $loan->borrower->name }}
                        @else
                            <span class="text-gray-400 italic">Unknown Borrower</span>
                        @endif
                    </td>

                    <td class="p-3">
                        {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}
                    </td>

                    <td class="p-3">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($loan->details as $detail)
                                <li>
                                    {{ $detail->item->item_name }}
                                    ({{ $detail->quantity }})
                                </li>
                            @endforeach
                        </ul>
                    </td>

                    <td class="p-3 text-center font-bold">
                        {{ $loan->details->sum('quantity') }}
                    </td>

                    <td class="p-3 text-center">
                        <div class="flex justify-center gap-2">

                            <!-- APPROVE -->
                            <form action="{{ route('supervisor.loan-approvals.approve', $loan->id) }}"
                                  method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="px-3 py-1 bg-green-600 text-white rounded
                                           hover:bg-green-700">
                                    Approve
                                </button>
                            </form>

                            <!-- REJECT -->
                            <form action="{{ route('supervisor.loan-approvals.reject', $loan->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to reject this loan?');">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="px-3 py-1 bg-red-600 text-white rounded
                                           hover:bg-red-700">
                                    Reject
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5"
                        class="text-center text-gray-500 py-6 italic">
                        No loan requests pending approval
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>

<!-- ================= LOW STOCK ================= -->
<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-red-600">
            Low Stock Alert
        </h2>

        <a href="{{ route('supervisor.stock.low') }}"
           class="text-sm text-blue-600 hover:underline">
            View Details
        </a>
    </div>

    <table class="w-full text-sm border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="p-3 text-left">Item</th>
                <th class="p-3 text-left">Stock</th>
                <th class="p-3 text-left">Minimum</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($lowStockItems as $item)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3 font-medium">
                    {{ $item->item_name }}
                </td>
                <td class="p-3 text-red-600 font-bold">
                    {{ $item->stock }}
                </td>
                <td class="p-3">
                    {{ $item->min_stock }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3"
                    class="text-center text-gray-500 py-6 italic">
                    No low stock items
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

@endsection
