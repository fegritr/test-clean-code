<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can get all orders', function () {
    $result = app(\App\Domains\Orders\Services\OrderService::class)->get();

    expect($result)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('it can create order', function () {
    $product = \App\Models\Product::factory()->create();
    $inventory1 = \App\Models\Inventory::factory()->create([
        'product_id' => $product->id,
        'warehouse_id' => \App\Models\Warehouse::factory()->create()->id,
        'stock' => 10,
    ]);
    $inventory2 = \App\Models\Inventory::factory()->create([
        'product_id' => $product->id,
        'warehouse_id' => \App\Models\Warehouse::factory()->create()->id,
        'stock' => 10,
    ]);
    $data = new \App\Domains\Orders\Data\OrderData(
        product_id: $product->id,
        quantity: 15,
    );

    try {
        $result = app(\App\Domains\Orders\Services\OrderService::class)->store($data);
        expect($result->product_id)->toBe($product->id)
            ->and($result->quantity)->toBe(15)
            ->and($inventory1->refresh()->stock)->toBe(0)
            ->and($inventory2->refresh()->stock)->toBe(5);
    } catch (\Exception $e) {
        expect($e->getMessage())->toBeNull();
    }
});

test('it cannot create order with insufficient stock', function () {
    $product = \App\Models\Product::factory()->create();
    $inventory1 = \App\Models\Inventory::factory()->create([
        'product_id' => $product->id,
        'warehouse_id' => \App\Models\Warehouse::factory()->create()->id,
        'stock' => 10,
    ]);
    $inventory2 = \App\Models\Inventory::factory()->create([
        'product_id' => $product->id,
        'warehouse_id' => \App\Models\Warehouse::factory()->create()->id,
        'stock' => 10,
    ]);
    $data = new \App\Domains\Orders\Data\OrderData(
        product_id: $product->id,
        quantity: 25,
    );

    try {
        app(\App\Domains\Orders\Services\OrderService::class)->store($data);
    } catch (\Exception $e) {
        expect($e->getMessage())->toBe('Insufficient stock! Exist only:20 but required:25');
    }
});
