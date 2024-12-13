<?php 
namespace App\Console\Commands;

use App\Domains\Orders\Services\OrderService;
use Illuminate\Console\Command;

class CreateOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:create
                            {--product_id= : The ID of the product}
                            {--quantity= : The quantity of the product to order}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an order and update inventory';

    /**
     * The OrderService instance.
     *
     * @var \App\Domains\Orders\Services\OrderService
     */
    protected OrderService $orderService;

    /**
     * Create a new command instance.
     *
     * @param OrderService $orderService
     */
    public function __construct(OrderService $orderService)
    {
        parent::__construct();
        $this->orderService = $orderService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $productId = $this->option('product_id');
        $quantity = $this->option('quantity');

        if (!$productId || !$quantity) {
            $this->error('Please provide both product_id and quantity.');
            return 1;
        }

        if (!is_numeric($productId) || !is_numeric($quantity)) {
            $this->error('product_id and quantity must be numeric.');
            return 1;
        }

        try {
            $order = $this->orderService->createOrder($productId, $quantity);

            if (isset($order['success']) && !$order['success']) {
                $this->error($order['message']);
                return 1;
            }

            $this->info('Order created successfully!');
            $this->line('Order ID: ' . $order['order']->id);
            $this->line('Total Price: ' . $order['order']->total_price);

        } catch (\Exception $e) {
            $this->error('An error occurred while creating the order: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
