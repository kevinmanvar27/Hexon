<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DeliveryChallan;
use App\Models\SpareParts;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tcpdf\Fpdi;
use App\Models\DeliveryChallanItem;
use App\DataTables\DeliveryChallansDataTable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use App\Models\Settings;

class DeliveryChallanController extends Controller
{
    public function index(DeliveryChallansDataTable $dataTable)
    {
        return $dataTable->render('deliveryChallans.index');
    }

    public function create()
    {
        $customers = Customer::all();
        $spareParts = SpareParts::all();

        $currentDate = Carbon::now();        
        $currentMonth = $currentDate->format('m'); 
        $financialYearStart = $currentDate->month >= 4 ? $currentDate->year : $currentDate->year - 1;
        $financialYearEnd = $financialYearStart + 1;
        $financialYear = substr($financialYearStart, -2) . '-' . substr($financialYearEnd, -2);

        $latestInvoice = DeliveryChallan::latest('id')->first();
        if ($latestInvoice) {            
            preg_match('/HVDC-(\d+)/', $latestInvoice->invoice, $matches);
            $lastInvoiceNumber = isset($matches[1]) ? (int)$matches[1] : 0;

            $newInvoiceNumber = 'HVDC-' . ($lastInvoiceNumber + 1) . '-' . $currentMonth . '/' . $financialYear;
        } else {
            $newInvoiceNumber = 'HVDC-1-' . $currentMonth . '/' . $financialYear;
        }

        return view('deliveryChallans.add-edit', compact('customers', 'spareParts', 'newInvoiceNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'job_work_name'  => 'required|string|max:255',
            'pdf_files.*'    => 'required|file|mimes:pdf|max:2048',
        ]);

        $pdfFiles = [];

        if ($request->hasFile('pdf_files')) 
        {
            foreach ($request->file('pdf_files') as $file)
            {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $relativePath = $file->storeAs('jobworkChallan', $fileName, 'public');
                $pdfFiles[] = $relativePath;
            }
        }

        $invoice = DeliveryChallan::create([
            'job_work_name'         => $request->job_work_name,
            'pdf_files'             => $pdfFiles,
            'user_id'               => Auth::id(),
            'customer_id'           => $request->input('customer_id'),
            'po_no'                 => $request->input('po_no'),
            'prno'                  => $request->input('prno'),
            'po_revision_and_date'  => $request->input('po_revision_and_date'),
            'reason_of_revision'    => $request->input('reason_of_revision'),
            'quotation_ref_no'      => $request->input('quotation_ref_no'),
            'remarks'               => $request->input('remarks'),
            'pr_date'               => $request->input('pr_date'),
        ]);

        foreach ($request->spare_part_id as $index => $sparePartId) {
            DeliveryChallanItem::create([
                'delivery_challan_id'   => $invoice->id,
                'spare_part_id'         => $sparePartId,
                'quantity'              => $request->quantity[$index] ?? 0,
                'remaining_quantity'    => $request->quantity[$index] ?? 0,
                'wt_pc'                 => $request->wt_pc[$index] ?? null,
                'material_specification'=> $request->material_specification[$index] ?? null,
                'remark'                => $request->remark[$index] ?? null,
            ]);
        }

        return redirect()->route('deliveryChallans.index')
                        ->with('success', 'Delivery Challan created successfully.');
    }


    public function show($id)
    {
        $deliveryChallan = DeliveryChallan::with('customer', 'user')->findOrFail($id);
        
        return response()->json($deliveryChallan);
    }

    public function edit($id)
    {
        $deliveryChallan = DeliveryChallan::with('customer')->findOrFail($id);
        $customers = Customer::all();
        $spareParts = SpareParts::all();

        return view('deliveryChallans.add-edit', compact('deliveryChallan', 'customers', 'spareParts'));
    }

