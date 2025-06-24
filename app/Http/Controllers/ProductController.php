<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    // Display product management page
    public function index(Request $request)
    {
        $query = Product::withCount('rentals')->latest();
        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('product_code', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%");
            });
        }
        $products = $query->paginate(10)->appends(['search' => $search]);
        $totalRentalCount = Rental::count();
        return view('products.index', compact('products', 'totalRentalCount', 'search'));
    }

    // Show create product form
    public function create()
    {
        return view('products.create');
    }

    // Store new product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_code' => 'required|unique:products,product_code|max:50',
            'name' => 'required|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'purchase_price' => 'required|numeric|min:0',
            'rental_price' => 'required|numeric|min:0',
            'deposit_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Sản phẩm đã được thêm thành công!');
    }

    // Show edit product form
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // Update product
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_code' => ['required', Rule::unique('products')->ignore($product->id), 'max:50'],
            'name' => 'required|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'purchase_price' => 'required|numeric|min:0',
            'rental_price' => 'required|numeric|min:0',
            'deposit_price' => 'required|numeric|min:0',
            'purchase_date' => 'required|date',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    // Delete product
    public function destroy(Product $product)
    {
        // Check if product is currently rented
        if ($product->activeRental()) {
            return back()->with('error', 'Không thể xóa sản phẩm đang được thuê!');
        }

        // Delete image
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Sản phẩm đã được xóa thành công!');
    }

    // Search product by code or name (for rental cart)
    public function searchByCode(Request $request)
    {
        $searchTerm = $request->get('search');
        if (!$searchTerm) {
            return response()->json([]);
        }

        $products = Product::select('id', 'product_code', 'name', 'rental_price', 'deposit_price', 'status', 'image')
            ->where(function($query) use ($searchTerm) {
                $query->where('product_code', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('name', 'LIKE', "%{$searchTerm}%");
            })
            ->limit(10)
            ->get();

        return response()->json($products);
    }
} 