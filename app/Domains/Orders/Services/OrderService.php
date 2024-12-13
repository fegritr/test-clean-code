<?php 
namespace App\Domains\Orders\Services;

use App\Domains\Orders\Models\Orders;
use App\Domains\Inventory\Services\InventoryService;
use App\Domains\Products\Models\Products;

class OrderService
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function createOrder(int $productId, int $quantity)
    {
        $product = Products::findOrFail($productId);

        $totalPrice = $product->price * $quantity;

        $inventory = $this->inventoryService->getInventoryForProduct($productId);

        if ($inventory && $inventory->quantity >= $quantity) {
            $this->inventoryService->updateInventory($productId, $inventory->warehouse_id, $inventory->quantity - $quantity);

            $order = Orders::create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
            ]);

            return [
                'message' => 'Order created successfully.',
                'order' => $order,
                'success' => true,
            ];
        } else {

            return [
                'message' => 'Insufficient stock available. Only ' . ($inventory ? $inventory->quantity : 0) . ' items in stock.',
                'success' => false,
            ];
        }
    }

    /**
     * Cancel the order and update inventory.
     */
    public function cancelOrder(int $productId, int $quantity)
    {
        $inventory = $this->inventoryService->getInventoryForProduct($productId);

        if ($inventory) {
            $this->inventoryService->updateInventory($productId, $inventory->warehouse_id, $inventory->quantity + $quantity);
        }

        $order = Orders::create([
            'product_id' => $productId,
            'quantity' => $quantity,
            'total_price' => 0,    
        ]);

        return [
            'message' => 'Order has been canceled.',
            'order' => $order,
            'success' => true,
        ];
    }
}
