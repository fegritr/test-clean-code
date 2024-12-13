<?php 
namespace App\Console\Commands;

use App\Domains\Products\Services\ProductService;
use Illuminate\Console\Command;

class AddProductCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:add 
                            {--name= : The name of the product} 
                            {--price= : The price of the product} 
                            {--warehouse_id= : The ID of the warehouse} 
                            {--stock= : The stock quantity of the product} 
                            {--category_id= : The category ID of the product}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new product with its associated inventory';

    /**
     * The ProductService instance.
     *
     * @var \App\Domains\Products\Services\ProductService
     */
    protected ProductService $productService;

    /**
     * Create a new command instance.
     *
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        parent::__construct();
        $this->productService = $productService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the input parameters
        $name = $this->option('name');
        $price = $this->option('price');
        $warehouseId = $this->option('warehouse_id');
        $stock = $this->option('stock');
        $categoryId = $this->option('category_id');

        // Validate inputs
        if (!$name || !$price || !$warehouseId || !$stock || !$categoryId) {
            $this->error('Please provide all required parameters: name, price, warehouse_id, stock, and category_id.');
            return 1;
        }

        if (!is_numeric($price) || !is_numeric($stock) || !is_numeric($categoryId)) {
            $this->error('Price, stock, and category_id must be numeric.');
            return 1;
        }

        try {
            // Prepare the product data
            $data = [
                'name' => $name,
                'price' => $price,
                'category_id' => $categoryId,
            ];

            // Call the ProductService to create the product and inventory
            $product = $this->productService->createProduct($data, $warehouseId, $stock);

            // Provide feedback
            $this->info('Product added successfully!');
            $this->line('Product ID: ' . $product->id);
            $this->line('Name: ' . $product->name);
            $this->line('Price: ' . $product->price);
            $this->line('Stock: ' . $stock);
        } catch (\Exception $e) {
            // Handle errors
            $this->error('An error occurred while adding the product: ' . $e->getMessage());
        }

        return 0;  // Return success
    }
}
