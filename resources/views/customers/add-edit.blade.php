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
                                <h5 class="card-title">New Customer:</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('customers.index') }}">
                                            <i class="fa fa-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <form method="POST" action="{{ isset($customer) ? route('customers.update', $customer->id) : route('customers.store') }}" enctype="multipart/form-data">
                            @csrf
                            @if(isset($customer))
                                @method('PUT')
                            @endif
                                <div class="card-body general-info">
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-6">
                                            <label for="first_name" class="fw-semibold">First Name: </label>
                                            <input type="text" name="first_name" placeholder="First Name" class="form-control"  id="first_name"  value="{{ old('first_name', $customer->first_name ?? '') }}">
                                            @error('first_name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="last_name" class="fw-semibold">Last Name: </label>
                                            <input type="text" name="last_name" placeholder="Last Name" class="form-control"  id="last_name"  value="{{ old('last_name', $customer->last_name ?? '') }}">
                                            @error('last_name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-6">
                                            <label for="email" class="fw-semibold">Email: </label>
                                            <input type="email" name="email" placeholder="Email" class="form-control"  id="email"  value="{{ old('email', $customer->email ?? '') }}">
                                            @error('email')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="user_type" class="fw-semibold">User Type: </label>
                                            <select name="user_type" id="user_type" class="form-select">
                                                <option value="supplier" {{ old('user_type', $customer->user_type ?? '') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                                                <option value="customer" {{ old('user_type', $customer->user_type ?? '') == 'customer' ? 'selected' : '' }}>Customer</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="city" class="fw-semibold">City: </label>
                                            <input type="text" name="city" placeholder="City" class="form-control"  id="city"  value="{{ old('city', $customer->city ?? '') }}">
                                            @error('city')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="state" class="fw-semibold">State: </label>
                                            <input type="text" name="state" placeholder="State" class="form-control"  id="state"  value="{{ old('state', $customer->state ?? '') }}">
                                            @error('state')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="state_code" class="fw-semibold">State Code: </label>
                                            <input type="text" name="state_code" placeholder="State Code" class="form-control"  id="state_code"  value="{{ old('state_code', $customer->state_code ?? '') }}">
                                            @error('state_code')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">                                    
                                        <div class="col-lg-12">
                                            <label for="address" class="fw-semibold">Address: </label>
                                            <textarea name="address" id="address" class="form-control">{{ old('address', $customer->address ?? '') }}</textarea>
                                            @error('address')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">                                    
                                        <div class="col-lg-4">
                                            <label for="gstin" class="fw-semibold">GSTIN: </label>
                                            <input type="text" name="GSTIN" placeholder="GSTIN" class="form-control"  id="gstin"  value="{{ old('GSTIN', $customer->GSTIN ?? '') }}">
                                            @error('GSTIN')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="business_name" class="fw-semibold">Business Name: </label>
                                            <input type="text" name="business_name" placeholder="Business Name" class="form-control"  id="business_name"  value="{{ old('business_name', $customer->business_name ?? '') }}">
                                            @error('business_name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="bank_name" class="fw-semibold">Bank Name: </label>
                                            <input type="text" name="bank_name" placeholder="Bank Name" class="form-control"  id="bank_name"  value="{{ old('bank_name', $customer->bank_name ?? '') }}">
                                            @error('bank_name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">                                    
                                        <div class="col-lg-6">
                                            <label for="bank_account_no" class="fw-semibold">Bank Account No: </label>
                                            <input type="text" name="bank_account_no" placeholder="Bank Account No" class="form-control"  id="bank_account_no"  value="{{ old('bank_account_no', $customer->bank_account_no ?? '') }}">
                                            @error('bank_account_no')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="ifsc_code" class="fw-semibold">IFSC Code: </label>
                                            <input type="text" name="ifsc_code" placeholder="IFSC Code" class="form-control"  id="ifsc_code"  value="{{ old('ifsc_code', $customer->ifsc_code ?? '') }}">
                                            @error('ifsc_code')<div class="text-danger">{{ $message }}</div>@enderror
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