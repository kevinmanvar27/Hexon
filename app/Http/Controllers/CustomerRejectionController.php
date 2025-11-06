<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\SpareParts;
use App\Models\CustomerRejection;
use App\Models\CustomerRejectionSparePart;
use App\Models\Product;
use App\DataTables\CustomerRejectionsDataTable;

class CustomerRejectionController extends Controller
{
    public function index(CustomerRejectionsDataTable $dataTable)
    {
        return $dataTable->render('rejections.customer-rejection');
    }

    public function create(Request $request)
    {
        $customers = Customer::all();
        $products = Product::all();
        $spareParts = collect(); 
        if ($request->has('product_id') && $request->input('product_id') != null)
        {
            $productID = $request->input('product_id');
            $product = Product::find($productID);
            
            if ($product)
            {
                $spareParts = $product->spareParts;
                $productID = $productID;
                $typeSearch = $request->input('type');
                $quantitySearch = $request->input('quantity');
            }
        }
        return view('rejections.add-customer-rejection', compact('customers', 'products', 'spareParts' )); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'productId' => 'required|exists:products,id',
            'customer_id' => 'required|exists:customers,id',
            'quantity' => 'required|integer|min:1',
            'spare_parts' => 'required|array',
            'spare_parts.*.id' => 'required|exists:spare_parts,id',
            'spare_parts.*.type' => 'required|string',
            'spare_parts.*.size' => 'required|string',
            'spare_parts.*.weight' => 'required|numeric',
            'spare_parts.*.quantity' => 'required|integer|min:0',
            'note' => 'nullable|string',
        ]);

        $sparePartsInput = $request->input('spare_parts');

        $rejection = CustomerRejection::create([
            'customer_id' => $request->input('customer_id'),
            'product_id'  => $request->input('productId'),
            'quantity'    => $request->input('quantity'),
            'note'        => $request->input('note'),
        ]);
        
        foreach ($sparePartsInput as $part) {
            $sparePartModel = SpareParts::find($part['id']);
            if ($sparePartModel) {
                $rejectedQty = $part['quantity'];
                if ($part['quantity'] < $request->input('quantity')) {
                    $sparePartModel->qty += $request->input('quantity') - $part['quantity'];
                }
                $sparePartModel->save();

                CustomerRejectionSparePart::create([
                    'customer_rejection_id' => $rejection->id,
                    'spare_part_id' => $part['id'],
                    'type' => $part['type'],
                    'size' => $part['size'],
                    'weight' => $part['weight'],
                    'quantity' => $rejectedQty,
                ]);
            }
        }

        return redirect()->route('customerRejections.index')
                        ->with('success', 'Customer Rejection saved. Rejected quantities added back to spare parts stock.');
    }

    public function show($id)
    {
        $rejection = CustomerRejection::with('spareParts')->findOrFail($id);

        $rejection->spareParts->transform(function($part) {
            $part->name = $part->sparePart->name ?? 'N/A';
            return $part;
        });

        return response()->json($rejection);
    }

}