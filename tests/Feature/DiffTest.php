<?php

test('everything is equal', function () {
    $response = $this->postJson('/api/diff', [
        'id' => 1,
        'title' => 'Title 1',
        'description' => 'Description 1',
        'images' => [
            [
                'id' => 1,
                'position' => 1,
                'url' => 'http://example.com/image.jpg',
            ]
        ],
        'variants' => [
            [
                'id' => 1,
                'sku' => 'sku1',
                'barcode' => 'barcode1',
                'image_id' => 1,
                'inventory_quantity' => 20,
            ]
        ]
    ]);

    $response->assertStatus(200);

    $response = $this->postJson('/api/diff', [
        'id' => 1,
        'title' => 'Title 1',
        'description' => 'Description 1',
        'images' => [
            [
                'id' => 1,
                'position' => 1,
                'url' => 'http://example.com/image.jpg',
            ]
        ],
        'variants' => [
            [
                'id' => 1,
                'sku' => 'sku1',
                'barcode' => 'barcode1',
                'image_id' => 1,
                'inventory_quantity' => 20,
            ]
        ]
    ]);

    $response->assertStatus(200);

    $response = $this->get('/api/diff');

    $response->assertStatus(200);

    $json = $response->json();

    expect($json)->toBeArray()
        ->and($json['title'])->toEqual('equal')
        ->and($json['description'])->toEqual('equal')
        ->and($json['images']['0']['position'])->toEqual('equal')
        ->and($json['images']['0']['url'])->toEqual('equal')
        ->and($json['variants']['0']['sku'])->toEqual('equal')
        ->and($json['variants']['0']['barcode'])->toEqual('equal')
        ->and($json['variants']['0']['image_id'])->toEqual('equal')
        ->and($json['variants']['0']['inventory_quantity'])->toEqual('equal');
});

test('everything is changed with same ids', function () {
    $response = $this->postJson('/api/diff', [
        'id' => 1,
        'title' => 'Title 1',
        'description' => 'Description 1',
        'images' => [
            [
                'id' => 1,
                'position' => 1,
                'url' => 'http://example.com/image.jpg',
            ]
        ],
        'variants' => [
            [
                'id' => 1,
                'sku' => 'sku1',
                'barcode' => 'barcode1',
                'image_id' => 1,
                'inventory_quantity' => 20,
            ]
        ]
    ]);

    $response->assertStatus(200);

    $response = $this->postJson('/api/diff', [
        'id' => 1,
        'title' => 'Title 2',
        'description' => 'Description 2',
        'images' => [
            [
                'id' => 1,
                'position' => 2,
                'url' => 'http://example.com/image2.jpg',
            ]
        ],
        'variants' => [
            [
                'id' => 1,
                'sku' => 'sku2',
                'barcode' => 'barcode2',
                'image_id' => 2,
                'inventory_quantity' => 30,
            ]
        ]
    ]);

    $response->assertStatus(200);

    $response = $this->get('/api/diff');

    $response->assertStatus(200);

    $json = $response->json();

    expect($json)->toBeArray()
        ->and($json['title'])->toEqual('changed')
        ->and($json['description'])->toEqual('changed')
        ->and($json['images']['0']['position'])->toEqual('changed')
        ->and($json['images']['0']['url'])->toEqual('changed')
        ->and($json['variants']['0']['sku'])->toEqual('changed')
        ->and($json['variants']['0']['barcode'])->toEqual('changed')
        ->and($json['variants']['0']['image_id'])->toEqual('changed')
        ->and($json['variants']['0']['inventory_quantity'])->toEqual('changed');
});

test('images and variants added', function () {
    $response = $this->postJson('/api/diff', [
        'id' => 1,
        'title' => 'Title 1',
        'description' => 'Description 1',
        'images' => [],
        'variants' => []
    ]);

    $response->assertStatus(200);

    $response = $this->postJson('/api/diff', [
        'id' => 1,
        'title' => 'Title 2',
        'description' => 'Description 2',
        'images' => [
            [
                'id' => 1,
                'position' => 2,
                'url' => 'http://example.com/image2.jpg',
            ]
        ],
        'variants' => [
            [
                'id' => 1,
                'sku' => 'sku2',
                'barcode' => 'barcode2',
                'image_id' => 2,
                'inventory_quantity' => 30,
            ]
        ]
    ]);

    $response->assertStatus(200);

    $response = $this->get('/api/diff');

    $response->assertStatus(200);

    $json = $response->json();

    expect($json)->toBeArray()
        ->and($json['images']['0']['position'])->toEqual('added')
        ->and($json['images']['0']['url'])->toEqual('added')
        ->and($json['variants']['0']['sku'])->toEqual('added')
        ->and($json['variants']['0']['barcode'])->toEqual('added')
        ->and($json['variants']['0']['image_id'])->toEqual('added')
        ->and($json['variants']['0']['inventory_quantity'])->toEqual('added');
});

test('images and variants removed', function () {
    $response = $this->postJson('/api/diff', [
        'id' => 1,
        'title' => 'Title 1',
        'description' => 'Description 1',
        'images' => [
            [
                'id' => 1,
                'position' => 2,
                'url' => 'http://example.com/image2.jpg',
            ]
        ],
        'variants' => [
            [
                'id' => 1,
                'sku' => 'sku2',
                'barcode' => 'barcode2',
                'image_id' => 2,
                'inventory_quantity' => 30,
            ]
        ]
    ]);

    $response->assertStatus(200);

    $response = $this->postJson('/api/diff', [
        'id' => 1,
        'title' => 'Title 2',
        'description' => 'Description 2',
        'images' => [],
        'variants' => []
    ]);

    $response->assertStatus(200);

    $response = $this->get('/api/diff');

    $response->assertStatus(200);

    $json = $response->json();

    expect($json)->toBeArray()
        ->and($json['images']['0']['position'])->toEqual('removed')
        ->and($json['images']['0']['url'])->toEqual('removed')
        ->and($json['variants']['0']['sku'])->toEqual('removed')
        ->and($json['variants']['0']['barcode'])->toEqual('removed')
        ->and($json['variants']['0']['image_id'])->toEqual('removed')
        ->and($json['variants']['0']['inventory_quantity'])->toEqual('removed');
});
