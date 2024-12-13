<?php

namespace App\Domains\Products\Services;

use App\Domains\Products\Events\ProductCreated;
use App\Domains\Products\Models\Products;

class ProductService
{
    public function createProduct(array $data): Products
    {
        $product = Products::create($data);

        // Dispatch event
        ProductCreated::dispatch($product);

        return $product;
    }
}
