<?php

namespace App\Domains\Products\Events;

use App\Domains\Products\Models\Products;
use Illuminate\Foundation\Events\Dispatchable;

class ProductCreated
{
    use Dispatchable;

    public Products $product;

    public function __construct(Products $product)
    {
        $this->product = $product;
    }
}
