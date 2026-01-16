<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Computer', 'description' => 'All types of computers']);
        Category::create(['name' => 'Printer', 'description' => 'Printer devices']);
        Category::create(['name' => 'Mouse', 'description' => 'Computer mice']);
        Category::create(['name' => 'Network', 'description' => 'Network devices like router and switch']);
    }
}
