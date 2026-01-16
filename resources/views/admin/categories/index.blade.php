@extends('layouts.admin')

@section('title', 'Categories')

@section('content')

<div class="bg-white rounded-xl shadow-xl border">

    <!-- ===================== -->
    <!-- HEADER -->
    <!-- ===================== -->
    <div class="flex items-center justify-between px-6 py-4 rounded-t-xl
                bg-gradient-to-r from-blue-500 to-blue-600">

        <!-- Title -->
        <div>
            <h2 class="text-xl font-bold text-white">Categories</h2>
            <p class="text-sm text-indigo-100">
                Manage inventory item categories
            </p>
        </div>

        <!-- Add Button -->
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center justify-center gap-2
                  px-4 py-2
                  bg-emerald-500 text-white
                  font-semibold text-sm
                  rounded-lg
                  hover:bg-emerald-600
                  transition">
            + Add New Category
        </a>
    </div>

    <!-- ===================== -->
    <!-- TABLE -->
    <!-- ===================== -->
    <div class="p-6 overflow-x-auto">
        <table class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-800 text-white text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Category Name</th>
                    <th class="px-4 py-3 text-left">Description</th>
                    <th class="px-4 py-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y bg-white">
                @forelse ($categories as $index => $category)
                    <tr class="hover:bg-gray-100 transition">
                        <td class="px-4 py-3 font-semibold text-gray-700">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-4 py-3 font-bold text-gray-900">
                            {{ $category->name }}
                        </td>

                        <td class="px-4 py-3 text-gray-600">
                            {{ $category->description ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-3">

                                <!-- EDIT -->
                                <a href="{{ route('admin.categories.edit', $category->id) }}"
                                   class="inline-flex items-center gap-2
                                          px-4 py-2 text-sm font-bold
                                          bg-amber-400
                                          text-black
                                          rounded-lg
                                          shadow
                                          hover:bg-amber-500
                                          transition">
                                    Update
                                </a>

                                <!-- DELETE -->
                                <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this category?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="inline-flex items-center gap-2
                                               px-4 py-2 text-sm font-bold
                                               bg-red-600
                                               text-white
                                               rounded-lg
                                               shadow
                                               hover:bg-red-700
                                               transition">
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            class="text-center py-10 text-gray-500 italic">
                            No categories available
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

</div>

    </div>

</div>

@endsection
