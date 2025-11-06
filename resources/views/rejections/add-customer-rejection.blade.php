@extends('layouts.app')

@section('content')

<main class="nxl-container">
    <div class="nxl-content">
        <div class="main-content">
            <div class="row">
                <div class="col-lg-12">
                    {{-- Display validation errors --}}
                    @if ($errors->any())
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
                            <h5 class="card-title">{{ isset($rejection) ? 'Edit' : 'Add' }} Customer Rejection</h5>
                            <div class="card-header-action">
                                <div class="card-header-btn">
                                    <a class="btn btn-sm btn-primary" href="{{ route('customerRejections.index') }}">
                                        <i class="fa fa-arrow-left"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr class="mt-0">

                        {{-- Step 1: Filter by Product --}}
                        <form method="GET" action="{{ route('customerRejections.create') }}">
                            <div class="card-body general-info">
                                <div class="row d-flex align-items-center">
                                    {{-- Customer Select --}}
                                    <div class="col-lg-3 mb-3">
                                        <label for="customer_id" class="form-label">Select Customer</label>
                                        <select class="form-control customer_id" name="customer_id">
                                            <option value="">Select Customer</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ (string)old('customer_id', request('customer_id', isset($rejection) ? $rejection->customer_id : '')) === (string)$customer->id ? 'selected' : '' }}>
                                                    {{ $customer->first_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Product Select --}}
                                    <div class="col-lg-3 mb-3">
                                        <label for="product_id" class="form-label">Select Product</label>
                                        <select class="form-control product_id" name="product_id">
                                            <option value="">Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ (string)old('product_id', request('product_id', isset($rejection) ? $rejection->product_id : '')) === (string)$product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Quantity --}}
                                    <div class="col-md-3 mb-3">
                                        <label for="quantity" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" value="{{ request('quantity') }}" required/>
                                    </div>

                                    {{-- Filter Button --}}
                                    <div class="col-md-3 mb-3 mt-3">
                                        <input type="submit" class="btn btn-primary"/>
                                    </div>
                                </div>
                            </div>
                        </form>

                        {{-- Step 2: Customer Rejection Form --}}
                        <form action="{{ route('customerRejections.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="customer_id" value="{{ request('customer_id') }}">
                            <input type="hidden" name="productId" value="{{ request('product_id') }}">
                            <input type="hidden" name="quantity" value="{{ request('quantity') }}">

                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Spare Parts</th>
                                            <th>Type</th>
                                            <th>Size</th>
                                            <th>Weight</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(request('product_id') && !$spareParts->isEmpty())
                                            @foreach ($spareParts as $part)
                                                <tr>
                                                    <td>{{ $part->name }}</td>
                                                    <td>{{ $part->type }}</td>
                                                    <td>{{ $part->size }}</td>
                                                    <td>{{ $part->weight }}</td>
                                                    <td>
                                                        <input type="number" class="form-control" name="spare_parts[{{ $part->id }}][quantity]" value="{{ $part->quantity ?? request('quantity') }}" />
                                                        <input type="hidden" name="spare_parts[{{ $part->id }}][id]" value="{{ $part->id }}" />
                                                        <input type="hidden" name="spare_parts[{{ $part->id }}][type]" value="{{ $part->type }}" />
                                                        <input type="hidden" name="spare_parts[{{ $part->id }}][size]" value="{{ $part->size }}" />
                                                        <input type="hidden" name="spare_parts[{{ $part->id }}][weight]" value="{{ $part->weight }}" />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center">Please select a product to view spare parts.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            {{-- Note and Submit --}}
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="note" class="form-label">Note</label>
                                        <textarea class="form-control" rows="6" name="note">{{ old('note') }}</textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-8">
                                        <button type="submit" class="btn btn-primary mt-2 mb-3">
                                            <i class="fa-solid fa-floppy-disk me-2"></i>{{ isset($rejection) ? 'Update' : 'Submit' }}
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.customer_id, .product_id').select2({
                placeholder: "Search or select",
                allowClear: true,
                width: '100%',
                theme: "bootstrap-5"
            });
        });
    </script>
@endpush
