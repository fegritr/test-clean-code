<?php

namespace App\Domains\Products\Services;

use App\Domains\Products\Models\Product;
use App\Domains\Products\Events\ProductCreated;

class ProductService
{
    public function createProduct(array $data): Product
    {
        $product = Product::create($data);

        // Dispatch event
        ProductCreated::dispatch($product);

        return $product;
    }
}
