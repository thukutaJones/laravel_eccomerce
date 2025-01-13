<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['user', 'category'])->get();
        return response()->json($products);
    }

    public function getProductsByUserId($id)
    {
        $products = Product::where('user_id', $id)->get();

        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::with(['user', 'category'])->findOrFail($id);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255', 
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);
    
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('images', 'public');
        } else {
            return response()->json(['error' => 'Invalid image file.'], 400);
        }
    
        $product = Product::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'category_id' => $request->category_id,
        ]);
    
        return response()->json($product, 201);
    }
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if ($request->has('name')) {
            $product->name = $request->name;
        }
        if ($request->has('description')) {
            $product->description = $request->description;
        }
        if ($request->has('price')) {
            $product->price = $request->price;
        }
        if ($request->has('category_id')) {
            $product->category_id = $request->category_id;
        }
        $product->save();

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        Storage::disk('public')->delete($product->image);
        $product->delete();

        return response()->json(null, 204);
    }
}