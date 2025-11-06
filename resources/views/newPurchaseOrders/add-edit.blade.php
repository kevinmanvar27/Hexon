@extends('layouts.app')

@section('content')

    <main class="nxl-container">
        <div class="nxl-content">
            <!-- [ Main Content ] start -->
            <div class="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            </div>
                        @endif
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Create New Purchase Order</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('newPurchaseOrders.index') }}">
                                            <i class="fa fa-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <form method="POST" action="{{ isset($newPurchaseOrder) ? route('newPurchaseOrders.update', $newPurchaseOrder->id) : route('newPurchaseOrders.store') }}">
                            @csrf
                            @if(isset($newPurchaseOrder))
                                @method('PUT')
                            @endif
                                <div class="card-body general-info">
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-4">
                                            <label for="customer_id" class="fw-semibold">Client Name</label>
                                            <div class="input-group">
                                                <select name="customer_id" id="customer_id" class="form-control customer_id">
                                                    <option value="">Select Customer</option>
                                                    @foreach($customers as $customer)
                                                        <option value="{{ $customer->id }}" {{ old('customer_id', isset($newPurchaseOrder) && $newPurchaseOrder->customer_id ? 'selected' : '') }}>{{ $customer->first_name }}</option>  
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('customer_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="invoice" class="fw-semibold">Invoice #</label>
                                            <div class="input-group">
                                                <input type="text" name="invoice" placeholder="Invoice" class="form-control"  id="invoice"  value="{{ old('invoice', isset($newPurchaseOrder) ? $newPurchaseOrder->invoice : $newInvoiceNumber ?? '') }}" readonly>
                                            </div>
                                            @error('invoice')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="status" class="fw-semibold">Status</label>
                                            <div class="input-group">
                                                <select name="status" id="status" class="form-select">
                                                    <option value="draft" {{ old('status', $newPurchaseOrder->status ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                                                    <option value="completed" {{ old('status', $newPurchaseOrder->status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                                                </select>
                                            </div>
                                            @error('status')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-4">
                                            <label for="po_revision_and_date" class="fw-semibold">PO Revision & Date</label>
                                            <div class="input-group">
                                                <input type="date" name="po_revision_and_date" class="form-control"  id="po_revision_and_date"  value="{{ old('po_revision_and_date', isset($newPurchaseOrder) ? $newPurchaseOrder->po_revision_and_date : '') }}">
                                            </div>
                                            @error('po_revision_and_date')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="reason_of_revision" class="fw-semibold">Reason of Revision</label>
                                            <div class="input-group">
                                                <input type="text" name="reason_of_revision" class="form-control" placeholder="Reason of Revision" id="reason_of_revision"  value="{{ old('reason_of_revision', isset($newPurchaseOrder) ? $newPurchaseOrder->reason_of_revision : '') }}">
                                            </div>
                                            @error('reason_of_revision')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="quotation_ref_no" class="fw-semibold">Quotation Ref No</label>
                                            <div class="input-group">
                                                <input type="text" name="quotation_ref_no" class="form-control" placeholder="Quotation Ref No" id="quotation_ref_no"  value="{{ old('quotation_ref_no', isset($newPurchaseOrder) ? $newPurchaseOrder->quotation_ref_no : '') }}">
                                            </div>
                                            @error('quotation_ref_no')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-4">
                                            <label for="remarks" class="fw-semibold">Remarks</label>
                                            <div class="input-group">
                                                <input type="text" name="remarks" class="form-control" placeholder="Remarks" id="remarks"  value="{{ old('remarks', isset($newPurchaseOrder) ? $newPurchaseOrder->remarks : '') }}">
                                            </div>
                                            @error('remarks')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="prno" class="fw-semibold">P.R. No</label>
                                            <div class="input-group">
                                                <input type="text" name="prno" class="form-control" placeholder="P.R. No" id="prno"  value="{{ old('prno', isset($newPurchaseOrder) ? $newPurchaseOrder->prno : '') }}">
                                            </div>
                                            @error('prno')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="pr_date" class="fw-semibold">P.R. Date</label>
                                            <div class="input-group">
                                                <input type="date" name="pr_date" class="form-control" placeholder="P.R. Date" id="pr_date"  value="{{ old('pr_date', isset($newPurchaseOrder) ? $newPurchaseOrder->pr_date : '') }}">
                                            </div>
                                            @error('pr_date')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-6 mb-4">
                                            <label for="address" class="fw-semibold">Address</label>
                                            <div class="input-group">
                                                <textarea name="address" id="address" class="form-control" placeholder="Address">{{ old('address', isset($newPurchaseOrder) ? $newPurchaseOrder->address : '') }}</textarea>
                                            </div>
                                            @error('address')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label for="note" class="fw-semibold">Note</label>
                                            <div class="input-group">
                                                <textarea name="note" id="note" class="form-control" placeholder="Note">{{ old('note', isset($newPurchaseOrder) ? $newPurchaseOrder->note : '') }}</textarea>
                                            </div>
                                            @error('note')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <button id="addTableRow" type="button" class="btn btn-sm btn-success mb-3"><i class="fa fa-plus"></i> Add Row</button>
                                    <div class="row align-items-center">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="myTable">
                                                    <thead>
                                                        <tr>
                                                            <th style="min-width: 250px;">Spare Part</th>
                                                            <th style="min-width: 300px;">Material/Specification</th>
                                                            <th style="min-width: 150px;">Quantity</th>
                                                            <th style="min-width: 150px;">Unit</th>
                                                            <th style="min-width: 150px;">Rate/Kgs</th>
                                                            <th style="min-width: 150px;">Per Pc Weight</th>
                                                            <th style="min-width: 150px;">Total Weight</th>
                                                            <th style="min-width: 150px;">Amount</th>
                                                            <th style="min-width: 150px;">Delivery Date</th>
                                                            <th style="min-width: 200px;">Remark</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(isset($newPurchaseOrder) && $newPurchaseOrder->items)
                                                            @foreach($newPurchaseOrder->items as $index => $item)
                                                                <tr>
                                                                    <td>
                                                                        <select class="form-control customer_id" name="spare_part_id[]">
                                                                            <option value="">Select Spare Part</option>
                                                                            @foreach($spareParts as $spare_part)
                                                                                <option value="{{ $spare_part->id }}" {{ $spare_part->id == $item->spare_part_id ? 'selected' : '' }}>{{ $spare_part->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td><textarea class="form-control" name="material_specification[]">{{ $item->material_specification }}</textarea></td>
                                                                    <td><input type="number" class="form-control" name="quantity[]" value="{{ $item->quantity }}"></td>
                                                                    <td><input type="text" class="form-control" name="unit[]" value="{{ $item->unit }}"></td>
                                                                    <td><input type="number" class="form-control" name="rate_kgs[]" value="{{ $item->rate_kgs }}"></td>
                                                                    <td><input type="number" class="form-control" name="per_pc_weight[]" value="{{ $item->per_pc_weight }}"></td>
                                                                    <td><input type="number" class="form-control" name="total_weight[]" value="{{ $item->total_weight }}"></td>
                                                                    <td><input type="number" class="form-control" name="amount[]" value="{{ $item->amount }}"></td>
                                                                    <td><input type="date" class="form-control" name="delivery_date[]" value="{{ $item->delivery_date }}"></td>
                                                                    <td><input type="text" class="form-control" name="remark[]" value="{{ $item->remark }}"></td>
                                                                    <td><button class="btn btn-danger btn-md deleteRow"><i class="fa fa-trash"></i></button></td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td>
                                                                    <select class="form-control spare_part_id" name="spare_part_id[]">
                                                                        <option value="">Select Spare Part</option>
                                                                        @foreach($spareParts as $spare_part)
                                                                            <option value="{{ $spare_part->id }}">{{ $spare_part->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td><textarea class="form-control" placeholder="Material Specificarion" name="material_specification[]"></textarea></td>
                                                                <td><input type="number" class="form-control" name="quantity[]" value="1"></td>
                                                                <td><input type="text" class="form-control" name="unit[]" placeholder="Unit"></td>
                                                                <td><input type="number" class="form-control" name="rate_kgs[]"  placeholder="Rate/KGS"></td>
                                                                <td><input type="number" class="form-control" name="per_pc_weight[]"  placeholder="Per PC Weight"></td>
                                                                <td><input type="number" class="form-control" name="total_weight[]" placeholder="Total Weight"></td>
                                                                <td><input type="number" class="form-control" name="amount[]" placeholder="Amount"></td>
                                                                <td><input type="date" class="form-control" name="delivery_date[]"></td>
                                                                <td><input type="text" class="form-control" name="remark[]" placeholder="Remark"></td>
                                                                <td><button class="btn btn-danger btn-md deleteRow"><i class="fa fa-trash"></i></button></td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <button type="submit" class="btn btn-primary mt-2 mb-3">
                                                    <i class="fa-solid fa-floppy-disk me-2"></i>
                                                    Submit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </main>
    
@endsection

@push('scripts')
<script>
    function initSelect2(scope) {
        (scope || $(document)).find('.customer_id, .spare_part_id').select2({
            placeholder: "Search or select",
            allowClear: true,
            width: '100%',
            theme: "bootstrap-5"
        });
    }


    $(document).ready(function() {
        initSelect2(); 
    });

    $(document).ready(function() { 

        $('#addTableRow').click(function() {
            let newRow = `
                <tr>
                    <td>
                        <select class="form-control spare_part_id" name="spare_part_id[]">
                            <option value="">Select Spare Part</option>
                            @foreach($spareParts as $spare_part)
                                <option value="{{ $spare_part->id }}">{{ $spare_part->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><textarea class="form-control" placeholder="Material Specificarion" name="material_specification[]"></textarea></td>
                    <td><input type="number" class="form-control" name="quantity[]" value="1"></td>
                    <td><input type="text" class="form-control" name="unit[]" placeholder="Unit"></td>
                    <td><input type="number" class="form-control" name="rate_kgs[]"  placeholder="Rate/KGS"></td>
                    <td><input type="number" class="form-control" name="per_pc_weight[]"  placeholder="Per PC Weight"></td>
                    <td><input type="number" class="form-control" name="total_weight[]" placeholder="Total Weight"></td>
                    <td><input type="number" class="form-control" name="amount[]" placeholder="Amount"></td>
                    <td><input type="date" class="form-control" name="delivery_date[]"></td>
                    <td><input type="text" class="form-control" name="remark[]" placeholder="Remark"></td>
                    <td><button class="btn btn-danger btn-md deleteRow"><i class="fa fa-trash"></i></button></td>
                </tr>
            `;
            $('#myTable tbody').append(newRow);
            initSelect2($('#myTable tbody tr:last'));
        });

        $('#myTable').on('click', '.deleteRow', function() {
            $(this).closest('tr').remove();
        });

        function updateRowNumbers() {
            rowCount = 1;
            $('#myTable tbody tr').each(function() {
                $(this).find('td:first').text(rowCount);
                rowCount++;
            });
        }

        $('#customer_id').on('change', function() {
            var customerId = $(this).val();
            
            if (customerId) {
                $.ajax({
                    url: '/customer/' + customerId + '/details',
                    type: 'GET',
                    success: function(data) {
                        $('#address').val(data.address);
                    },
                    error: function() {
                        alert('Unable to fetch customer address.');
                    }
                });
            } else {
                $('#address').val(''); 
            }
        });

    })
</script>
@endpush
