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
                                        <a class="btn btn-sm btn-primary" href="{{ route('deliveryChallans.index') }}">
                                            <i class="fa fa-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <form method="POST" action="{{ isset($deliveryChallan) ? route('deliveryChallans.update', $deliveryChallan->id) : route('deliveryChallans.store') }}" enctype="multipart/form-data" id="jobWorkForm">
                            @csrf
                            @if(isset($deliveryChallan))
                                @method('PUT')
                            @endif
                                <div class="card-body general-info">
                                    <div class="row align-items-center">
                                        <div class="col-lg-6 mb-4">
                                            <label for="job_work_name" class="fw-semibold">Process</label>
                                            <div class="input-group">
                                                <input type="text" name="job_work_name" id="job_work_name" class="form-control" placeholder="Process" value="{{ old('job_work_name', isset($deliveryChallan) ? $deliveryChallan->job_work_name : '') }}">
                                            </div>
                                            @error('job_work_name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label for="pdf_files" class="fw-semibold">Upload PDF Files (Optional)</label>
                                            <div class="input-group">
                                                <input type="file" name="pdf_files[]" id="pdf_files" class="form-control" accept="application/pdf" multiple>
                                            </div>
                                            @error('pdf_files')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>

                                    @if(!empty($deliveryChallan->pdf_files))
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="pdfPreview" class="d-flex flex-wrap">
                                                    @foreach ($deliveryChallan->pdf_files as $file)
                                                        <div class="file-preview m-2 position-relative" data-file="{{ $file }}">
                                                            <div class="card text-center" style="width: 15rem;">
                                                                <div class="card-body">
                                                                    <a href="{{ asset('storage/' . $file) }}" target="_blank">
                                                                        <img src="https://img.icons8.com/plasticine/100/000000/pdf.png" class="img-fluid" alt="PDF Icon">
                                                                    </a>
                                                                    <p class="card-text mt-2">{{ $file }}</p>
                                                                    <button type="button" class="close remove-file btn btn-danger" aria-label="Close" data-file="{{ $file }}">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row align-items-center">
                                        <div class="col-lg-6 mb-4"> 
                                            <label for="customer_id" class="fw-semibold">Client Name</label>
                                            <div class="input-group">
                                                <select name="customer_id" id="customer_id" class="form-control customer_id">
                                                    <option value="">Select Customer</option>
                                                    @foreach($customers as $customer)
                                                        <option value="{{ $customer->id }}" {{ old('customer_id', isset($deliveryChallan) && $deliveryChallan->customer_id ? 'selected' : '') }}>{{ $customer->first_name }}</option>  
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('customer_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label for="po_no" class="fw-semibold">Invoice #</label>
                                            <div class="input-group">
                                                <input type="text" name="po_no" placeholder="Invoice" class="form-control"  id="po_no"  value="{{ old('po_no', isset($deliveryChallan) ? $deliveryChallan->po_no : $newInvoiceNumber ?? '') }}" readonly>
                                            </div>
                                            @error('po_no')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-4">
                                            <label for="po_revision_and_date" class="fw-semibold">PO Revision & Date</label>
                                            <div class="input-group">
                                                <input type="date" name="po_revision_and_date" class="form-control"  id="po_revision_and_date"  value="{{ old('po_revision_and_date', isset($deliveryChallan) ? $deliveryChallan->po_revision_and_date : '') }}">
                                            </div>
                                            @error('po_revision_and_date')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="reason_of_revision" class="fw-semibold">Reason of Revision</label>
                                            <div class="input-group">
                                                <input type="text" name="reason_of_revision" class="form-control" placeholder="Reason of Revision" id="reason_of_revision"  value="{{ old('reason_of_revision', isset($deliveryChallan) ? $deliveryChallan->reason_of_revision : '') }}">
                                            </div>
                                            @error('reason_of_revision')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="quotation_ref_no" class="fw-semibold">Quotation Ref No</label>
                                            <div class="input-group">
                                                <input type="text" name="quotation_ref_no" class="form-control" placeholder="Quotation Ref No" id="quotation_ref_no"  value="{{ old('quotation_ref_no', isset($deliveryChallan) ? $deliveryChallan->quotation_ref_no : '') }}">
                                            </div>
                                            @error('quotation_ref_no')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-4">
                                            <label for="remarks" class="fw-semibold">Remarks</label>
                                            <div class="input-group">
                                                <input type="text" name="remarks" class="form-control" placeholder="remarks" id="remarks"  value="{{ old('remarks', isset($deliveryChallan) ? $deliveryChallan->remarks : '') }}">
                                            </div>
                                            @error('remarks')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="prno" class="fw-semibold">P.R. No</label>
                                            <div class="input-group">
                                                <input type="text" name="prno" class="form-control" placeholder="P.R. No" id="prno"  value="{{ old('prno', isset($deliveryChallan) ? $deliveryChallan->prno : '') }}">
                                            </div>
                                            @error('prno')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="pr_date" class="fw-semibold">P.R. Date</label>
                                            <div class="input-group">
                                                <input type="date" name="pr_date" class="form-control" placeholder="P.R. Date" id="pr_date"  value="{{ old('pr_date', isset($deliveryChallan) ? $deliveryChallan->pr_date : '') }}">
                                            </div>
                                            @error('pr_date')<div class="text-danger">{{ $message }}</div>@enderror
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
                                                            <th style="min-width: 150px;">Wt./PC</th>
                                                            <th style="min-width: 150px;">Net Weight</th>
                                                            <th style="min-width: 200px;">Remark</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(isset($deliveryChallan) && $deliveryChallan->items)
                                                            @foreach($deliveryChallan->items as $index => $item)
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
                                                                    <td><input type="number" class="form-control" name="wt_pc[]" value="{{ $item->wt_pc }}"></td>
                                                                    <td><input type="number" class="form-control amount" name="netweight[]" value="{{ $item->quantity*$item->wt_pc }}" readonly></td>
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
                                                                <td><input type="number" class="form-control" name="wt_pc[]"  placeholder="Per PC Weight"></td>
                                                                <td><input type="number" class="form-control amount" name="netweight[]" readonly></td>
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
                    <td><textarea class="form-control" placeholder="Material Specification" name="material_specification[]"></textarea></td>
                    <td><input type="number" class="form-control" name="quantity[]" value="1"></td>
                    <td><input type="number" class="form-control" name="wt_pc[]"  placeholder="Per PC Weight"></td>
                    <td><input type="number" class="form-control amount" name="netweight[]" readonly></td>
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

    document.addEventListener('DOMContentLoaded', function() {
        const pdfInput = document.getElementById('pdf_files'); // match Blade ID
        const previewContainer = document.getElementById('pdfPreview');
        const form = document.getElementById('jobWorkForm');

        // Handle new file uploads
        pdfInput.addEventListener('change', function(event) {
            const files = event.target.files;

            Array.from(files).forEach((file) => {
                const fileDiv = document.createElement('div');
                fileDiv.classList.add('file-preview', 'm-2', 'position-relative');
                fileDiv.dataset.file = file.name; // file.name for new uploads
                fileDiv.dataset.type = 'new'; // mark as new
                fileDiv.innerHTML = `
                    <div class="card text-center" style="width: 8rem;">
                        <div class="card-body">
                            <img src="https://img.icons8.com/plasticine/100/000000/pdf.png" class="img-fluid" alt="PDF Icon">
                            <p class="card-text mt-2">${file.name}</p>
                        </div>
                        <button type="button" class="close remove-file btn btn-danger" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                previewContainer.appendChild(fileDiv);
            });
        });

        // Handle remove file clicks
        previewContainer.addEventListener('click', function(event) {
            if (event.target.closest('.remove-file')) {
                const fileDiv = event.target.closest('.file-preview');
                const fileType = fileDiv.dataset.type;
                const fileName = fileDiv.dataset.file;

                // Remove new uploaded files from input
                if (fileType === 'new') {
                    const dataTransfer = new DataTransfer();
                    Array.from(pdfInput.files).forEach((file) => {
                        if (file.name !== fileName) dataTransfer.items.add(file);
                    });
                    pdfInput.files = dataTransfer.files;
                } 
                // For existing files, add hidden input with full path
                else {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'delete_files[]';
                    input.value = fileName; // full relative path from data-file
                    form.appendChild(input);
                }

                // Remove file preview
                fileDiv.remove();
            }
        });

        // Mark existing files correctly
        Array.from(previewContainer.querySelectorAll('.file-preview')).forEach(div => {
            div.dataset.type = 'existing'; // mark existing files
        });
    });

    $(document).ready(function() { 
        $('#myTable').on('input', 'input[name="quantity[]"], input[name="wt_pc[]"]', function() {
            var row = $(this).closest('tr');
            var quantity = parseFloat(row.find('input[name="quantity[]"]').val()) || 0;
            var wtPc = parseFloat(row.find('input[name="wt_pc[]"]').val()) || 0;
            var netweight = quantity * wtPc;
            row.find('input[name="netweight[]"]').val(netweight.toFixed(2));
        });
    });

</script>
@endpush
