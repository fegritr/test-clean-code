<?php

namespace App\Domains\Products\Services;

use App\Domains\Products\Events\ProductCreated;
use App\Domains\Products\Models\Products;

class ProductService
{
    public function createProduct(array $data, int $warehouseId, int $stock): Products
    {
        $product = Products::create($data);

        event(new ProductCreated($product,$warehouseId, $stock));

        return $product;
    }
}
