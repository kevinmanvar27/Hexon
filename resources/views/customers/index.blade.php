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
                                    <h5 class="card-title">Customers</h5>
                                    <div class="card-header-action">    
                                        @can('customer-create')
                                            <a class="btn btn-success btn-sm" href="{{ route('customers.create') }}">
                                                <i class="fa fa-plus"></i> Add New Customer
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
    <div class="modal fade" id="customerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Customer Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="customerDetails">Loading...</div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $(document).on("click", ".showCustomer", function () {
        var id = $(this).data("id");

            $.ajax({
                url: "/customers/" + id, // your show route
                type: "GET",
                success: function (data) {
                    // assuming your controller returns JSON
                    let html = `
                    
                        <table class="table table-striped">
                            <tr>
                                <td>First Name:</td>
                                <td> ${data.first_name}</td>
                            </tr>
                            <tr>
                                <td>Last Name:</td>
                                <td> ${data.last_name}</td>
                            </tr>
                            <tr>
                                <td>Email:</td>
                                <td> ${data.email}</td>
                            </tr>
                            <tr>
                                <td>User Type:</td>
                                <td> ${data.user_type}</td>
                            </tr>
                            <tr>
                                <td>Business Name:</td>
                                <td> ${data.business_name}</td>
                            </tr>
                            <tr>
                                <td>Address:</td>
                                <td> ${data.address}</td>
                            </tr>
                            <tr>
                                <td>City:</td>
                                <td> ${data.city}</td>
                            </tr>
                            <tr>
                                <td>State:</td>
                                <td> ${data.state}</td>
                            </tr>
                            <tr>
                                <td>State Code:</td>
                                <td> ${data.state_code}</td>
                            </tr>
                            <tr>
                                <td>GSTIN:</td>
                                <td> ${data.GSTIN}</td>
                            </tr>
                            <tr>
                                <td>Bank Name:</td>
                                <td> ${data.bank_name}</td>
                            </tr>
                            <tr>
                                <td>Bank Account No:</td>
                                <td> ${data.bank_account_no}</td>
                            </tr>
                            <tr>
                                <td>IFSC Code:</td>
                                <td> ${data.ifsc_code}</td>
                            </tr>
                        </table>
                    `;
                    $("#customerDetails").html(html);
                    $("#customerModal").modal("show");
                },
                error: function () {
                    $("#customerDetails").html("<p class='text-danger'>Failed to load data.</p>");
                    $("#customerModal").modal("show");
                }
            });
        });

    </script>
@endpush