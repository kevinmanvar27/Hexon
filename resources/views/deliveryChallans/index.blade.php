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
                                    <h5 class="card-title">All Delivery Challans</h5>
                                    <div class="card-header-action">    
                                        @can('deliveryChallans-create')                  
                                            <a class="btn btn-success btn-sm" href="{{ route('deliveryChallans.create') }}">
                                                <i class="fa fa-plus"></i> Add New Delivery Challan
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
    <!-- Product Details Modal -->
    <div class="modal fade" id="deliveryChallanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delivery Challan Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="deliveryChallanDetails">Loading...</div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $(document).on("click", ".showdeliveryChallan", function () {
        var id = $(this).data("id");

            $.ajax({
                url: "/deliveryChallans/" + id,
                type: "GET",
                success: function (data) {
                    let html = ` 
                    
                        <table class="table table-striped">
                            <tr>
                                <td>Invoice:</td>   
                                <td> ${data.po_no ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Customer:</td>
                                <td>${data.customer?.first_name || '-'}</td>
                            </tr>
                            <tr>
                                <td>PO Revision & Date:</td>
                                <td>${data.po_revision_and_date ?? '-' ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Reason of Revision:</td>
                                <td>${data.reason_of_revision ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Quotation Ref No:</td>
                                <td>${data.quotation_ref_no ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Remarks:</td>
                                <td>${data.remarks ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>P.R. No:</td>
                                <td>${data.prno ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>P.R. Date:</td>
                                <td>${data.pr_date ?? '-'}</td>
                            </tr>
                            <tr>
                                <td>Prepared By:</td>
                                <td>${data.user?.name ?? '-'}</td>
                            </tr>
                        </table>
                    `;

                    $("#deliveryChallanDetails").html(html);
                    $("#deliveryChallanModal").modal("show");
                },
                error: function () {
                    $("#deliveryChallanDetails").html("<p class='text-danger'>Failed to load data.</p>");
                    $("#deliveryChallanModal").modal("show");
                }
            });
        });

    </script>
@endpush