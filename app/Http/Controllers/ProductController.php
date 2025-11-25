<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::latest()->get();
        $categories = Category::all();
        $categoryId = $request->category_id;
        $products = Product::when($categoryId, function ($query) use ($categoryId) {
        $query->where('category_id', $categoryId);})->get();
        $total = $products->sum('price');

        return view('products', compact('products', 'categories', 'total', 'categoryId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|integer|min:1',
            'description' => 'nullable|string|max:1000'
        ]);

        Product::create($validated);
        return redirect()->back()->with('success', 'Product added successfully');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'unit' => 'required|integer|min:1',
            'description' => 'nullable|string|max:1000'
        ]);

        $product->update($validated);
        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
