<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('price', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('category_filter') && $request->category_filter != '') {
            $query->where('category_id', $request->category_filter);
        }

        $products = $query->latest()->get();
        $categories = Category::all();
        $categoryId = $request->category_id;
        $product = Product::when($categoryId, function ($queries) use ($categoryId) {
        $queries->where('category_id', $categoryId);})->get();
        $total = $product->sum('price');

        return view('dashboard', compact('products', 'categories', 'total', 'categoryId'));
    }
}
