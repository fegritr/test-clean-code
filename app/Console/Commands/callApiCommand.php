<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CallApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'api:post-products
                            {--name= : The name of the product} 
                            {--price= : The price of the product}';

    /**
     * The console command description.
     */
    protected $description = 'Send a POST request to create a product via an API';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Get options from the command
        $name = $this->option('name');
        $price = $this->option('price');

        // Validate the input
        if (!$name || !$price || !is_numeric($price)) {
            $this->error('Invalid input. Make sure to provide a valid --name and --price.');
            return 1;
        }

        // Define the API endpoint
        $endpoint = 'http://127.0.0.1:8000/api/products'; // Replace with actual API endpoint

        try {
            // Send POST request
            $response = Http::post($endpoint, [
                'name' => $name,
                'price' => $price,
            ]);

            // Handle response
            if ($response->successful()) {
                $this->info('Product created successfully:');
                $this->line($response->body());
            } else {
                $this->error("Failed to create product. HTTP Status: {$response->status()}");
                $this->line($response->body());
            }
        } catch (\Exception $e) {
            // Handle exceptions
            $this->error('An error occurred while calling the API:');
            $this->error($e->getMessage());
        }

        return 0;
    }
}

