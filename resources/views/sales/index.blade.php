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
                                    <h5 class="card-title">Sales</h5>
                                    <div class="card-header-action">                      
                                        @can('sales-create')
                                            <a class="btn btn-success btn-sm" href="{{ route('sales.create') }}">
                                                <i class="fa fa-plus"></i> Add Sales
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
    <div class="modal fade" id="saleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sale Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="saleDetails">Loading...</div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
    <script>
        $(document).on("click", ".showSale", function () {
        var id = $(this).data("id");

            $.ajax({
                url: "/sales/" + id,
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
                                <td>Create Date:</td>
                                <td>${data.create_date}</td>
                            </tr>
                            <tr>
                                <td>Due Date:</td>
                                <td>${data.due_date}</td>
                            </tr>
                            <tr>
                                <td>Order NO:</td>
                                <td>${data.orderno}</td>
                            </tr>
                            <tr>
                                <td>L.R. NO:</td>
                                <td>${data.lrno}</td>
                            </tr>
                            <tr>
                                <td>Transport:</td>
                                <td>${data.transport}</td>
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
                    $("#saleDetails").html(html);
                    $("#saleModal").modal("show");
                },
                error: function () {
                    $("#saleDetails").html("<p class='text-danger'>Failed to load data.</p>");
                    $("#saleModal").modal("show");
                }
            });
        });

    </script>
@endpush