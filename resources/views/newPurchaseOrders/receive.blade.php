@extends('layouts.app')

@section('content')

    <main class="nxl-container">
        <div class="nxl-content">
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
                                <h5 class="card-title">Receive Purchase Order</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('newPurchaseOrders.index') }}">
                                            <i class="fa fa-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <form action="{{ route('newPurchaseOrders.receive.store', $purchaseOrder->id) }}" method="POST">
                                @csrf
                                <div class="card-body general-info">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label for="invoice" class="form-label">Invoice #</label>
                                            <input type="text" class="form-control" id="invoice" value="{{ $purchaseOrder->invoice }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="client_name" class="form-label">Client Name</label>
                                            <input type="text" class="form-control" id="client_name" value="{{ $purchaseOrder->customer->first_name }}" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="status" class="form-label">Status</label>
                                            <input type="text" class="form-control" id="status" value="{{ $purchaseOrder->status }}" readonly>
                                        </div>
                                    </div>
                
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Spare Part</th>
                                                    <th>Total Quantity</th>
                                                    <th>Total Received Quantity</th>
                                                    <th>Received Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($purchaseOrder->items as $item)
                                                    @php
                                                        $totalReceivedQty = $item->quantity - $item->remaining_quantity;
                                                        $isFullyReceived = $item->quantity == $totalReceivedQty;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $item->sparePart->name ?? 'N/A' }}</td>
                                                        <td>
                                                            <input type="number" class="form-control" value="{{ $item->quantity }}" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" value="{{ $totalReceivedQty }}" readonly>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="received_quantity[{{ $item->id }}]" 
                                                                class="form-control" 
                                                                min="0" 
                                                                max="{{ $item->remaining_quantity }}" 
                                                                value="0" 
                                                                {{ $isFullyReceived ? 'disabled' : '' }} 
                                                                placeholder="{{ $isFullyReceived ? 'Fully received' : 'Enter received qty' }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                
                                    <button type="submit" class="btn btn-primary mt-3">Save Received Quantities</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection