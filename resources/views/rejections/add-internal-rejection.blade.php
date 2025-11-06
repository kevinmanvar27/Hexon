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
                                <h5 class="card-title">{{ isset($rejection) ? 'Edit' : 'Add' }} Internal Rejection</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('rejections.index') }}">
                                            <i class="fa fa-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <form method="POST" action="{{ isset($rejection) ? route('rejections.update', $rejection->id) : route('rejections.store') }}">
                            @csrf
                            @if(isset($rejection))
                                @method('PUT')
                            @endif
                                <div class="card-body general-info">
                                    @if(!isset($rejection))
                                    <button id="addTableRow" type="button" class="btn btn-sm btn-success mb-3"><i class="fa fa-plus"></i> Add Row</button>
                                    @endif
                                    <div class="row align-items-center">
                                        <div class="col-lg-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="myTable">
                                                    <thead>
                                                        <tr>
                                                            <th style="min-width: 250px;">Part Name</th>
                                                            <th style="min-width: 150px;">Quantity</th>
                                                            <th style="min-width: 150px;">Reason</th>
                                                            @if(!isset($rejection))
                                                            <th>Action</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(isset($rejection))
                                                        <tr>
                                                            <td>
                                                                <select class="form-control spare_part_id" name="spare_part_id">
                                                                    <option value="">Select Spare Part</option>
                                                                    @foreach($spareParts as $spare_part)
                                                                        <option value="{{ $spare_part->id }}" {{ (isset($rejection) && $rejection->spare_part_id == $spare_part->id) ? 'selected' : '' }}>{{ $spare_part->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td><input type="number" class="form-control" name="qty" value="{{ $rejection->qty ?? 0 }}"></td>
                                                            <td><input type="text" class="form-control" name="reason" placeholder="Reason" value="{{ $rejection->reason ?? '' }}"></td>
                                                        </tr>
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
                                                            <td><input type="number" class="form-control" name="qty[]" value="0"></td>
                                                            <td><input type="text" class="form-control" name="reason[]" placeholder="Reason"></td>
                                                            <td><button class="btn btn-danger btn-md deleteRow"><i class="fa fa-trash"></i></button></td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                    <tfooter>
                                                        </tfooter>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <button type="submit" class="btn btn-primary mt-2 mb-3">
                                                    <i class="fa-solid fa-floppy-disk me-2"></i>
                                                    {{ isset($rejection) ? 'Update' : 'Submit' }}
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

    @if(!isset($rejection))
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
                    <td><input type="number" class="form-control" name="qty[]" value="0"></td>
                    <td><input type="text" class="form-control" name="reason[]" placeholder="Reason"></td>
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
    @endif
</script>
@endpush