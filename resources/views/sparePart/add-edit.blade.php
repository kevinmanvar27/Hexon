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
                                <h5 class="card-title">New Part</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('spareParts.index') }}">
                                            <i class="fa fa-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <form method="POST" action="{{ isset($sparePart) ? route('spareParts.update', $sparePart) : route('spareParts.store') }}">
                            @csrf
                            @if(isset($sparePart))
                                @method('PUT')
                            @endif
                                <div class="card-body general-info">
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="name" class="fw-semibold">Name</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="text" name="name" placeholder="Name" class="form-control"  id="name"  value="{{ old('name', $sparePart->name ?? '') }}">
                                            </div>
                                            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="type" class="fw-semibold">Type</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="text" name="type" placeholder="Type" class="form-control"  id="type"  value="{{ old('type', $sparePart->type ?? '') }}">
                                            </div>
                                            @error('type')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="size" class="fw-semibold">Size</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="text" name="size" placeholder="Size" class="form-control" id="size" value="{{ old('size', $sparePart->size ?? '') }}" >
                                            </div>
                                            @error('size')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="weight" class="fw-semibold">Weight</label>
                                        </div>  
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="number" step='0.001' name="weight" placeholder="Weight" class="form-control"  id="weight"  value="{{ old('weight', $sparePart->weight ?? '') }}">
                                            </div>
                                            @error('weight')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="unit" class="fw-semibold">Unit</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="text" name="unit" placeholder="Unit" class="form-control" id="unit" value="{{ old('unit', $sparePart->unit ?? '') }}">
                                            </div>
                                            @error('unit')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="qty" class="fw-semibold">Quantity</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="number" name="qty" placeholder="Quantity" class="form-control" id="qty" value="{{ old('qty', $sparePart->qty ?? '') }}">
                                            </div>                                            
                                            @error('qty')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="minimum_qty" class="fw-semibold">Minimum Quantity</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="number" name="minimum_qty" placeholder="Quantity" class="form-control" id="minimum_qty" value="{{ old('minimum_qty', $sparePart->minimum_qty ?? '') }}">
                                            </div>
                                            @error('minimum_qty')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="rate" class="fw-semibold">Rate</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <input type="number" step='0.001' name="rate" placeholder="Rate" class="form-control" id="rate" value="{{ old('rate', $sparePart->rate ?? '') }}">
                                            </div>
                                            @error('rate')<div class="text-danger">{{ $message }}</div>@enderror
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