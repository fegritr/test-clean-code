<?php

test('it can create warehouse from request', function () {
    $request = new \App\Http\Requests\WarehouseCreateRequest([
        'name' => 'Warehouse 1',
    ]);

    $data = \App\Domains\Inventories\Data\WarehouseData::fromRequest($request);

    expect($data->name)->toBe('Warehouse 1');
});

test('it can create inventory from request', function () {
    $mockProduct = Mockery::mock('App\Models\Product');
    $mockProduct->shouldReceive('getAttribute')->with('id')->andReturn(1);

    $mockWarehouse = Mockery::mock('App\Models\Warehouse');
    $mockWarehouse->shouldReceive('getAttribute')->with('id')->andReturn(1);

    $mock = Mockery::mock('App\Models\Inventory');
    $mock->shouldReceive('getAttribute')->with('product_id')->andReturn($mockProduct->id);
    $mock->shouldReceive('getAttribute')->with('warehouse_id')->andReturn($mockWarehouse->id);
    $mock->shouldReceive('getAttribute')->with('stock')->andReturn(10);

    $request = new \App\Http\Requests\InventoryRequest([
        'product_id' => $mockProduct->id,
        'warehouse_id' => $mockWarehouse->id,
        'stock' => $mock->stock,
    ]);
    $data = \App\Domains\Inventories\Data\InventoryData::fromRequest($request);

    $mock->shouldReceive('create')
        ->once()
        ->with([
            'product_id' => $mockProduct->id,
            'warehouse_id' => $mockWarehouse->id,
            'stock' => $mock->stock,
        ])
        ->andReturn($mock);

    $service = new \App\Domains\Inventories\Services\InventoryService($mock);
    $result = $service->store($data);

    expect($result->product_id)->toBe($mockProduct->id)
        ->and($result->warehouse_id)->toBe($mockWarehouse->id)
        ->and($result->stock)->toBe($mock->stock);
});
