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
                                    <h5 class="card-title">New Purchase Order</h5>
                                    <div class="card-header-action">    
                                        @can('purchaseOrder-create')                  
                                            <a class="btn btn-success btn-sm" href="{{ route('newPurchaseOrders.create') }}">
                                                <i class="fa fa-plus"></i> Add New Purchase Orders
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
    <div class="modal fade" id="newPurchaseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="newPurchaseDetails">Loading...</div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $(document).on("click", ".shownewPurchase", function () {
        var id = $(this).data("id");

            $.ajax({
                url: "/newPurchaseOrders/" + id,
                type: "GET",
                success: function (data) {
                    let html = `
                    
                        <table class="table table-striped">
                            <tr>
                                <td>Invoice:</td>
                                <td> ${data.invoice}</td>
                            </tr>
                            <tr>
                                <td>Customer:</td>
                                <td>${data.customer?.first_name || '-'}</td>
                            </tr>
                            <tr>
                                <td>Status:</td>
                                <td>${data.status}</td>
                            </tr>
                            <tr>
                                <td>PO Revision & Date:</td>
                                <td>${data.po_revision_and_date}</td>
                            </tr>
                            <tr>
                                <td>Reason of Revision:</td>
                                <td>${data.reason_of_revision}</td>
                            </tr>
                            <tr>
                                <td>Quotation Ref No:</td>
                                <td>${data.quotation_ref_no}</td>
                            </tr>
                            <tr>
                                <td>Remarks:</td>
                                <td>${data.remarks}</td>
                            </tr>
                            <tr>
                                <td>P.R. No:</td>
                                <td>${data.pr_no}</td>
                            </tr>
                            <tr>
                                <td>P.R. Date:</td>
                                <td>${data.pr_date}</td>
                            </tr>
                            <tr>
                                <td>Address:</td>
                                <td>${data.address}</td>
                            </tr>
                            <tr>
                                <td>Note:</td>
                                <td>${data.note}</td>
                            </tr>
                        </table>
                    `;
                    $("#newPurchaseDetails").html(html);
                    $("#newPurchaseModal").modal("show");
                },
                error: function () {
                    $("#newPurchaseDetails").html("<p class='text-danger'>Failed to load data.</p>");
                    $("#newPurchaseModal").modal("show");
                }
            });
        });

    </script>
@endpush