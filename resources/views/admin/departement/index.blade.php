@extends('layouts.admin')

@section('title', 'Departement')

@section('content')

<div class="bg-white rounded-xl shadow border border-gray-200">
    <div class="flex items-center justify-between px-6 py-4 rounded-t-xl bg-gradient-to-r from-gray-700 to-gray-800">
        <div>
            <h2 class="text-xl font-semibold text-black">Departement</h2>
            <p class="text-sm text-gray-500">Master data inventory departement</p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full md:w-auto">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2 flex-1">
                <form method="GET" class="flex gap-2 flex-1 sm:flex-none">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search item..."
                        class="px-3 py-2 border rounded-lg text-sm w-full sm:w-48 focus:ring focus:ring-blue-200">
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                        Search
                    </button>
                </form>

                <form method="GET" class="flex items-center">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <select name="per_page" onchange="this.form.submit()" class="px-3 py-2 border rounded-lg text-sm">
                        @foreach([5,10,25,50] as $size)
                            <option value="{{ $size }}" {{ $perPage == $size ? 'selected' : '' }}>
                                Show {{ $size }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <a href="{{ route('admin.departement.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-500 text-white font-semibold text-sm rounded-lg hover:bg-emerald-600 transition ml-auto mt-2 sm:mt-0">
                + Add Departement
            </a>
        </div>
    </div>

    <div class="p-6 overflow-x-auto">
        <table class="w-full min-w-[900px] text-sm border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 w-12">No</th>
                    <th class="px-4 py-3">Departement Code</th>
                    <th class="px-4 py-3">Departement Name</th>
                    <th class="px-4 py-3">Description</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center w-40">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y bg-white">
    @forelse ($items as $dept)
    <tr class="hover:bg-gray-50 transition">
        <td class="px-4 py-3 text-center">
            {{ $items->firstItem() + $loop->index }}
        </td>

        <td class="px-4 py-3 font-semibold text-gray-800">
            {{ $dept->code_dept }}
        </td>

        <td class="px-4 py-3 font-semibold text-gray-800">
            {{ $dept->departement_name }}
        </td>

        <td class="px-4 py-3 text-gray-600">
            {{ $dept->description ?? '-' }}
        </td>

        <td class="px-4 py-3 text-center">
            {{-- Jika is_active == 1 tampil Normal, jika 0 tampil Broken --}}
            <span class="px-3 py-1 rounded-full text-xs font-semibold
                {{ $dept->is_active == 1
                    ? 'bg-green-100 text-green-700'
                    : 'bg-red-100 text-red-700' }}">
                {{ $dept->is_active == 1 ? 'Normal' : 'Broken' }}
            </span>
        </td>

        <td class="px-4 py-3">
            <div class="flex justify-center gap-2">
                <a href="{{ route('admin.departement.edit', $dept->id) }}"
                   class="px-3 py-1.5 text-xs font-semibold bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Update
                </a>

                <form action="{{ route('admin.departement.destroy', $dept->id) }}"
                      method="POST"
                      onsubmit="return confirm('Delete this departement?')">
                    @csrf
                    @method('DELETE')
                    <button class="px-3 py-1.5 text-xs font-semibold bg-red-600 text-white rounded hover:bg-red-700 transition">
                        Delete
                    </button>
                </form>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">
            No items available
        </td>
    </tr>
    @endforelse
</tbody>
        </table>
    </div>

    <div class="p-6 border-t flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="text-sm text-gray-600">
            Showing
            <span class="font-semibold">{{ $items->firstItem() }}</span> –
            <span class="font-semibold">{{ $items->lastItem() }}</span> of
            <span class="font-semibold">{{ $items->total() }}</span> items
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ $items->previousPageUrl() ?? '#' }}"
               class="px-4 py-2 text-sm font-medium rounded-lg {{ $items->onFirstPage() ? 'bg-gray-200 text-gray-400 cursor-not-allowed pointer-events-none' : 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-100' }}">
                Previous
            </a>

            <a href="{{ $items->nextPageUrl() ?? '#' }}"
               class="px-4 py-2 text-sm font-medium rounded-lg {{ $items->hasMorePages() ? 'bg-white border border-gray-300 text-gray-700 hover:bg-gray-100' : 'bg-gray-200 text-gray-400 cursor-not-allowed pointer-events-none' }}">
                Next
            </a>
        </div>
    </div>
</div>

@endsection
