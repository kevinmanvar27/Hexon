<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NewPurchaseOrder;
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\SpareParts;
use App\DataTables\NewPurchaseOrdersDataTable;
use App\Models\NewPurchaseOrderItem;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Settings;


class NewPurchaseOrderController extends Controller
{
    public function index(NewPurchaseOrdersDataTable $dataTable)
    {
        return $dataTable->render('newPurchaseOrders.index');
    }

    public function create()
    {
        $customers = Customer::all();

        $currentDate = Carbon::now();        
        $currentMonth = $currentDate->format('m'); 
        $financialYearStart = $currentDate->month >= 4 ? $currentDate->year : $currentDate->year - 1;
        $financialYearEnd = $financialYearStart + 1;
        $financialYear = substr($financialYearStart, -2) . '-' . substr($financialYearEnd, -2);

        $latestInvoice = NewPurchaseOrder::latest('id')->first();
        if ($latestInvoice) {            
            preg_match('/HVPO-(\d+)/', $latestInvoice->invoice, $matches);
            $lastInvoiceNumber = isset($matches[1]) ? (int)$matches[1] : 0;

            $newInvoiceNumber = 'HVPO-' . ($lastInvoiceNumber + 1) . '-' . $currentMonth . '/' . $financialYear;
        } else {
            $newInvoiceNumber = 'HVPO-1-' . $currentMonth . '/' . $financialYear;
        }

        $spareParts = SpareParts::all();

        return view('newPurchaseOrders.add-edit', compact('customers', 'newInvoiceNumber', 'spareParts'));
    }

    //create store method for new purchase order
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required',
            'invoice' => 'required|unique:new_purchase_orders',
            'status' => 'nullable',
            'po_revision_and_date' => 'required',
            'reason_of_revision' => 'nullable',
            'quotation_ref_no' => 'nullable',
            'remarks' => 'nullable',
            'prno' => 'nullable',
            'pr_date' => 'required',
            'address' => 'required',
            'note' => 'nullable',

