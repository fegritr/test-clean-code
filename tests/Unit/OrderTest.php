<?php

test('it can create order from request', function () {
    $mockProduct = Mockery::mock('App\Models\Product');
    $mockProduct->shouldReceive('getAttribute')->with('id')->andReturn(1);

    $request = new \App\Http\Requests\OrderRequest([
        'product_id' => $mockProduct->id,
        'quantity' => 1,
    ]);

    $data = \App\Domains\Orders\Data\OrderData::fromRequest($request);

    expect($data->product_id)->toBe($mockProduct->id)
        ->and($data->quantity)->toBe(1);
});
