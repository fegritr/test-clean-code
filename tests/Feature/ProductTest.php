<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can get all products', function () {
    $result = app(\App\Domains\Products\Services\ProductService::class)->get();

    expect($result)->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

test('it can create product', function () {
    $data = new \App\Domains\Products\Data\ProductData(
        name: 'Product 1',
        description: 'Description 1',
        price: 100.00,
    );

    $result = app(\App\Domains\Products\Services\ProductService::class)->store($data);

    expect($result->name)->toBe('Product 1')
        ->and($result->description)->toBe('Description 1')
        ->and($result->price)->toBe(100.00);
});
