<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;

class ProductController extends Controller
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
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('product_photos', 'public');
            $validated['photo'] = $photoPath;
        }

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
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }

            $photoPath = $request->file('photo')->store('product_photos', 'public');
            $validated['photo'] = $photoPath;
        }

        $product->update($validated);
        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.trash')->with('success', 'Product deleted successfully.');
    }

    public function trash()
    {
        $products = Product::onlyTrashed()->with('category')->latest('deleted_at')->get();
        $categories = Category::all();

        return view('trash', compact('products', 'categories'));
    }

    public function restore ($id)
    {
        $product = Product::withTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('products.index')->with('success', 'Product restored successfully');
    }

    public function forceDelete($id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        if ($product->photo) {
            Storage::disk('public')->delete($product->photo);
        }

        $product->forceDelete();

        return redirect()->route('products.trash')->with('success', 'Product permanently deleted!');
    }

    public function export (Request $request)
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

        $product = $query->latest()->get();

        $filename = 'products_export_' . date('Y-m-d_His') . '.pdf';

        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Products Export</title>
            <style>
                body {
                    font-family: "Helvetica", Arial, sans-serif;
                    background: #f9fafb; /* light gray */
                    margin: 0;
                    padding: 30px;
                    color: #111827;
                }

                .container {
                    max-width: 1100px;
                    margin: auto;
                    background: #ffffff;
                    padding: 32px;
                    border-radius: 10px;
                    border: 1px solid #e5e7eb;
                }

                .header {
                    text-align: center;
                    margin-bottom: 30px;
                }

                .header h1 {
                    margin: 0;
                    font-size: 26px;
                    letter-spacing: 0.5px;
                    color: #1f2937; /* dark gray */
                }

                .header p {
                    margin-top: 8px;
                    font-size: 14px;
                    color: #6b7280;
                }

                .divider {
                    height: 2px;
                    background: #e5e7eb;
                    margin: 25px 0;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    font-size: 14px;
                }

                th {
                    background: #4f46e5; /* indigo */
                    color: #ffffff;
                    padding: 12px 10px;
                    text-align: left;
                    font-weight: 600;
                }

                td {
                    padding: 10px;
                    border-bottom: 1px solid #e5e7eb;
                    vertical-align: top;
                    color: #374151;
                }

                tr:nth-child(even) {
                    background: #f9fafb;
                }

                .badge {
                    display: inline-block;
                    padding: 4px 10px;
                    font-size: 12px;
                    border-radius: 999px;
                    background: #eef2ff; /* indigo soft */
                    color: #4338ca;
                    font-weight: 600;
                }

                .price {
                    font-weight: bold;
                    color: #047857; /* teal/green */
                }

                .stock {
                    font-weight: bold;
                    color: #0f766e;
                }

                .footer {
                    margin-top: 30px;
                    text-align: center;
                    font-size: 13px;
                    color: #6b7280;
                }

                @media print {
                    body {
                        background: white;
                        padding: 0;
                    }
                    .container {
                        border-radius: 0;
                        border: none;
                    }
                }
            </style>
        </head>
        <body>
            <div class="container">

                <div class="header">
                    <h1>Product Inventory Report</h1>
                    <p>
                        Exported on ' . date('F d, Y \\a\\t h:i A') . '<br>
                        Total Records: ' . $product->count() . '
                    </p>
                </div>

                <div class="divider"></div>

                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item Name / SKU</th>
                            <th>Category</th>
                            <th>Price / Unit</th>
                            <th>Stock</th>
                            <th>Description</th>
                            <th>Added</th>
                        </tr>
                    </thead>
                    <tbody>';

                $number = 1;
                foreach ($product as $prod) {
                    $html .= '<tr>
                        <td>' . $number++ . '</td>
                        <td>' . htmlspecialchars($prod->name) . '</td>
                        <td>
                            <span class="badge">' . htmlspecialchars($prod->category ? $prod->category->name : 'N/A') . '</span>
                        </td>
                        <td class="price">' . number_format($prod->price, 2) . '</td>
                        <td class="stock">' . htmlspecialchars($prod->unit) . '</td>
                        <td>' . htmlspecialchars($prod->description ?? '-') . '</td>
                        <td>' . $prod->created_at->format('Y-m-d H:i:s') . '</td>
                    </tr>';
                }

                $html .= '</tbody>
                    </table>
                    <div class="footer">
                        Total Products: ' . $product->count() . '<br/>
                        © ' . date('Y') . ' Inventory System. All rights reserved.
                    </div>
            </div>
        </body>
        </html>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream($filename, ['Attachment' => true]);
    }
}
