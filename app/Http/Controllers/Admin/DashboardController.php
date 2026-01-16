<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;


class DashboardController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.dashboard', compact('categories'));
    }
     public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string'
        ]);

        Category::create($request->only('name', 'description'));

        return redirect()->route('admin.dashboard')
            ->with('success', 'Category added successfully');
    }
}
