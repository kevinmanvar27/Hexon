<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\NewPurchaseOrder;
use Illuminate\Support\Facades\DB;
use App\DataTables\SalesDataTable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Settings;

class SaleController extends Controller
{
    public function index(SalesDataTable $dataTable)
    {
        return $dataTable->render('sales.index');
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();

        $currentDate = Carbon::now();        
        $currentMonth = $currentDate->format('m'); 
        $financialYearStart = $currentDate->month >= 4 ? $currentDate->year : $currentDate->year - 1;
        $financialYearEnd = $financialYearStart + 1;
        $financialYear = substr($financialYearStart, -2) . '-' . substr($financialYearEnd, -2);

        $latestInvoice = Sale::latest('id')->first();
        if ($latestInvoice) {            
            preg_match('/HVS-(\d+)/', $latestInvoice->invoice, $matches);
            $lastInvoiceNumber = isset($matches[1]) ? (int)$matches[1] : 0;

            $newInvoiceNumber = 'HVS-' . ($lastInvoiceNumber + 1) . '-' . $currentMonth . '/' . $financialYear;
        } else {
            $newInvoiceNumber = 'HVS-1-' . $currentMonth . '/' . $financialYear;
        }

        return view('sales.add-edit', compact('customers', 'products', 'newInvoiceNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice' => 'required|unique:sales,invoice',
            'status' => 'nullable|string',
            'create_date' => 'required|date',
            'due_date' => 'required|date',
            'orderno' => 'required|string',
            'lrno' => 'required|string',
            'transport' => 'required|string',
            'address' => 'required|string',
            'note' => 'nullable|string',
            'sub_total' => 'nullable|numeric',
            'pfcouriercharge' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'discount_type' => 'nullable|string',
            'cgst' => 'nullable|numeric',
            'sgst' => 'nullable|numeric',
            'igst' => 'nullable|numeric',
            'courier_charge' => 'nullable|numeric',
            'round_off' => 'nullable|numeric',
            'balance' => 'nullable|numeric',

            'product_id'=>'required|array',
            'product_id.*'=>'integer|exists:products,id',
            'quantity'=>'required|array',
            'quantity.*'=>'numeric|min:1',
            'price'=>'required|array',
            'price.*'=>'numeric',
            'amount'=>'required|array',
            'amount.*'=>'numeric',
            'remark'=>'nullable|array',
            'remark.*'=>'string|nullable'
        ]);

        DB::transaction(function () use ($validated) {
            // Create Sale
            $saleData = collect($validated)->except([
                'product_id','quantity','price','amount','remark'
            ])->toArray();

            $sale = Sale::create($saleData);

            // Create line items
            foreach ($validated['product_id'] as $index => $productId) {
                $sale->items()->create([
                    'product_id' => $productId,
                    'quantity' => $validated['quantity'][$index],
                    'price' => $validated['price'][$index],
                    'amount' => $validated['amount'][$index],
                    'remark' => $validated['remark'][$index] ?? null,
                ]);
            }
        });

        return redirect()->route('sales.index')->with('success', 'Sales created successfully');
    }

    public function edit($id)
    {
        $sale = Sale::with('items')->findOrFail($id);
        $customers = Customer::all();
        $products = Product::all();
        return view('sales.add-edit', compact('sale', 'customers', 'products'));
    }

    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice' => 'required|unique:sales,invoice,' . $sale->id,
            'status' => 'nullable|string',
            'create_date' => 'required|date',
            'due_date' => 'required|date',
            'orderno' => 'required|string',
            'lrno' => 'required|string',
            'transport' => 'required|string',
            'address' => 'required|string',
            'note' => 'nullable|string',
            'sub_total' => 'nullable|numeric',
            'pfcouriercharge' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'discount_type' => 'nullable|string',
            'cgst' => 'nullable|numeric',
            'sgst' => 'nullable|numeric',
            'igst' => 'nullable|numeric',
            'courier_charge' => 'nullable|numeric',
            'round_off' => 'nullable|numeric',
            'balance' => 'nullable|numeric',

            'product_id'=>'required|array',
            'product_id.*'=>'integer|exists:products,id',
            'quantity'=>'required|array',
            'quantity.*'=>'numeric|min:1',
            'price'=>'required|array',
            'price.*'=>'numeric',
            'amount'=>'required|array',
            'amount.*'=>'numeric',
            'remark'=>'nullable|array',
            'remark.*'=>'string|nullable'
        ]);

        DB::transaction(function () use ($validated, $sale) {
            // Update Sale
            $saleData = collect($validated)->except([
                'product_id','quantity','price','amount','remark'
            ])->toArray();

            $sale->update($saleData);

            // Remove old line items
            $sale->items()->delete();

            // Re-create line items
            foreach ($validated['product_id'] as $index => $productId) {
                $sale->items()->create([
                    'product_id' => $productId,
                    'quantity' => $validated['quantity'][$index],
                    'price' => $validated['price'][$index],
                    'amount' => $validated['amount'][$index],
                    'remark' => $validated['remark'][$index] ?? null,
                ]);
            }
        });

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully');
    }

    public function download(Request $request, $id)
    {
        // Eager load related models
        $invoice = Sale::with(['items.product', 'customer'])->findOrFail($id);
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

        // Pass invoice, settings, and base64 images to view
        $html = view('sales.download', [
            'invoice' => $invoice,
            'settings' => $settings,
            'logoBase64' => $logoBase64,
            'signatureBase64' => $signatureBase64,
        ])->render();

        $pdf = Pdf::loadHTML($html);

        // Use invoice number safely for PDF filename
        $filename = str_replace('/', '-', $invoice->invoice ?? 'invoice') . '.pdf';

        return $pdf->download($filename);
    }

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully');
    }

    public function show($id)
    {
        $sale = Sale::with('customer')->findOrFail($id);
        
        return response()->json($sale);
    }
}   
