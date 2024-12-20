<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can get all warehouses', function () {
    $result = app(\App\Domains\Inventories\Services\WarehouseService::class)->get();

    expect($result)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('it can create warehouse', function () {
    $data = new \App\Domains\Inventories\Data\WarehouseData(
        name: 'Warehouse 1',
    );

    $result = app(\App\Domains\Inventories\Services\WarehouseService::class)->store($data);

    expect($result->name)->toBe('Warehouse 1');
});

test('it can get all inventories', function () {
    $result = app(\App\Domains\Inventories\Services\InventoryService::class)->get();

    expect($result)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('it can create inventory', function () {
    $product = \App\Models\Product::factory()->create();
    $warehouse = \App\Models\Warehouse::factory()->create();
    $data = new \App\Domains\Inventories\Data\InventoryData(
        product_id: $product->id,
        warehouse_id: $warehouse->id,
        stock: 10,
    );

    $result = app(\App\Domains\Inventories\Services\InventoryService::class)->store($data);

    expect($result->product_id)->toBe($product->id)
        ->and($result->warehouse_id)->toBe($warehouse->id)
        ->and($result->stock)->toBe(10);
});

test('it can update stock', function () {
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

    $orderData = new \App\Domains\Orders\Data\OrderData(
        product_id: $product->id,
        quantity: 12,
    );
    app(\App\Domains\Inventories\Services\InventoryService::class)->updatingStock($orderData);

    $inventory1->refresh();
    $inventory2->refresh();

    expect($inventory1->stock)->toBe(0)
        ->and($inventory2->stock)->toBe(8);
});

test('it can update stock with insufficient stock', function () {
    $product = \App\Models\Product::factory()->create();
    $warehouse = \App\Models\Warehouse::factory()->create();
    $inventory = \App\Models\Inventory::factory()->create([
        'product_id' => $product->id,
        'warehouse_id' => $warehouse->id,
        'stock' => 10,
    ]);

    $orderData = new \App\Domains\Orders\Data\OrderData(
        product_id: $product->id,
        quantity: 15,
    );

    $this->expectException(UnexpectedValueException::class);

    app(\App\Domains\Inventories\Services\InventoryService::class)->updatingStock($orderData);
});
