@extends('layouts.admin')

@section('title', 'Units')

@section('content')

<div class="bg-white rounded-xl shadow-sm border border-gray-200">

    <!-- ===================== -->
    <!-- HEADER -->
    <!-- ===================== -->
    <div class="flex items-center justify-between px-6 py-4 rounded-t-xl bg-gradient-to-r from-gray-700 to-gray-800">
        <div>
            <h2 class="text-xl font-semibold text-black">Units</h2>
            <p class="text-sm text-gray-500">Master data inventory Units</p>
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

            <a href="{{ route('admin.units.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-500 text-white font-semibold text-sm rounded-lg hover:bg-emerald-600 transition ml-auto mt-2 sm:mt-0">
                + Add Units
            </a>
        </div>
    </div>

    <!-- ===================== -->
    <!-- TABLE -->
    <!-- ===================== -->
    <div class="overflow-x-auto">
        @if ($units->count() > 0)
            <table class="w-full text-sm">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">Unit Name</th>
                        <th class="px-6 py-4 text-left font-bold text-gray-700 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-4 text-center font-bold text-gray-700 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse ($units as $unit)
                        <tr class="hover:bg-blue-50 transition duration-150">
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                {{ ($units->currentPage() - 1) * $units->perPage() + $loop->iteration }}
                            </td>

                            <td class="px-6 py-4 font-semibold text-gray-700 bg-blue-50 rounded">
                                {{ $unit->code }}
                            </td>

                            <td class="px-6 py-4 font-semibold text-gray-900">
                                {{ $unit->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate">
                                {{ $unit->description ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.units.edit', $unit->id) }}"
                                    class="px-3 py-1.5 text-xs font-semibold bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                        Update
                                    </a>

                                    <form action="{{ route('admin.units.destroy', $unit->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this unit?')">
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
                            <td colspan="5" class="text-center py-16 text-gray-500">
                                <p class="text-lg mb-4">No units available</p>
                                <a href="{{ route('admin.units.create') }}" class="text-gray-700 hover:underline font-semibold">Create one now</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- ===================== -->
            <!-- PAGINATION -->
            <!-- ===================== -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between items-center rounded-b-xl">
                <p class="text-sm text-gray-600">
                    Showing <span class="font-bold">{{ ($units->currentPage() - 1) * $units->perPage() + 1 }}</span> 
                    to <span class="font-bold">{{ min($units->currentPage() * $units->perPage(), $units->total()) }}</span> 
                    of <span class="font-bold">{{ $units->total() }}</span> units
                </p>
                
                <div>
                    {{ $units->links('pagination::tailwind') }}
                </div>
            </div>
        @else
            <div class="text-center py-16">
                <p class="text-gray-500 text-lg mb-4">No units found</p>
                <a href="{{ route('admin.units.create') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                    Create First Unit
                </a>
            </div>
        @endif
    </div>

</div>

@endsection

