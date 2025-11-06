<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SpareParts; // plural model name
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\DataTables\ProductsDataTable;

class ProductController extends Controller
{
    /**
     * Display products listing using DataTable
     */
    public function index(ProductsDataTable $dataTable)
    {
        return $dataTable->render('products.index');
    }

    /**
     * Show create form
     */
    public function create()
    {
        $spareParts = SpareParts::all();
        return view('products.add-edit', compact('spareParts'));
    }

    /**
     * Store a new product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255|unique:products,name',
            'valve_type' => 'nullable|string',
            'product_code' => 'nullable|numeric',
            'actuation' => 'nullable|string',
            'pressure_rating' => 'nullable|string',
            'valve_size' => 'nullable|string',
            'valve_size_rate' => 'nullable|string|in:inch,centimeter',
            'media' => 'nullable|string',
            'flow' => 'nullable|string',
            'sku_code' => 'nullable|string',
            'mrp' => 'nullable|string',
            'media_temperature' => 'nullable|string',
            'media_temperature_rate' => 'nullable|string|in:CELSIUS,FAHRENHEIT',
            'body_material' => 'nullable|string',
            'hsn_code' => 'nullable|string',
            'primary_material_of_construction' => 'nullable|string',

            // Spare parts arrays
            'spare_parts'   => 'required|array',
            'spare_parts.*' => 'integer|exists:spare_parts,id',
            'qty'           => 'required|array',
            'qty.*'         => 'numeric|min:1',
        ]);

        // Create product without spare parts data
        $productData = collect($validated)->except(['spare_parts', 'qty'])->toArray();
        $product = Product::create($productData);

        // Attach spare parts with quantities
        foreach ($request->spare_parts as $index => $sparePartId) {
            $product->spareParts()->attach($sparePartId, [
                'qty' => $request->qty[$index] ?? 0,
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Product added successfully.');
    }

    /**
     * Show edit form
     */
    public function edit(Product $product)
    {
        $spareParts = SpareParts::all();
        $product->load('spareParts');

        return view('products.add-edit', compact('product', 'spareParts'));
    }

    /**
     * Update product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('products', 'name')->ignore($product->id),
            ],
            'valve_type' => 'nullable|string',
            'product_code' => 'nullable|numeric',
            'actuation' => 'nullable|string',
            'pressure_rating' => 'nullable|string',
            'valve_size' => 'nullable|string',
            'valve_size_rate' => 'nullable|string|in:inch,centimeter',
            'media' => 'nullable|string',
            'flow' => 'nullable|string',
            'sku_code' => 'nullable|string',
            'mrp' => 'nullable|string',
            'media_temperature' => 'nullable|string',
            'media_temperature_rate' => 'nullable|string|in:CELSIUS,FAHRENHEIT',
            'body_material' => 'nullable|string',
            'hsn_code' => 'nullable|string',
            'primary_material_of_construction' => 'nullable|string',

            // Spare parts arrays
            'spare_parts'   => 'required|array',
            'spare_parts.*' => 'integer|exists:spare_parts,id',
            'qty'           => 'required|array',
            'qty.*'         => 'numeric|min:1',
        ]);

        // Update product fields
        $productData = collect($validated)->except(['spare_parts', 'qty'])->toArray();
        $product->update($productData);

        // Sync spare parts quantities
        $syncData = [];
        foreach ($request->spare_parts as $index => $sparePartId) {
            $syncData[$sparePartId] = ['qty' => $request->qty[$index] ?? 0];
        }
        $product->spareParts()->sync($syncData);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Show product detail
     */
    public function show(Product $product)
    {
        $product->load('spareParts');

        if (request()->ajax()) {
            return response()->json($product);
        }

        return view('products.show', compact('product'));
    }

    /**
     * Delete a product
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * Copy product (duplicate)
     */
    public function copy($id)
    {
        $originalProduct = Product::with('spareParts')->findOrFail($id);

        // Clone product
        $newProduct = $originalProduct->replicate();
        $newProduct->name = $originalProduct->name . ' (Copy)';
        $newProduct->sku_code = $this->generateNewSKUCode($originalProduct->sku_code);
        $newProduct->save();

        // Clone spare parts pivot data
        $pivotData = [];
        foreach ($originalProduct->spareParts as $sparePart) {
            $pivotData[$sparePart->id] = ['qty' => $sparePart->pivot->qty];
        }
        $newProduct->spareParts()->sync($pivotData);

        return redirect()->route('products.edit', $newProduct->id)
            ->with('success', 'Product copied successfully. You can now edit the copy.');
    }

    /**
     * Generate a new SKU code for copied products
     */
    private function generateNewSKUCode($originalSKU)
    {
        if (preg_match('/(.*?)(\\d+)?$/', $originalSKU, $matches)) {
            $base = $matches[1];
            $number = isset($matches[2]) ? intval($matches[2]) : 0;
            return $base . ($number + 1);
        }
        return $originalSKU . '-1';
    }
}
