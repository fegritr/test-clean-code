<?php

namespace App\Domains\Products\Events;

use App\Domains\Products\Models\Product;
use Illuminate\Foundation\Events\Dispatchable;

class ProductCreated
{
    use Dispatchable;

    public Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
