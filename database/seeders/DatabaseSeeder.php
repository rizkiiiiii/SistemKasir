<?php
namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat User (Admin & Kasir)
        User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@kopi.com',
            'role'     => 'admin',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name'     => 'Kasir Shift Pagi',
            'email'    => 'kasir@kopi.com',
            'role'     => 'cashier',
            'password' => Hash::make('password'),
        ]);

        // 2. Buat Kategori
        $catMinuman = Category::create(['name' => 'Minuman', 'slug' => 'minuman']);
        $catMakanan = Category::create(['name' => 'Makanan', 'slug' => 'makanan']);
        $catSnack   = Category::create(['name' => 'Snack', 'slug' => 'snack']);

        // 3. Buat Produk Dummy
        Product::create([
            'category_id' => $catMinuman->id,
            'name'        => 'Kopi Susu Gula Aren',
            'code'        => 'K001',
            'stock'       => 100, 
            'price'       => 18000,
        ]);

        Product::create([
            'category_id' => $catMinuman->id,
            'name'        => 'Americano',
            'code'        => 'K002',
            'stock'       => 50,
            'price'       => 15000,
        ]);

        Product::create([
            'category_id' => $catMakanan->id,
            'name'        => 'Nasi Goreng Spesial',
            'code'        => 'M001',
            'stock'       => 20,
            'price'       => 25000,
        ]);

        Product::create([
            'category_id' => $catSnack->id,
            'name'        => 'Kentang Goreng',
            'code'        => 'S001',
            'stock'       => 100,
            'price'       => 12000,
        ]);
    }
}