    public function update(Request $request, $id)
    {
        $deliveryChallan = DeliveryChallan::findOrFail($id);

        $request->validate([
            'customer_id'    => 'required|exists:customers,id',
            'job_work_name'  => 'required|string|max:255',
            'pdf_files.*'    => 'nullable|file|mimes:pdf|max:2048',
        ]);

        // Existing files
        $existingFiles = $deliveryChallan->pdf_files ?? [];
        $filesToDelete = $request->input('delete_files', []);

        if (!empty($filesToDelete)) {
            foreach ($filesToDelete as $filePath) { // Use full path from input
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
                // Remove from existing files array
                $existingFiles = array_filter($existingFiles, fn($f) => $f !== $filePath);
            }
        }

        // Handle new uploads
        if ($request->hasFile('pdf_files')) {
            foreach ($request->file('pdf_files') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $relativePath = $file->storeAs('jobworkChallan', $fileName, 'public');
                $existingFiles[] = $relativePath;
            }
        }

        // Update main model
        $deliveryChallan->update([
            'job_work_name'         => $request->job_work_name,
            'pdf_files'             => $existingFiles,
            'customer_id'           => $request->customer_id,
            'po_no'                 => $request->po_no,
            'prno'                  => $request->prno,
            'po_revision_and_date'  => $request->po_revision_and_date,
            'reason_of_revision'    => $request->reason_of_revision,
            'quotation_ref_no'      => $request->quotation_ref_no,
            'remarks'               => $request->remarks,
            'pr_date'               => $request->pr_date,
        ]);

        // Update items
        $deliveryChallan->items()->delete();
        foreach ($request->spare_part_id as $index => $sparePartId) {
            DeliveryChallanItem::create([
                'delivery_challan_id'   => $deliveryChallan->id,
                'spare_part_id'         => $sparePartId,
                'quantity'              => $request->quantity[$index] ?? 0,
                'remaining_quantity'    => $request->quantity[$index] ?? 0,
                'wt_pc'                 => $request->wt_pc[$index] ?? null,
                'material_specification'=> $request->material_specification[$index] ?? null,
                'remark'                => $request->remark[$index] ?? null,
            ]);
        }

        return redirect()->route('deliveryChallans.index')
                        ->with('success', 'Delivery Challan updated successfully.');
    }

    public function receive($id)
    { 
        $deliveryChallan = DeliveryChallan::with('items.sparePart')->findOrFail($id);
        return view('deliveryChallans.receive', compact('deliveryChallan'));

    }


    public function storeReceivedQuantity(Request $request, $id)
    {
        $request->validate([
            'received_quantity.*' => 'required|numeric|min:0',
        ]);

        $purchaseOrder = DeliveryChallan::findOrFail($id);

        foreach ($request->input('received_quantity') as $itemId => $receivedQty) {
            $orderItem = DeliveryChallanItem::find($itemId);

            if ($orderItem) {
                $newRemainingQty = max($orderItem->remaining_quantity - $receivedQty, 0);

                // Update item
                $orderItem->remaining_quantity = $newRemainingQty;
                $orderItem->save();

                // Update stock (optional)
                // $sparePart = SpareParts::find($orderItem->spare_part_id);
                // if ($sparePart) {
                //     $sparePart->qty += $receivedQty;
                //     $sparePart->save();
                // }
            }
        }

        return redirect()->route('deliveryChallans.index')->with('success', 'Received quantities updated successfully.');
    }   

   public function download(Request $request, $id)
    { 
        $invoice = DeliveryChallan::with('items', 'customer')->findOrFail($id);
        $settings = Settings::first();

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

        $html = view('deliveryChallans.download', [
            'invoice' => $invoice,
            'settings' => $settings,
            'logoBase64' => $logoBase64,
            'signatureBase64' => $signatureBase64,
        ])->render();

        $pdf = Pdf::loadHTML($html);

        return $pdf->download( str_replace('/','-', $invoice->invoice) . '.pdf');

    }

    public function destroy($id)
    {
        $deliveryChallan = DeliveryChallan::findOrFail($id);

        if ($deliveryChallan->pdf_files) {
            foreach ($deliveryChallan->pdf_files as $filePath) {
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }
        }

        $deliveryChallan->items()->delete();

        $deliveryChallan->delete();

        return redirect()->route('deliveryChallans.index')
                        ->with('success', 'Delivery Challan deleted successfully.');
    }

}
