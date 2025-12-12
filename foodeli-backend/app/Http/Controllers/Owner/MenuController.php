<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    // Bulk add menu items
    public function bulkAddMenuItems(Request $request)
    {
        $user = $request->user();
        $restaurant = Restaurant::where('owner_id', $user->id)->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:500',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.description' => 'nullable|string|max:2000',
            'items.*.category' => 'nullable|string',
            'items.*.preparation_time' => 'nullable|integer|min:0',
            'items.*.ingredients' => 'nullable|array',
            'items.*.allergens' => 'nullable|array',
            'items.*.calories' => 'nullable|integer|min:0',
            'items.*.dietary_info' => 'nullable|array',
            'items.*.spice_level' => 'nullable|string|max:50',
            'items.*.portion_size' => 'nullable|string|max:50',
            'items.*.cooking_method' => 'nullable|string|max:100',
            'items.*.origin_country' => 'nullable|string|max:100',
            'items.*.nutritional_info' => 'nullable|array',
            'items.*.tags' => 'nullable|array',
            'items.*.is_available' => 'nullable|boolean',
            'items.*.is_featured' => 'nullable|boolean'
        ]);

        $createdItems = [];
        foreach ($request->items as $itemData) {
            $itemData['restaurant_id'] = $restaurant->id;
            $itemData['is_available'] = $itemData['is_available'] ?? true;
            $itemData['is_featured'] = $itemData['is_featured'] ?? false;
            
            $createdItems[] = MenuItem::create($itemData);
        }

        return response()->json([
            'message' => 'Menu items added successfully',
            'items' => $createdItems,
            'count' => count($createdItems)
        ]);
    }

    // Import menu from CSV/JSON
    public function importMenu(Request $request)
    {
        $user = $request->user();
        $restaurant = Restaurant::where('owner_id', $user->id)->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        $request->validate([
            'file' => 'required|file|mimes:csv,json,txt|max:5120', // 5MB max
            'format' => 'required|in:csv,json',
            'overwrite_existing' => 'nullable|boolean'
        ]);

        $file = $request->file('file');
        $format = $request->format;
        $overwriteExisting = $request->overwrite_existing ?? false;

        try {
            $content = file_get_contents($file->getPathname());
            $items = [];

            if ($format === 'json') {
                $data = json_decode($content, true);
                $items = $data['items'] ?? $data;
            } elseif ($format === 'csv') {
                $lines = explode("\n", $content);
                $headers = str_getcsv(array_shift($lines));
                
                foreach ($lines as $line) {
                    if (trim($line)) {
                        $values = str_getcsv($line);
                        $items[] = array_combine($headers, $values);
                    }
                }
            }

            if ($overwriteExisting) {
                MenuItem::where('restaurant_id', $restaurant->id)->delete();
            }

            $createdItems = [];
            foreach ($items as $itemData) {
                // Convert string arrays back to arrays for JSON fields
                foreach (['ingredients', 'allergens', 'dietary_info', 'nutritional_info', 'tags'] as $field) {
                    if (isset($itemData[$field]) && is_string($itemData[$field])) {
                        $itemData[$field] = json_decode($itemData[$field], true) ?: explode(',', $itemData[$field]);
                    }
                }

                $itemData['restaurant_id'] = $restaurant->id;
                $itemData['is_available'] = filter_var($itemData['is_available'] ?? true, FILTER_VALIDATE_BOOLEAN);
                $itemData['is_featured'] = filter_var($itemData['is_featured'] ?? false, FILTER_VALIDATE_BOOLEAN);
                
                $createdItems[] = MenuItem::create($itemData);
            }

            return response()->json([
                'message' => 'Menu imported successfully',
                'items' => $createdItems,
                'count' => count($createdItems)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error importing menu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Export menu to JSON/CSV
    public function exportMenu(Request $request)
    {
        $user = $request->user();
        $restaurant = Restaurant::where('owner_id', $user->id)->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        $format = $request->get('format', 'json');
        $menuItems = $restaurant->menuItems()->get()->toArray();

        if ($format === 'csv') {
            $headers = array_keys($menuItems[0] ?? []);
            $csv = implode(',', $headers) . "\n";
            
            foreach ($menuItems as $item) {
                $row = [];
                foreach ($headers as $header) {
                    $value = $item[$header];
                    if (is_array($value)) {
                        $value = json_encode($value);
                    }
                    $row[] = '"' . str_replace('"', '""', $value) . '"';
                }
                $csv .= implode(',', $row) . "\n";
            }

            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="menu_export.csv"');
        }

        return response()->json([
            'restaurant' => $restaurant->name,
            'exported_at' => now(),
            'items' => $menuItems
        ])->header('Content-Disposition', 'attachment; filename="menu_export.json"');
    }

    // Get menu statistics
    public function getMenuStats(Request $request)
    {
        $user = $request->user();
        $restaurant = Restaurant::where('owner_id', $user->id)->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        $menuItems = $restaurant->menuItems();
        
        $stats = [
            'total_items' => $menuItems->count(),
            'available_items' => $menuItems->where('is_available', true)->count(),
            'featured_items' => $menuItems->where('is_featured', true)->count(),
            'categories' => $menuItems->distinct('category')->pluck('category')->filter()->values(),
            'avg_price' => $menuItems->avg('price'),
            'price_range' => [
                'min' => $menuItems->min('price'),
                'max' => $menuItems->max('price')
            ],
            'avg_rating' => $menuItems->avg('rating'),
            'total_orders' => $menuItems->sum('order_count'),
            'dietary_options' => $menuItems->whereNotNull('dietary_info')->get()
                ->pluck('dietary_info')->flatten()->unique()->values()
        ];

        return response()->json(['stats' => $stats]);
    }

    // Duplicate menu item
    public function duplicateMenuItem(Request $request, $id)
    {
        $user = $request->user();
        $restaurant = Restaurant::where('owner_id', $user->id)->first();

        if (!$restaurant) {
            return response()->json(['message' => 'Restaurant not found'], 404);
        }

        $originalItem = MenuItem::where('restaurant_id', $restaurant->id)->findOrFail($id);
        
        $duplicateData = $originalItem->toArray();
        unset($duplicateData['id'], $duplicateData['created_at'], $duplicateData['updated_at']);
        
        $duplicateData['name'] = $duplicateData['name'] . ' (Copy)';
        $duplicateData['image'] = null; // Don't duplicate image
        
        $duplicateItem = MenuItem::create($duplicateData);

        return response()->json([
            'message' => 'Menu item duplicated successfully',
            'original' => $originalItem,
            'duplicate' => $duplicateItem
        ]);
    }
}