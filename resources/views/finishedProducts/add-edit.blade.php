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
                        @If (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @If (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">New Finished Product</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('finishedProducts.index') }}">
                                            <i class="fa fa-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <form method="POST" action="{{ isset($finishedProduct) ? route('finishedProducts.update', $finishedProduct->id) : route('finishedProducts.store') }}">
                                @csrf
                                @if(isset($finishedProduct))
                                    @method('PUT')
                                @endif
                                <div class="card-body general-info">
                                    <div class="row align-items-center">
                                        <div class="col-lg-6 mb-4">
                                            <label for="product" class="fw-semibold">Product</label>
                                            <div class="input-group">
                                                <select name="product_id" id="product" class="form-select product">
                                                    <option value="" selected>Select Product</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}" {{ (isset($finishedProduct) && $finishedProduct->product_id == $product->id) ? 'selected' : '' }} >{{ $product->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('product_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label for="qty" class="fw-semibold">Quantity</label>
                                            <div class="input-group">
                                                <input type="number" name="qty" class="form-control" id="qty" value="{{ old('qty', isset($finishedProduct) ? $finishedProduct->qty : '') }}">
                                            </div>
                                            @error('qty')<div class="text-danger">{{ $message }}</div>@enderror
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
        </div>
    </main>
    
@endsection

@push('scripts')
    <script>
        function initSelect2(scope) {
            (scope || $(document)).find('.product').select2({
                placeholder: "Search Product",
                allowClear: true,
                width: '100%',
                theme: "bootstrap-5"
            });
        }

        $(document).ready(function (){
            initSelect2();

            $(document).on('select2:open', function () {
                var searchField = document.querySelector('.select2-container--open .select2-search__field');
                if (searchField) searchField.focus();
            });
        });
    </script>
@endpush
