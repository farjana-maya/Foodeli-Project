<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Menu Item Configuration - NO LIMITATIONS
    |--------------------------------------------------------------------------
    |
    | This configuration defines the unlimited possibilities for menu items.
    | All fields are optional except name and price.
    |
    */

    'unlimited_features' => [
        'categories' => 'Any custom category allowed - no restrictions',
        'image_formats' => ['jpeg', 'png', 'jpg', 'gif', 'webp', 'bmp', 'svg'],
        'image_size_limit' => '10MB (10240KB)',
        'name_length' => '500 characters',
        'description_length' => '2000 characters',
        'price_range' => 'Any positive number with 2 decimal places',
        'preparation_time' => 'Any positive integer (minutes)',
        'bulk_operations' => true,
        'import_export' => ['csv', 'json'],
        'duplicate_items' => true,
    ],

    'flexible_fields' => [
        // Basic Information
        'name' => 'required|string|max:500',
        'description' => 'nullable|string|max:2000',
        'price' => 'required|numeric|min:0',
        'category' => 'nullable|string (unlimited custom categories)',
        'image' => 'nullable|image|max:10MB',
        
        // Availability & Features
        'is_available' => 'nullable|boolean',
        'is_featured' => 'nullable|boolean',
        'preparation_time' => 'nullable|integer|min:0',
        
        // Nutritional Information
        'ingredients' => 'nullable|array (unlimited ingredients)',
        'allergens' => 'nullable|array (unlimited allergens)',
        'calories' => 'nullable|integer|min:0',
        'nutritional_info' => 'nullable|array (protein, carbs, fat, fiber, etc.)',
        
        // Dietary & Preference Information
        'dietary_info' => 'nullable|array (vegan, vegetarian, gluten-free, keto, etc.)',
        'spice_level' => 'nullable|string (mild, medium, hot, extra hot, custom)',
        'portion_size' => 'nullable|string (small, medium, large, family, custom)',
        
        // Culinary Information
        'cooking_method' => 'nullable|string (grilled, fried, baked, steamed, etc.)',
        'origin_country' => 'nullable|string (Italian, Chinese, Mexican, etc.)',
        
        // Marketing & Organization
        'tags' => 'nullable|array (popular, chef-special, new, seasonal, etc.)',
        
        // Performance Metrics
        'rating' => 'decimal:2 (auto-calculated)',
        'total_reviews' => 'integer (auto-calculated)',
        'order_count' => 'integer (auto-calculated)',
    ],

    'example_menu_item' => [
        'name' => 'Grandma\'s Secret Recipe Lasagna',
        'description' => 'A family recipe passed down through generations, featuring layers of fresh pasta, rich meat sauce, creamy béchamel, and a blend of Italian cheeses.',
        'price' => 18.99,
        'category' => 'Italian Comfort Food',
        'preparation_time' => 25,
        'ingredients' => [
            'Fresh pasta sheets',
            'Ground beef and pork',
            'San Marzano tomatoes',
            'Mozzarella cheese',
            'Parmesan cheese',
            'Ricotta cheese',
            'Fresh basil',
            'Garlic',
            'Onions'
        ],
        'allergens' => ['gluten', 'dairy', 'eggs'],
        'calories' => 650,
        'dietary_info' => ['high-protein'],
        'spice_level' => 'mild',
        'portion_size' => 'large',
        'cooking_method' => 'baked',
        'origin_country' => 'Italy',
        'nutritional_info' => [
            'protein' => '35g',
            'carbohydrates' => '45g',
            'fat' => '28g',
            'fiber' => '4g',
            'sodium' => '890mg'
        ],
        'tags' => ['chef-special', 'family-favorite', 'comfort-food'],
        'is_available' => true,
        'is_featured' => true
    ],

    'api_endpoints' => [
        'basic_operations' => [
            'GET /api/owner/menu-items' => 'Get all menu items',
            'POST /api/owner/menu-items' => 'Add single menu item (unlimited fields)',
            'PUT /api/owner/menu-items/{id}' => 'Update menu item (unlimited fields)',
            'DELETE /api/owner/menu-items/{id}' => 'Delete menu item',
        ],
        'advanced_operations' => [
            'POST /api/owner/menu-items/bulk' => 'Add multiple menu items at once',
            'POST /api/owner/menu/import' => 'Import menu from CSV/JSON',
            'GET /api/owner/menu/export' => 'Export menu to CSV/JSON',
            'GET /api/owner/menu/stats' => 'Get comprehensive menu statistics',
            'POST /api/owner/menu-items/{id}/duplicate' => 'Duplicate existing menu item',
        ]
    ]
];