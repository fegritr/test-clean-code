<?php

namespace App\Http\Controllers;

use App\Domains\Products\Services\ProductService;
use App\Http\Requests\CreateProductRequest;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function store(CreateProductRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $warehouseId = $request->input('warehouse_id');
        $stock = $request->input('stock');

        $product = $this->productService->createProduct($validated, $warehouseId, $stock);


        return response()->json(['message' => 'Product created', 'product' => $product], 201);
    }
}
