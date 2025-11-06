@extends('layouts.app')

@section('content')

    <main class="nxl-container">
        <div class="nxl-content">   
            <div class="main-content">
                <div class="row">
                    <div class="row mb-3">
                        <!-- [Leads] start -->
                        <div class="col-xxl-12">
                            @session('success')
                                <div class="alert alert-success" role="alert"> 
                                    {{ session('success') }}
                                </div>
                            @endsession
                            <div class="card stretch stretch-full">
                                <div class="card-header">
                                    <h5 class="card-title">Parts</h5>
                                    <div class="card-header-action">                      
                                        @can('part-create')
                                            <a class="btn btn-success btn-sm" href="{{ route('spareParts.create') }}">
                                                <i class="fa fa-plus"></i> Add New Part
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                                <div class="card-body custom-card-action p-0">
                                    <div class="table-responsive">
                                        {{ $dataTable->table() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- [Leads] end -->
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="modal fade" id="partModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Parts Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="partDetails">Loading...</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    
    <script>
        $(document).on("click", ".showPart", function () {
        var id = $(this).data("id");

            $.ajax({
                url: "{{ url('spareParts') }}/" + id, 
                type: "GET",
                success: function (data) {
                    // assuming your controller returns JSON
                    let html = `
                        <table class="table table-striped">
                            <tr>
                                <td>Name:</td>
                                <td> ${data.name}</td>
                            </tr>
                            <tr>
                                <td>Type:</td>
                                <td> ${data.type}</td>
                            </tr>
                            <tr>
                                <td>Size:</td>
                                <td> ${data.size}</td>
                            </tr>
                            <tr>
                                <td>Weight:</td>
                                <td> ${data.weight}</td>
                            </tr>
                            <tr>
                                <td>Quantity:</td>
                                <td> ${data.qty}</td>
                            </tr>
                            <tr>
                                <td>Minimum Quantity:</td>
                                <td> ${data.minimum_qty}</td>
                            </tr>
                            <tr>
                                <td>Rate:</td>
                                <td> ${data.rate}</td>
                            </tr>
                            <tr>
                                <td>Unit:</td>
                                <td> ${data.unit}</td>
                            </tr>
                        </table>
                    `;
                    $("#partDetails").html(html);
                    $("#partModal").modal("show");
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    $("#partDetails").html("<p class='text-danger'>Failed to load data.</p>");
                }
            });
        });

    </script>
@endpush