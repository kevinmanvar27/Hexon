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
                                <h5 class="card-title">Create Sales</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('sales.index') }}">
                                            <i class="fa fa-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <form method="POST" action="{{ isset($sale) ? route('sales.update', $sale->id) : route('sales.store') }}">
                                @csrf
                                @if(isset($sale))
                                    @method('PUT')
                                @endif

                                <div class="card-body general-info">
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-4">
                                            <label for="name" class="fw-semibold">Client Name</label>
                                            <div class="input-group">
                                                <select name="customer_id" id="customer_id" class="form-control customer_id">
                                                    <option value="">Select Customer</option>
                                                    @foreach($customers as $customer)
                                                        <option value="{{ $customer->id }}" {{ old('customer_id', $sale->customer_id ?? '') == $customer->id ? 'selected' : '' }}>{{ $customer->first_name }}</option>  
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('customer_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="valve_type" class="fw-semibold">Invoice #</label>
                                            <div class="input-group">
                                                <input type="text" name="invoice" placeholder="Invoice" class="form-control"  id="invoice"  value="{{ old('invoice', isset($sale) ? $sale->invoice : $newInvoiceNumber ?? '') }}" readonly>
                                            </div>
                                            @error('invoice')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="status" class="fw-semibold">Status</label>
                                            <div class="input-group">
                                                <select name="status" id="status" class="form-select">
                                                    <option value="draft" {{ old('status', $sale->status ?? '') == 'draft' ? 'selected' : '' }}>Draft</option>
                                                    <option value="completed" {{ old('status', $sale->status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                                                </select>
                                            </div>
                                            @error('status')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-6 mb-4">
                                            <label for="product_code" class="fw-semibold">Create Date</label>
                                            <div class="input-group">
                                                <input type="date" name="create_date" placeholder="Create Date" class="form-control" id="create_date" value="{{ old('create_date', $sale->create_date ?? '') }}" >
                                            </div>
                                            @error('create_date')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label for="due_date" class="fw-semibold">Due Date</label>
                                            <div class="input-group">
                                                <input type="date" name="due_date" placeholder="Due Date" class="form-control" id="due_date" value="{{ old('due_date', $sale->due_date ?? '') }}" >
                                            </div>
                                            @error('due_date')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-4">
                                            <label for="orderno" class="fw-semibold">Order NO</label>
                                            <div class="input-group">
                                                <input type="text" name="orderno" placeholder="Order NO" class="form-control" id="orderno" value="{{ old('orderno', $sale->orderno ?? '') }}" >
                                            </div>
                                            @error('orderno')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="lrno" class="fw-semibold">L.R. NO</label>
                                            <div class="input-group">
                                                <input type="text" name="lrno" placeholder="L.R. NO" class="form-control" id="lrno" value="{{ old('lrno', $sale->lrno ?? '') }}" >
                                            </div>
                                            @error('lrno')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="transport" class="fw-semibold">Transport</label>
                                            <div class="input-group">
                                                <input type="text" name="transport" placeholder="Transport" class="form-control" id="transport" value="{{ old('transport', $sale->transport ?? '') }}" >
                                            </div>
                                            @error('transport')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-6 mb-4">
                                            <label for="sku_code" class="fw-semibold">Address</label>
                                            <div class="input-group">
                                                <textarea name="address" id="address" class="form-control" placeholder="Address...">{{ old('address', $sale->address ?? '') }}</textarea>
                                            </div>
                                            @error('address')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label for="note" class="fw-semibold">Note</label>
                                            <div class="input-group">
                                                <textarea name="note" id="note" class="form-control" placeholder="Note...">{{ old('note', $sale->note ?? '') }}</textarea>
                                            </div>
                                            @error('note')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="d-flex justify-content-between mb-4">
                                    <h5 class="card-title">Products </h5>
                                    <button id="addTableRow" type="button" class="btn btn-sm btn-success mb-3"><i class="fa fa-plus"></i> Add Row</button>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="myTable">
                                                    <thead>
                                                        <tr>
                                                            <th style="min-width: 250px;">Products</th>
                                                            <th style="min-width: 150px;">Quantity</th>
                                                            <th style="min-width: 150px;">Price</th>
                                                            <th style="min-width: 150px;">Amount</th>
                                                            <th style="min-width: 200px;">Remark</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(isset($sale) && $sale->items)
                                                            @foreach($sale->items as $index => $item)
                                                                <tr>
                                                                    <td>
                                                                        <select class="form-control product_id" name="product_id[]">
                                                                            <option value="">Select Product</option>
                                                                            @foreach($products as $product)
                                                                                <option value="{{ $product->id }}" {{ $product->id == $item->product_id ? 'selected' : '' }}>{{ $product->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td><input type="number" class="form-control" name="quantity[]" value="{{ $item->quantity }}"></td>
                                                                    <td><input type="number" class="form-control" name="price[]" value="{{ $item->price }}"></td>
                                                                    <td><input type="number" class="form-control" name="amount[]" value="{{ $item->amount }}" readonly></td>
                                                                    <td><input type="text" class="form-control" name="remark[]" value="{{ $item->remark }}"></td>
                                                                    <td><button class="btn btn-danger btn-md deleteRow"><i class="fa fa-trash"></i></button></td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <tr>
                                                                <td>
                                                                    <select class="form-control product_id" name="product_id[]">
                                                                        <option value="">Select Product</option>
                                                                        @foreach($products as $product)
                                                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <td><input type="number" class="form-control" name="quantity[]" value="1"></td>
                                                                <td><input type="number" class="form-control" name="price[]"  placeholder="Price"></td>
                                                                <td><input type="number" class="form-control" name="amount[]" placeholder="Amount" readonly></td>
                                                                <td><input type="text" class="form-control" name="remark[]" placeholder="Remark"></td>
                                                                <td><button class="btn btn-danger btn-md deleteRow"><i class="fa fa-trash"></i></button></td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-7"></div>
                                        <div class="col-md-5">
                                            <table class="table">
                                                <tr>
                                                    <td>Subtotal</td>
                                                    <td><input type="text" class="form-control" id="subtotal" name="sub_total" value="{{ old('sub_total', $sale->sub_total ?? '') }}" readonly></td>
                                                </tr>
                                                <tr>
                                                    <td>P & F Charge</td>
                                                    <td>
                                                        <input type="number" step='0.1' class="form-control mt-2" id="pfcouriercharge"
                                                            name="pfcouriercharge" value="{{ old('pfcouriercharge', $sale->pfcouriercharge ?? '0') }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Discount</td>
                                                    <td>
                                                        <select class="custom-select" id="discount_type" name="discount_type">
                                                            <option value="flat"  {{ old('discount_type', $sale->discount_type ?? '') == 'flat' ? 'selected' : '' }}>Flat</option>
                                                            <option value="percentage" {{ old('discount_type', $sale->discount_type ?? '') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                                        </select>
                                                        <input type="number" class="form-control mt-2" id="discount" step='any' name="discount" value="{{ old('discount', $sale->discount ?? '0') }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>CGST (%)</td>
                                                    <td><input type="number" class="form-control" id="cgst" name="cgst"
                                                            value="{{ old('cgst', $sale->cgst ?? '9') }}" /></td>
                                                </tr>
                                                <tr>
                                                    <td>SGST (%)</td>
                                                    <td><input type="number" class="form-control" id="sgst" name="sgst"
                                                            value="{{ old('sgst', $sale->sgst ?? '9') }}" /></td>
                                                </tr>
                                                <tr>
                                                    <td>IGST (%)</td>
                                                    <td><input type="number" class="form-control" id="igst" name="igst" value="{{ old('igst', $sale->igst ?? '18') }}" /></td>
                                                </tr>
                                                <tr>
                                                    <td>Courier Charge</td>
                                                    <td><input type="number" class="form-control" id="courier_charge" name="courier_charge" value="{{ old('courier_charge', $sale->courier_charge ?? '0') }}" /></td>
                                                </tr>
                                                <tr>
                                                    <td>Round Off</td>
                                                    <td><input type="text" class="form-control" id="round_off" name="round_off" value="{{ old('round_off', $sale->round_off ?? '0') }}" /></td>
                                                </tr>
                                                <tr>
                                                    <td>Total</td>
                                                    <td><input type="text" class="form-control" id="total" name="balance" value="{{ old('balance', $sale->balance ?? '') }}" readonly></td>
                                                </tr>
                                            </table>
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
    $(document).ready(function() {
        function initSelect2(scope) {
            (scope || $(document)).find('.product_id, .customer_id').select2({
                placeholder: "Search or select",
                allowClear: true,
                width: '100%',
                theme: "bootstrap-5"
            });
        }
        initSelect2();

        // Add new row
        $('#addTableRow').click(function() {
            let newRow = `
                <tr>
                    <td>
                        <select class="form-control product_id" name="product_id[]">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" class="form-control" name="quantity[]" value="1"></td>
                    <td><input type="number" class="form-control" name="price[]" placeholder="Price"></td>
                    <td><input type="number" class="form-control" name="amount[]" placeholder="Amount" readonly></td>
                    <td><input type="text" class="form-control" name="remark[]" placeholder="Remark"></td>
                    <td><button class="btn btn-danger btn-md deleteRow"><i class="fa fa-trash"></i></button></td>
                </tr>
            `;
            $('#myTable tbody').append(newRow);
            initSelect2($('#myTable tbody tr:last'));
            calculateAmounts(); // recalc after adding row
        });

        // Delete row
        $('#myTable').on('click', '.deleteRow', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
            calculateAmounts(); // recalc after removing row
        });

        // Calculate amounts dynamically
        function calculateAmounts() { 
            let subtotal = 0;

            $('#myTable tbody tr').each(function() {
                const qty = parseFloat($(this).find('input[name="quantity[]"]').val()) || 0;
                const price = parseFloat($(this).find('input[name="price[]"]').val()) || 0;
                const amount = qty * price;
                subtotal += amount;
                $(this).find('input[name="amount[]"]').val(amount.toFixed(2));
            });

            $('#subtotal').val(subtotal.toFixed(2));

            const pfcouriercharge = parseFloat($('#pfcouriercharge').val()) || 0;
            const courier_charge = parseFloat($('#courier_charge').val()) || 0;

            let discount = parseFloat($('#discount').val()) || 0;
            const discountType = $('#discount_type').val();
            if (discountType === 'percentage') {
                discount = (subtotal * discount) / 100;
            }

            const pfcourierchargeAmount = ((subtotal) * pfcouriercharge) / 100;
            const grandTotal = (subtotal + pfcourierchargeAmount - discount);

            const cgstRate = (parseFloat($('#cgst').val()) || 0) / 100;
            const sgstRate = (parseFloat($('#sgst').val()) || 0) / 100;
            const igstRate = (parseFloat($('#igst').val()) || 0) / 100;

            const cgstAmount = grandTotal * cgstRate;
            const sgstAmount = grandTotal * sgstRate;
            const igstAmount = grandTotal * igstRate;

            const finalTotal = grandTotal + cgstAmount + sgstAmount + igstAmount + courier_charge;

            const roundedValue = Math.round(finalTotal);
            const difference = roundedValue - finalTotal;

            $('#total').val(roundedValue);
            $('#round_off').val(difference.toFixed(2));
        }

        // Recalculate when any input changes
        $(document).on('input change', 
            'input[name="quantity[]"], input[name="price[]"], #discount, #discount_type, #cgst, #sgst, #igst, #courier_charge, #pfcouriercharge', 
            calculateAmounts
        );

        // Initial calculation on page load
        calculateAmounts();
    });

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
</script>
@endpush
