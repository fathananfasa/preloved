<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;



class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $category = Category::first();

    Product::create([
        'name' => 'Jaket Levi’s',
        'description' => 'Samba',
        'price_original' => 1500000,
        'stock' => 1,
        'status' => 'available',
        'category_id' => $category->id
    ]);
    }
}
