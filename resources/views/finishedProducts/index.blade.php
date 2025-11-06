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
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Finished Products</h5>
                                <div class="card-header-action">                      
                                    @can('finished-product-create')
                                        <a class="btn btn-success btn-sm" href="{{ route('finishedProducts.create') }}">
                                            <i class="fa fa-plus"></i> Add New Finished Product
                                        </a>
                                    @endcan
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    {{ $dataTable->table() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </main>

    <div class="modal fade" id="finishedProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="finishedProductDetails">Loading...</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $(document).on("click", ".showFinishedProduct", function () {
        var id = $(this).data("id");

            $.ajax({
                url: "/finishedProducts/" + id,
                type: "GET",
                success: function (data) {
                    let html = `                    
                        <table class="table table-striped">
                            <tr>
                                <td>Product:</td>
                                <td>${data.product?.name ?? ''}</td>
                            </tr>
                            <tr>
                                <td>Quantity:</td>
                                <td>${data.qty}</td>
                            </tr>
                            <tr>
                                <td>Created By:</td>
                                <td>${data.created_by?.name ?? ''}</td>
                            </tr>
                            <tr>
                                <td>Created At:</td>
                                <td>${data.created_at}</td>
                            </tr>
                        </table>
                    `;

                    $("#finishedProductDetails").html(html);
                    $("#finishedProductModal").modal("show");
                },
                error: function () {
                    $("#finishedProductDetails").html("<p class='text-danger'>Failed to load data.</p>");
                    $("#finishedProductModal").modal("show");
                }
            });
        });

    </script>
@endpush