<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::latest()->get();
        $categories = Category::all();
        $categoryId = $request->category_id;
        $products = Product::when($categoryId, function ($query) use ($categoryId) {
        $query->where('category_id', $categoryId);})->get();
        $total = $products->sum('price');

        return view('dashboard', compact('products', 'categories', 'total', 'categoryId'));
    }
}
