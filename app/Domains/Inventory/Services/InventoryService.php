<?php

namespace App\Domains\Inventory\Services;

use App\Domains\Inventory\Models\Inventory;

class InventoryService
{
    public function getInventoryForProduct(int $productId)
    {
        // Retrieve the inventory for the product
        return Inventory::where('product_id', $productId)->first();
    }

    public function updateInventory(int $productId, int $warehouseId, int $quantity)
    {
        // Mencari atau membuat data inventory baru untuk produk tersebut
        $inventory = Inventory::updateOrCreate(
            [
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
            ],
            [
                'quantity' => $quantity, // Update the quantity in the inventory
            ]
        );

        return $inventory;
    }
}
