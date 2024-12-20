<?php

test('it can create product', function () {
    $mock = Mockery::mock('App\Models\Product');
    $mock->shouldReceive('getAttribute')->with('name')->andReturn('Product 1');
    $mock->shouldReceive('getAttribute')->with('description')->andReturn('Description 1');
    $mock->shouldReceive('getAttribute')->with('price')->andReturn(100.00);

    $mock->shouldReceive('create')
        ->once()
        ->with([
            'name' => 'Product 1',
            'description' => 'Description 1',
            'price' => 100.00,
        ])
        ->andReturn($mock);

    $data = new \App\Domains\Products\Data\ProductData(
        name: 'Product 1',
        description: 'Description 1',
        price: 100.00,
    );

    $service = new \App\Domains\Products\Services\ProductService($mock);

    $result = $service->store($data);

    expect($result->name)->toBe('Product 1')
        ->and($result->description)->toBe('Description 1')
        ->and($result->price)->toBe(100.00);
});

test('it can create from request', function () {
    $mock = Mockery::mock('App\Models\Product');
    $mock->shouldReceive('getAttribute')->with('name')->andReturn('Product 1');
    $mock->shouldReceive('getAttribute')->with('description')->andReturn('Description 1');
    $mock->shouldReceive('getAttribute')->with('price')->andReturn(100.00);

    $request = new \App\Http\Requests\ProductRequest([
        'name' => 'Product 1',
        'description' => 'Description 1',
        'price' => 100.00,
    ]);
    $data = \App\Domains\Products\Data\ProductData::fromRequest($request);

    $mock->shouldReceive('create')
        ->once()
        ->with([
            'name' => 'Product 1',
            'description' => 'Description 1',
            'price' => 100.00,
        ])
        ->andReturn($mock);

    $service = new \App\Domains\Products\Services\ProductService($mock);
    $result = $service->store($data);

    expect($result->name)->toBe('Product 1')
        ->and($result->description)->toBe('Description 1')
        ->and($result->price)->toBe(100.00);
});
