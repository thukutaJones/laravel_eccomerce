<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('product')->get();
        return response()->json($orders);
    }

    public function getCartByUserId($id)
    {
        $products = Order::where('user_id', $id)->with('product')->get();

        return response()->json($products);
    }

    public function getOrdersByProductUserId($userId)
    {
        $orders = Order::whereHas('product', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with('product')->get();

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $order = new Order();
        $order->user_id = $request->user_id;
        $order->product_id = $request->product_id;
        $order->quantity = $request->quantity;
        $order->status = 'pending';
        $order->save();

        return response()->json($order, 201);
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'integer|min:1',
            'status' => 'string',
        ]);

        $order = Order::findOrFail($id);
        if ($request->has('quantity')) {
            $order->quantity = $request->quantity;
        }
        if ($request->has('status')) {
            $order->status = $request->status;
        }
        $order->save();

        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(null, 204);
    }
}
