<?php

namespace App\Domains\Products\Events;

use App\Domains\Products\Models\Products;
use Illuminate\Foundation\Events\Dispatchable;

class ProductCreated
{
    use Dispatchable;

    public Products $product;
    public $warehouseId;
    public $stock;

    public function __construct(Products $product, int $warehouseId, int $stock)
    {
        $this->product = $product;
        $this->warehouseId = $warehouseId;
        $this->stock = $stock;
    }
}
