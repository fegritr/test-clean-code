<?php

namespace App\Http\Controllers;

use App\Domains\Orders\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function createOrder(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $productId = $validated['product_id'];
        $quantity = $validated['quantity'];

        $order = $this->orderService->createOrder($productId, $quantity);

        return response()->json(['order' => $order], 201);
    }
}
