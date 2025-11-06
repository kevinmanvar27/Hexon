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
                                    <h5 class="card-title">Products</h5>
                                    <div class="card-header-action">                      
                                        @can('product-create')
                                            <a class="btn btn-success btn-sm" href="{{ route('products.create') }}">
                                                <i class="fa fa-plus"></i> Add New Product
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
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="productDetails">Loading...</div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $(document).on("click", ".showProduct", function () {
        var id = $(this).data("id");

            $.ajax({
                url: "/products/" + id, // your show route
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
                                <td>Valve Type:</td>
                                <td> ${data.valve_type}</td>
                            </tr>
                            <tr>
                                <td>Product Code:</td>
                                <td> ${data.product_code}</td>
                            </tr>
                            <tr>
                                <td>Actuation:</td>
                                <td> ${data.actuation}</td>
                            </tr>
                            <tr>
                                <td>Pressure Rating:</td>
                                <td> ${data.pressure_rating}</td>
                            </tr>
                            <tr>
                                <td>Valve Size:</td>
                                <td> ${data.valve_size}</td>
                            </tr>
                            <tr>
                                <td>Valve Size Rate:</td>
                                <td> ${data.valve_size_rate}</td>
                            </tr>
                            <tr>
                                <td>Media:</td>
                                <td> ${data.media}</td>
                            </tr>
                            <tr>
                                <td>Flow:</td>
                                <td> ${data.flow}</td>
                            </tr>
                            <tr>
                                <td>SKU Code:</td>
                                <td> ${data.sku_code}</td>
                            </tr>
                            <tr>
                                <td>MRP:</td>
                                <td> ${data.mrp}</td>
                            </tr>
                            <tr>
                                <td>Media Temperature:</td>
                                <td> ${data.media_temperature}</td>
                            </tr>
                            <tr>
                                <td>Media Temperature Rate:</td>
                                <td> ${data.media_temperature_rate}</td>
                            </tr>
                            <tr>
                                <td>Body Material:</td>
                                <td> ${data.body_material}</td>
                            </tr>
                            <tr>
                                <td>HSN Code:</td>
                                <td> ${data.hsn_code}</td>
                            </tr>
                            <tr>
                                <td>Primary Material Of Construction:</td>
                                <td> ${data.primary_material_of_construction}</td>
                            </tr>
                        </table>
                    `;
                    $("#productDetails").html(html);
                    $("#productModal").modal("show");
                },
                error: function () {
                    $("#productDetails").html("<p class='text-danger'>Failed to load data.</p>");
                    $("#productModal").modal("show");
                }
            });
        });

    </script>
@endpush