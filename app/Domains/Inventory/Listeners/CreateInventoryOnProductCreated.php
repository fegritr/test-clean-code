<?php

namespace App\Domains\Inventory\Listeners;

use App\Domains\Inventory\Services\InventoryService;
use App\Domains\Products\Events\ProductCreated;

class CreateInventoryOnProductCreated
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Handle the event.
     *
     * @param  ProductCreated  $event
     * @return void
     */
    public function handle(ProductCreated $event)
    {
        $product = $event->product;
        $warehouseId = $event->warehouseId;
        $stock = $event->stock;

        // Membuat inventory untuk produk yang baru dibuat
        $this->inventoryService->updateInventory($product->id, $warehouseId, $stock);
    }
}
