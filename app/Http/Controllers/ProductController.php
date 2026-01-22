<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Tampilkan daftar produk (Admin View)
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        return view('products.index', compact('products'));
    }

    // Tampilkan Form Tambah Produk
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    // Proses Simpan ke Database
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'code'        => 'required|unique:products,code',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi gambar
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            // Simpan gambar ke folder 'public/products'
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name'        => $request->name,
            'category_id' => $request->category_id,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'code'        => $request->code,
            'image'       => $imagePath,
        ]);

        return redirect()->route('products.index')->with('success', 'Menu baru berhasil ditambahkan!');
    }
}
