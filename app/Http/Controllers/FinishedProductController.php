<?php

namespace App\Http\Controllers;

use App\Models\FinishedProduct;
use App\Models\Product;
use App\Models\SpareParts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DataTables\FinishedProductsDataTable;
use Illuminate\Validation\Rule;

class FinishedProductController extends Controller
{
    public function index(FinishedProductsDataTable $dataTable)
    {
        return $dataTable->render('finishedProducts.index');
    }

    public function create()
    {
        $products = Product::all();
        return view('finishedProducts.add-edit', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|integer|not_in:0', // positive or negative
        ]);

        $validated['created_by'] = Auth::id();

        try {
            DB::transaction(function () use ($validated) {
                $productId = $validated['product_id'];
                $qty       = $validated['qty'];

                // Get all spare parts needed for this product
                $spareParts = DB::table('product_spare_part')
                    ->where('product_id', $productId)
                    ->get();

                // If adding finished products (qty > 0), check stock
                if ($qty > 0) {
                    foreach ($spareParts as $part) {
                        $required = $part->qty * $qty;
                        $spare = SpareParts::find($part->spare_part_id);

                        if ($spare->qty < $required) {
                            throw new \Exception(
                                "Not enough spare part: {$spare->name}. Needed {$required}, available {$spare->qty}."
                            );
                        }
                    }
                }

                // Deduct or add back spare parts
                foreach ($spareParts as $part) {
                    $required = $part->qty * abs($qty);
                    $spare = SpareParts::find($part->spare_part_id);

                    if ($qty > 0) {
                        $spare->decrement('qty', $required);
                    } else {
                        $spare->increment('qty', $required);
                    }

                    // Check minimum qty
                    if (!is_null($spare->minimum_qty) && $spare->qty < $spare->minimum_qty) {
                        session()->flash('warning', "Spare part {$spare->name} below minimum qty!");
                    }
                }

                // Insert or update FinishedProduct
                $finishedProduct = FinishedProduct::where('product_id', $productId)
                    ->where('created_by', Auth::id())
                    ->first();

                if ($finishedProduct) {
                    $finishedProduct->qty += $qty;

                    if ($finishedProduct->qty <= 0) {
                        $finishedProduct->delete();
                    } else {
                        $finishedProduct->save();
                    }
                } else {
                    if ($qty > 0) {
                        FinishedProduct::create([
                            'product_id' => $productId,
                            'qty'        => $qty,
                            'created_by' => Auth::id()
                        ]);
                    }
                }

                // Clean up globally any <=0 qty
                FinishedProduct::where('qty', '<=', 0)->delete();
            });

            return redirect()->route('finishedProducts.index')
                ->with('success', 'Finished product saved successfully.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $finishedProduct = FinishedProduct::findOrFail($id);
        $products = Product::all();
        return view('finishedProducts.add-edit', compact('finishedProduct', 'products'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|integer|min:1',
        ]);

        $validated['created_by'] = Auth::id();

        FinishedProduct::where('id', $id)->update($validated);

        return redirect()->route('finishedProducts.index')
            ->with('success', 'Finished Product updated successfully.');
    }

    public function show($id)
    {
        $finishedProduct = FinishedProduct::with(['product', 'createdBy'])->findOrFail($id);

        return response()->json([
            'id' => $finishedProduct->id,
            'qty' => $finishedProduct->qty,
            'created_at' => $finishedProduct->created_at?->format('d M Y H:i'),
            'product' => $finishedProduct->product ? [
                'id' => $finishedProduct->product->id,
                'name' => $finishedProduct->product->name,
            ] : null,
            'created_by' => $finishedProduct->createdBy ? [
                'id' => $finishedProduct->createdBy->id,
                'name' => $finishedProduct->createdBy->name,
            ] : null,
        ]);
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $finishedProduct = FinishedProduct::findOrFail($id);

                // restore spare parts back
                $spareParts = DB::table('product_spare_part')
                    ->where('product_id', $finishedProduct->product_id)
                    ->get();

                foreach ($spareParts as $part) {
                    $toAdd = $part->qty * $finishedProduct->qty;
                    SpareParts::where('id', $part->spare_part_id)->increment('qty', $toAdd);
                }

                $finishedProduct->delete();
            });

            return redirect()->route('finishedProducts.index')
                ->with('success', 'Finished Product deleted and inventory restored.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
