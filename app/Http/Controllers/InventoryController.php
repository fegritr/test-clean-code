<?php

namespace App\Http\Controllers;

use App\Domains\Inventory\Services\InventoryService;
use App\Http\Requests\UpdateInventoryRequest;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Update the inventory of a product.
     */
    public function update(UpdateInventoryRequest $request, $productId)
    {
        $validated = $request->validated();

        // Memanggil service untuk memperbarui inventory
        $this->inventoryService->updateInventory($productId, $validated['warehouse_id'], $validated['quantity']);

        return response()->json(['message' => 'Inventory updated successfully']);
    }
}