            'spare_part_id'=>'required|array',
            'spare_part_id.*'=>'integer|exists:spare_parts,id',
            'material_specification'=>'required|array',
            'material_specification.*'=>'string',
            'quantity'=>'required|array',
            'quantity.*'=>'numeric|min:1',
            'unit'=>'required|array',
            'unit.*'=>'string',
            'rate_kgs'=>'required|array',
            'rate_kgs.*'=>'numeric',
            'per_pc_weight'=>'required|array',
            'per_pc_weight.*'=>'numeric',
            'total_weight'=>'required|array',
            'total_weight.*'=>'numeric',
            'amount'=>'required|array',
            'amount.*'=>'numeric',
            'delivery_date'=>'required|array',
            'delivery_date.*'=>'date',
            'remark'=>'required|array',
            'remark.*'=>'string',

        ]);
            DB::transaction(function () use ($validated, $request) {
            // Create the Purchase Order
            $purchaseData = collect($validated)->except([
                'spare_part_id',
                'material_specification',
                'quantity',
                'unit',
                'rate_kgs',
                'per_pc_weight',
                'total_weight',
                'amount',
                'delivery_date',
                'remark',
            ])->toArray();

            $newPurchaseOrder = NewPurchaseOrder::create($purchaseData);

            // Create line items
            foreach ($request->spare_part_id as $index => $sparePartId) {
                NewPurchaseOrderItem::create([
                    'new_purchase_order_id' => $newPurchaseOrder->id,
                    'spare_part_id' => $sparePartId,
                    'quantity' => $request->quantity[$index],
                    'remaining_quantity' => $request->quantity[$index],
                    'price' => $request->rate_kgs[$index],
                    'amount' => $request->amount[$index],
                    'product_unit' => $request->unit[$index],
                    'remark' => $request->remark[$index],
                    'material_specification' => $request->material_specification[$index],
                    'unit' => $request->unit[$index],
                    'rate_kgs' => $request->rate_kgs[$index],
                    'per_pc_weight' => $request->per_pc_weight[$index],
                    'total_weight' => $request->total_weight[$index],
                    'delivery_date' => $request->delivery_date[$index],
                ]);
            }
        });


        return redirect()->route('newPurchaseOrders.index')->with('success', 'New Purchase Order created successfully');

    }

    //create edit method for new purchase order
    public function edit($id)
    {
        $newPurchaseOrder = NewPurchaseOrder::with('items')->findOrFail($id);
        $customers = Customer::all();
        $spareParts = SpareParts::all();
        return view('newPurchaseOrders.add-edit', compact('newPurchaseOrder', 'customers', 'spareParts'));
    }

    public function show($id)
    {
        $purchaseOrder = NewPurchaseOrder::with('customer')->findOrFail($id);
        
        return response()->json($purchaseOrder);
    }

    //create update method for new purchase order
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'customer_id' => 'required',
            'invoice' => 'required|unique:new_purchase_orders,invoice,' . $id,
            'status' => 'nullable',
            'po_revision_and_date' => 'required',
            'reason_of_revision' => 'nullable',
            'quotation_ref_no' => 'nullable',
            'remarks' => 'nullable',
            'prno' => 'nullable',
            'pr_date' => 'required',
            'address' => 'required',
            'note' => 'nullable',

            // Line items
            'spare_part_id'=>'required|array',
            'spare_part_id.*'=>'integer|exists:spare_parts,id',
            'material_specification'=>'required|array',
            'material_specification.*'=>'string',
            'quantity'=>'required|array',
            'quantity.*'=>'numeric|min:1',
            'unit'=>'required|array',
            'unit.*'=>'string',
            'rate_kgs'=>'required|array',
            'rate_kgs.*'=>'numeric',
            'per_pc_weight'=>'required|array',
            'per_pc_weight.*'=>'numeric',
            'total_weight'=>'required|array',
            'total_weight.*'=>'numeric',
            'amount'=>'required|array',
            'amount.*'=>'numeric',
            'delivery_date'=>'required|array',
            'delivery_date.*'=>'date',
            'remark'=>'required|array',
            'remark.*'=>'string',
        ]);

        DB::transaction(function () use ($request, $validated, $id) {
            // Update the main purchase order
            $purchaseOrder = NewPurchaseOrder::findOrFail($id);

            $mainData = collect($validated)->except([
                'spare_part_id',
                'material_specification',
                'quantity',
                'unit',
                'rate_kgs',
                'per_pc_weight',
                'total_weight',
                'amount',
                'delivery_date',
                'remark'
            ])->toArray();

            $purchaseOrder->update($mainData);

            $existingRemaining = $purchaseOrder->items
                ->pluck('remaining_quantity', 'spare_part_id')
                ->toArray();

            $purchaseOrder->items()->delete();

            foreach ($request->spare_part_id as $index => $sparePartId) {
                $remaining = $existingRemaining[$sparePartId] ?? $request->quantity[$index];

                NewPurchaseOrderItem::create([
                    'new_purchase_order_id' => $purchaseOrder->id,
                    'spare_part_id' => $sparePartId,
                    'quantity' => $request->quantity[$index],
                    'remaining_quantity' => $remaining,
                    'price' => $request->rate_kgs[$index],
                    'amount' => $request->amount[$index],
                    'product_unit' => $request->unit[$index],
                    'remark' => $request->remark[$index],
                    'material_specification' => $request->material_specification[$index],
                    'unit' => $request->unit[$index],
                    'rate_kgs' => $request->rate_kgs[$index],
                    'per_pc_weight' => $request->per_pc_weight[$index],
                    'total_weight' => $request->total_weight[$index],
                    'delivery_date' => $request->delivery_date[$index],
                ]);
            }
        });

        return redirect()->route('newPurchaseOrders.index')->with('success', 'New Purchase Order updated successfully');
    }


    public function destroy($id)
    {
        $purchaseOrder = NewPurchaseOrder::findOrFail($id);

        DB::transaction(function () use ($purchaseOrder) {
            
            $purchaseOrder->items()->delete();

            $purchaseOrder->delete();
        });

        return redirect()->route('newPurchaseOrders.index')->with('success', 'Purchase Order deleted successfully');
    }

    public function receive($id)
    { 
        $purchaseOrder = NewPurchaseOrder::with('items.sparePart')->findOrFail($id);
        return view('newPurchaseOrders.receive', compact('purchaseOrder'));

    }

    public function download(Request $request, $id)
    { 
        $invoice = NewPurchaseOrder::with('items', 'customer')->findOrFail($id);
        $settings = Settings::first(); // Adjust as needed

        // Convert logo to base64
        $logoBase64 = null;
        if ($settings && $settings->logo) {
            $logoPath = storage_path('app/public/uploads/' . $settings->logo);
            if (File::exists($logoPath)) {
                $logoType = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
                $logoData = file_get_contents($logoPath);
                $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
            }
        }

        $signatureBase64 = null;
        if ($settings && $settings->authorized_signatory) {
            $signaturePath = storage_path('app/public/uploads/' . $settings->authorized_signatory);
            if (File::exists($signaturePath)) {
                $sigType = strtolower(pathinfo($signaturePath, PATHINFO_EXTENSION));
                $sigData = file_get_contents($signaturePath);
                $signatureBase64 = 'data:image/' . $sigType . ';base64,' . base64_encode($sigData);
            }
        }

        $html = view('newPurchaseOrders.download', [
            'invoice' => $invoice,
            'settings' => $settings,
            'logoBase64' => $logoBase64,
            'signatureBase64' => $signatureBase64,
        ])->render();

        $pdf = Pdf::loadHTML($html);

        return $pdf->download( str_replace('/','-', $invoice->invoice) . '.pdf');

    }

    public function storeReceivedQuantity(Request $request, $id)
    {
        $request->validate([
            'received_quantity.*' => 'required|numeric|min:0',
        ]);

        $purchaseOrder = NewPurchaseOrder::findOrFail($id);

        foreach ($request->input('received_quantity') as $itemId => $receivedQty) {
            $orderItem = NewPurchaseOrderItem::find($itemId);

            if ($orderItem) {
                $newRemainingQty = max($orderItem->remaining_quantity - $receivedQty, 0);

                // Update item
                $orderItem->remaining_quantity = $newRemainingQty;
                $orderItem->save();

                // Update stock (optional)
                $sparePart = SpareParts::find($orderItem->spare_part_id);
                if ($sparePart) {
                    $sparePart->qty += $receivedQty;
                    $sparePart->save();
                }
            }
        }

        return redirect()->route('newPurchaseOrders.index')->with('success', 'Received quantities updated successfully.');
    }   

}
