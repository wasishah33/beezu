<?php
return  [
    // Required fields
    'auth_protected' => true,
    'run_migration' => true,
    'table_name' => 'products',
    'model_name' => 'Product',

    // Optional fields (will be auto-generated if not provided)
    'controller_name' => 'Product',       // Default: model_name + 'Controller'
    'resource' => 'Product',            // Default: same as model_name
    'resource_name' => 'product',             // Default: lowercase model_name
    'resource_plural' => 'product',          // Default: pluralized resource_name
    'display_name' => 'Product',              // Default: ucfirst resource_name
    'display_name_plural' => 'Products',      // Default: ucfirst resource_plural

    // Additional options
    'timestamps' => true,                   // Add created_at/updated_at fields
    'per_page' => 15,                      // Items per page in listing
    'run_migration' => true,               // Run migration after generating
    'list_fields' => ['name', 'price', 'stock', 'status'],
    'fields' => [
        [
            'name' => 'name',
            'type' => 'string',
            'length' => 255,
            'nullable' => false,
            'searchable' => true,
            'label' => 'Product Name'
        ],
        [
            'name' => 'description',
            'type' => 'text',
            'nullable' => true,
            'searchable' => true,
            'label' => 'Product Description'
        ],
        [
            'name' => 'price',
            'type' => 'decimal',
            'length' => '10,2',
            'nullable' => false,
            'min' => 0,
            'label' => 'Price'
        ],
        [
            'name' => 'discount_price',
            'type' => 'decimal',
            'length' => '10,2',
            'nullable' => true,
            'min' => 0,
            'label' => 'Discount Price'
        ],
        [
            'name' => 'stock',
            'type' => 'integer',
            'nullable' => false,
            'default' => 0,
            'min' => 0,
            'label' => 'Stock Quantity'
        ],
        [
            'name' => 'sku',
            'type' => 'string',
            'length' => 100,
            'nullable' => true,
            'unique' => true,
            'label' => 'SKU Code'
        ],
        [
            'name' => 'category',
            'type' => 'string',
            'length' => 100,
            'nullable' => true,
            'index' => true,
            'label' => 'Category'
        ],
        [
            'name' => 'status',
            'type' => 'enum',
            'options' => ['active', 'inactive', 'out_of_stock'],
            'default' => 'active',
            'nullable' => false,
            'index' => true,
            'label' => 'Product Status'
        ],
        [
            'name' => 'weight',
            'type' => 'float',
            'nullable' => true,
            'min' => 0,
            'label' => 'Weight (kg)'
        ],
        [
            'name' => 'images',
            'type' => 'json',
            'nullable' => true,
            'label' => 'Product Images'
        ],
        [
            'name' => 'specifications',
            'type' => 'json',
            'nullable' => true,
            'label' => 'Product Specifications'
        ],
        [
            'name' => 'is_featured',
            'type' => 'boolean',
            'default' => false,
            'nullable' => false,
            'label' => 'Featured Product'
        ]
    ]
];
