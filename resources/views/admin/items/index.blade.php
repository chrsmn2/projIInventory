@extends('layouts.admin')

@section('title', 'Items')

@section('content')


<div class="bg-white rounded-xl shadow border border-gray-200">

    <!-- HEADER -->
<div class="flex items-center justify-between px-6 py-4 rounded-t-xl
                bg-gradient-to-r from-gray-700 to-gray-800">

    <div>
        <h2 class="text-xl font-semibold text-black">Items</h2>
        <p class="text-sm text-gray-500">
            Master data inventory items (stock is read-only)
        </p>
    </div>

    <!-- RIGHT SIDE: Search, Show, +Add Item -->
    <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full md:w-auto">

        <!-- LEFT SIDE: Search + Show -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 flex-1">
            
            <!-- SEARCH -->
            <form method="GET" class="flex gap-2 flex-1 sm:flex-none">
                <input type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search item..."
                    class="px-3 py-2 border rounded-lg text-sm w-full sm:w-48 focus:ring focus:ring-blue-200">

                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                    Search
                </button>
            </form>

            <!-- SHOW PER PAGE -->
            <form method="GET" class="flex items-center">
                <input type="hidden" name="search" value="{{ $search }}">
                <select name="per_page"
                        onchange="this.form.submit()"
                        class="px-3 py-2 border rounded-lg text-sm">
                    @foreach([5,10,25,50] as $size)
                        <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                            Show {{ $size }}
                        </option>
                    @endforeach
                </select>
            </form>

        </div>

        <!-- RIGHT SIDE: +Add Item -->
        <a href="{{ route('admin.items.create') }}"
           class="inline-flex items-center justify-center gap-2
                  px-4 py-2
                  bg-emerald-500 text-white
                  font-semibold text-sm
                  rounded-lg
                  hover:bg-emerald-600
                  transition
                  ml-auto mt-2 sm:mt-0">
            + Add Item
        </a>

    </div>
</div>


    <!-- TABLE -->
    <div class="p-6 overflow-x-auto">
        <table class="w-full min-w-[900px] text-sm border border-gray-200 rounded-lg overflow-hidden">

            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Item Code</th>
                    <th class="px-4 py-3 text-left">Item Name</th>
                    <th class="px-4 py-3 text-left">Category</th>
                    <th class="px-4 py-3 text-center">Condition</th>
                    <th class="px-4 py-3 text-left">Description</th>
                    <th class="px-4 py-3 text-center">Min</th>
                    <th class="px-4 py-3 text-center">Stock</th>
                    <th class="px-4 py-3 text-left">Unit</th>
                    <th class="px-4 py-3 text-center">Action</th>

                </tr>
            </thead>

            <tbody class="divide-y bg-white">
            @forelse ($items as $item)
            <tr class="hover:bg-gray-50 transition">

                <!-- NO -->
                <td class="px-4 py-3 text-center">
                    {{ $items->firstItem() + $loop->index }}
                </td>

                <!-- ITEM CODE-->
                <td class="px-4 py-3 font-semibold text-gray-800">
                    {{ $item->item_code }}
                </td>

                <!-- ITEM NAME -->
                <td class="px-4 py-3 font-semibold text-gray-800">
                    {{ $item->item_name }}
                </td>

                <!-- CATEGORY -->
                <td class="px-4 py-3 text-gray-600">
                    {{ $item->category->name ?? '-' }}
                </td>

                <!-- CONDITION -->
                <td class="px-4 py-3 text-center">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $item->condition === 'normal'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($item->condition) }}
                    </span>
                </td>

                <!-- DESCRIPTION -->
                <td class="px-4 py-3 text-gray-600 max-w-xs truncate">
                    {{ $item->description ?? '-' }}
                </td>

                <!-- MINIMUM STOCK -->
                <td class="px-4 py-3 text-center font-bold">            
                    <span class="px-3 py-1 rounded-full text-xs
                        {{ $item->stock >= $item->min_stock
                            ? 'bg-blue-100 text-blue-700'
                            : 'bg-orange-100 text-orange-700' }}">
                        {{ $item->min_stock }}
                    </span>
                </td>

                <!-- STOCK (READ ONLY - BUSINESS RULE BASED) -->
                <td class="px-4 py-3 text-center font-bold">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $item->stock >= $item->min_stock
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-700' }}">
                        {{ $item->stock }}
                    </span>
                </td>


                <!-- UNITS -->
                <td class="px-6 py-3 text-gray-600">
                    {{ $item->unit->name ?? '-' }}
                </td>

                <!-- ACTION -->
                <td class="px-4 py-3">
                    <div class="flex justify-center gap-2">

                        <!-- EDIT -->
                        <a href="{{ route('admin.items.edit', $item) }}"
                        class="px-3 py-1.5 text-xs font-semibold
                                bg-blue-600 text-white
                                rounded hover:bg-blue-700 transition">
                            Update
                        </a>

                        <!-- DELETE (SAFE MODE) -->
                        @if ($item->stock > 0 || $item->loanDetails()->exists())
    <button
        disabled
        title="Item sudah memiliki stock / transaksi"
        class="px-3 py-1.5 text-xs font-semibold
               bg-gray-300 text-gray-500
               rounded cursor-not-allowed">
        Delete
    </button>
@else
    <form action="{{ route('admin.items.destroy', $item) }}"
          method="POST"
          onsubmit="return confirm('Delete this item?')">
        @csrf
        @method('DELETE')
        <button class="px-3 py-1.5 text-xs font-semibold
                       bg-red-600 text-white
                       rounded hover:bg-red-700">
            Delete
        </button>
    </form>
@endif


                    </div>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="8"
                    class="px-6 py-10 text-center text-gray-400 italic">
                    No items available
                </td>
            </tr>
            @endforelse
            </tbody>

        </table>
    </div>


    <!-- PAGINATION -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6">

    <!-- INFO JUMLAH DATA -->
    <div class="text-sm text-gray-600">
        Showing
        <span class="font-semibold">{{ $items->firstItem() }}</span>
        –
        <span class="font-semibold">{{ $items->lastItem() }}</span>
        of
        <span class="font-semibold">{{ $items->total() }}</span>
        items
    </div>

    <!-- BUTTON -->
<div class="flex items-center gap-2 mt-4">
    <!-- PREVIOUS -->
    <a href="{{ $items->previousPageUrl() ?? '#' }}"
       class="px-4 py-2 text-sm font-medium rounded-lg
              {{ $items->onFirstPage()
                    ? 'bg-gray-200 text-gray-400 cursor-not-allowed pointer-events-none'
                    : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-100' }}">
        <<  
    </a>

    <!-- NEXT -->
    <a href="{{ $items->nextPageUrl() ?? '#' }}"
       class="px-4 py-2 text-sm font-medium rounded-lg
              {{ $items->hasMorePages()
                    ? 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-100'
                    : 'bg-gray-200 text-gray-400 cursor-not-allowed pointer-events-none' }}">
        >>  
    </a>
</div>

</div>

</div>

</div>

@endsection

