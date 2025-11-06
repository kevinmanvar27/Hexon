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
                                <h5 class="card-title">New Product</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('products.index') }}">
                                            <i class="fa fa-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <form method="POST" action="{{ isset($product) ? route('products.update', $product->id) : route('products.store') }}">
                            @csrf
                            @if(isset($product))
                                @method('PUT')
                            @endif
                                <div class="card-body general-info">
                                    <div class="row align-items-center">
                                        <div class="col-lg-6 mb-4">
                                            <label for="name" class="fw-semibold">Part Name</label>
                                            <div class="input-group">
                                                <input type="text" name="name" placeholder="Part Name" class="form-control"  id="name"  value="{{ old('name', $product->name ?? '') }}">
                                            </div>
                                            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-6 mb-4">
                                            <label for="valve_type" class="fw-semibold">Valve Type</label>
                                            <div class="input-group">
                                                <input type="text" name="valve_type" placeholder="Valve Type" class="form-control"  id="valve_type"  value="{{ old('valve_type', $product->valve_type ?? '') }}">
                                            </div>
                                            @error('valve_type')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-4">
                                            <label for="product_code" class="fw-semibold">Product Code</label>
                                            <div class="input-group">
                                                <input type="number" name="product_code" placeholder="Product Code" class="form-control" id="product_code" value="{{ old('product_code', $product->product_code ?? '') }}" >
                                            </div>
                                            @error('product_code')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="actuation" class="fw-semibold">Actuation</label>
                                            <div class="input-group">
                                                <input type="text" name="actuation" placeholder="Actuation" class="form-control" id="actuation" value="{{ old('actuation', $product->actuation ?? '') }}" >
                                            </div>
                                            @error('actuation')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="pressure_rating" class="fw-semibold">Pressure Rating</label>
                                            <div class="input-group">
                                                <input type="text" name="pressure_rating" placeholder="Pressure Rating" class="form-control" id="pressure_rating" value="{{ old('pressure_rating', $product->pressure_rating ?? '') }}" >
                                            </div>
                                            @error('pressure_rating')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-4">
                                            <label for="valve_size" class="fw-semibold">Valve Size</label>
                                            <div class="input-group">
                                                <input type="text" name="valve_size" placeholder="Valve Size" class="form-control" id="valve_size" value="{{ old('valve_size', $product->valve_size ?? '') }}" >
                                            </div>
                                            @error('valve_size')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="valve_size_rate" class="fw-semibold">Size</label>
                                            <div class="input-group">
                                                <select name="valve_size_rate" id="valve_size_rate" class="form-select">
                                                    <option value="inch" {{ old('valve_size_rate', $product->valve_size_rate ?? '') == 'inch' ? 'selected' : '' }}>INCH</option>
                                                    <option value="centimeter" {{ old('valve_size_rate', $product->valve_size_rate ?? '') == 'centimeter' ? 'selected' : '' }}>CM</option>
                                                </select>
                                            </div>
                                            @error('valve_size_rate')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="media" class="fw-semibold">Media</label>
                                            <div class="input-group">
                                                <input type="text" name="media" placeholder="Media" class="form-control" id="media" value="{{ old('media', $product->media ?? '') }}" >
                                            </div>
                                            @error('media')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-4">
                                            <label for="sku_code" class="fw-semibold">SKU Code</label>
                                            <div class="input-group">
                                                <input type="text" name="sku_code" placeholder="SKU CODE" class="form-control" id="sku_code" value="{{ old('sku_code', $product->sku_code ?? '') }}" >
                                            </div>
                                            @error('sku_code')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="mrp" class="fw-semibold">MRP</label>
                                            <div class="input-group">
                                                <input type="text" name="mrp" placeholder="MRP" class="form-control" id="mrp" value="{{ old('mrp', $product->mrp ?? '') }}" >
                                            </div>
                                            @error('mrp')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="media_temperature" class="fw-semibold">Media Temperature</label>
                                            <div class="input-group">
                                                <input type="text" name="media_temperature" placeholder="Media Temperature" class="form-control" id="media_temperature" value="{{ old('media_temperature', $product->media_temperature ?? '') }}" >
                                            </div>
                                            @error('media_temperature')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-lg-4 mb-4">
                                            <label for="media_temperature_rate" class="fw-semibold">Temp Rate</label>
                                            <div class="input-group">
                                                <select name="media_temperature_rate" id="media_temperature_rate" class="form-select">
                                                    <option value="FAHRENHEIT" {{ old('media_temperature_rate', $product->media_temperature_rate ?? '') == 'FAHRENHEIT' ? 'selected' : '' }}>FAHRENHEIT</option>
                                                    <option value="CELSIUS" {{ old('media_temperature_rate', $product->media_temperature_rate ?? '') == 'CELSIUS' ? 'selected' : '' }}>CELSIUS</option>
                                                </select>
                                            </div>
                                            @error('media_temperature_rate')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="hsn_code" class="fw-semibold">HSN Code</label>
                                            <div class="input-group">
                                                <input type="text" name="hsn_code" placeholder="HSN code" class="form-control" id="hsn_code" value="{{ old('hsn_code', $product->hsn_code ?? '') }}" >
                                            </div>
                                            @error('hsn_code')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="body_material" class="fw-semibold">Body Material</label>
                                            <div class="input-group">
                                                <input type="text" name="body_material" placeholder="Body Material" class="form-control" id="body_material" value="{{ old('body_material', $product->body_material ?? '') }}" >
                                            </div>
                                            @error('body_material')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row align-items-center">   
                                        <div class="col-lg-4 mb-4">
                                            <label for="flow" class="fw-semibold">Flow</label>
                                            <div class="input-group">
                                                <input type="text" name="flow" placeholder="FLOW" class="form-control" id="flow" value="{{ old('flow', $product->flow ?? '') }}" >
                                            </div>
                                            @error('flow')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-lg-4 mb-4">
                                            <label for="primary_material_of_construction" class="fw-semibold">Primary Material Of Construction</label>
                                            <div class="input-group">
                                                <input type="text" name="primary_material_of_construction" placeholder="Primary Material Of Construction" class="form-control" id="primary_material_of_construction" value="{{ old('primary_material_of_construction', $product->primary_material_of_construction ?? '') }}" >
                                            </div>
                                            @error('primary_material_of_construction')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="d-flex justify-content-between mb-4">
                                    <h5 class="card-title">Product Spare Parts</h5>
                                    <button id="addRow" type="button" class="btn btn-success btn-sm">
                                        <i class="feather-plus"></i> Add Row
                                    </button>
                                    </div>
                                    
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-12">
                                            <div id="newRow">
                                                @if(isset($product) && $product->spareParts->count())
                                                    @foreach($product->spareParts as $part)
                                                        <div id="inputFormRow">
                                                            <div class="input-group mb-3 gx-2 row">
                                                                <div class="col-lg-5 mb-2">
                                                                    <label for="spare_parts">Parts Name</label>
                                                                    <select name="spare_parts[]" class="form-control spare_parts_select" id="spare_parts">
                                                                        <option value="" disabled selected>Search or select part</option>
                                                                        @foreach($spareParts as $sp)
                                                                            <option value="{{ $sp->id }}" 
                                                                                {{ $part->id == $sp->id ? 'selected' : '' }}>
                                                                                {{ $sp->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('spare_parts')<div class="text-danger">{{ $message }}</div>@enderror
                                                                </div>
                                                                <div class="col-lg-5 mb-2">
                                                                    <label for="qty">Quantity</label>
                                                                    <input type="number" name="qty[]" class="form-control"
                                                                        value="{{ $part->pivot->qty }}" id="qty">
                                                                    @error('qty.*')<div class="text-danger">{{ $message }}</div>@enderror
                                                                </div>
                                                                <div class="col-lg-2 input-group-append">
                                                                    <label for="button">Action</label>
                                                                    <button type="button" class="btn btn-danger removeRow" id="button">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div id="inputFormRow">
                                                        <div class="input-group mb-3 gx-2 row">
                                                            <div class="col-lg-5 mb-2">
                                                                <label for="spare_parts">Parts Name</label>
                                                                <select name="spare_parts[]" class="form-control spare_parts_select" id="spare_parts">
                                                                    <option value="" disabled selected>Search or select part</option>
                                                                    @foreach($spareParts as $sp)
                                                                        <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @error('spare_parts')<div class="text-danger">{{ $message }}</div>@enderror
                                                            </div>
                                                            <div class="col-lg-5 mb-2">
                                                                <label for="qty">Quantity</label>
                                                                <input type="number" name="qty[]" class="form-control" value="0" id="qty">
                                                                @error('qty.*')<div class="text-danger">{{ $message }}</div>@enderror
                                                            </div>
                                                            <div class="col-lg-2 input-group-append">
                                                                <label for="button">Action</label>
                                                                <button type="button" class="btn btn-danger removeRow" id="button">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
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
        (scope || $(document)).find('.spare_parts_select').select2({
            placeholder: "Search or select part",
            allowClear: true,
            width: '100%',
            theme: "bootstrap-5"
        });
    }

    $(document).ready(function () {
        initSelect2();

        $("#addRow").on('click', function () {
            var html = '';
            html += '<div class="inputFormRow">';
            html += '<hr class="mt-0">';
            html += '  <div class="input-group mb-3 gx-2 row">';
            html += '    <div class="col-lg-5 mb-2">';
            html +=        '<label for="spare_parts">Parts Name</label>';
            html += '      <select name="spare_parts[]" class="form-control spare_parts_select" id="spare_parts">';
            html += '        <option value="">Search or select part</option>';
            @foreach($spareParts as $sp)
                html += '    <option value="{{ $sp->id }}">{{ $sp->name }}</option>';
            @endforeach
            html += '      </select>';
            html += '    </div>';
            html += '    <div class="col-lg-5 mb-2">';
            html +=        '<label for="qty">Quantity</label>';
            html += '      <input type="number" name="qty[]" class="form-control" value="0" id="qty">';
            html += '    </div>';
            html += '    <div class="col-lg-2 input-group-append">';
            html +=        '<label for="button">Action</label>';
            html += '      <button type="button" class="btn btn-danger removeRow" id="button"><i class="fa fa-trash"></i></button>';
            html += '    </div>';
            html += '  </div>';
            html += '</div>';

            var $row = $(html).appendTo('#newRow');

            // 3) Init Select2 only inside the newly added row
            initSelect2($row);
        });

        // 4) Remove row
        $(document).on('click', '.removeRow', function () {
            $(this).closest('#inputFormRow, .inputFormRow').remove();
        });

        // 5) Autofocus search box when dropdown opens (works with multiple instances)
        $(document).on('select2:open', function () {
            var searchField = document.querySelector('.select2-container--open .select2-search__field');
            if (searchField) searchField.focus();
        });
    });
</script>
@endpush
