@extends('layouts.app')

@section('content')

    <main class="nxl-container">
        <div class="nxl-content">   
            <div class="main-content">
                <div class="row">
                    <div class="row mb-3">
                        <!-- [Leads] start -->
                        <div class="col-xxl-12">
                            @if(session('success'))
                                <div class="alert alert-success" role="alert"> 
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="card stretch stretch-full">
                                <div class="card-header">
                                    <h5 class="card-title">Rejection Prorducts</h5>
                                    <div class="card-header-action">             
                                        @can('customerRejections-create')         
                                            <a class="btn btn-success btn-sm" href="{{ route('customerRejections.create') }}">
                                                <i class="fa fa-plus"></i> Add Customer Rejection
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                                <div class="card-body custom-card-action p-0">
                                    <div class="table-responsive">
                                        {!! $dataTable->table() !!}
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
    <!-- Product Details Modal -->
    <div class="modal fade" id="customerRejectionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Customer Rejection Spare Parts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="customerRejectionDetails">Loading...</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
        {{-- DataTable Scripts --}}
        {!! $dataTable->scripts() !!}
        <script>
            $(document).on("click", ".showCustomerRejectionParts", function () {
            var id = $(this).data("id");

            $.ajax({
                url: "/customerRejections/" + id,
                type: "GET",
                success: function (data) {
                    let html = `<table class="table table-striped">
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Size</th>
                                        <th>Weight</th>
                                        <th>Quantity</th>
                                    </tr>`;

                    // Loop through spare parts
                    if (data.spare_parts && data.spare_parts.length > 0) {
                        data.spare_parts.forEach(function(part) {
                            html += `
                                <tr>
                                    <td>${part.name || 'N/A'}</td>
                                    <td>${part.type}</td>
                                    <td>${part.size}</td>
                                    <td>${part.weight}</td>
                                    <td>${part.quantity}</td>
                                </tr>
                            `;
                        });
                    } else {
                        html += `<tr><td colspan="5">No spare parts found.</td></tr>`;
                    }

                    html += `</table>`;

                    $("#customerRejectionDetails").html(html);
                    $("#customerRejectionModal").modal("show");
                },
                error: function (xhr) {
                    console.error(xhr.responseText); // log the exact error
                    $("#customerRejectionDetails").html("<p class='text-danger'>Failed to load data.</p>");
                    $("#customerRejectionModal").modal("show");
                }
            });
        });
        </script>
@endpush
