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
                                    {{ $value }}
                                </div>
                            @endsession
                            <div class="card stretch stretch-full">
                                <div class="card-header">
                                    <h5 class="card-title">Users</h5>
                                    <div class="card-header-action">                      
                                        @can('user-create')
                                            <a class="btn btn-success btn-sm" href="{{ route('users.create') }}">
                                                <i class="fa fa-plus"></i> Create New User
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
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="userDetails">Loading...</div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    
    <script>
        $(document).on("click", ".showUser", function () {
        var id = $(this).data("id");

            $.ajax({
                url: "{{ url('users') }}/" + id, 
                type: "GET",
                success: function (data) {
                    // assuming your controller returns JSON
                    let html = `
                    <table class="table table-striped">
                        <tr><td>Name:</td><td>${data.name ?? ''}</td></tr>
                        <tr><td>Email:</td><td>${data.email ?? ''}</td></tr>
                        <tr><td>Username:</td><td>${data.username ?? ''}</td></tr>
                        <tr><td>User Photo:</td><td><img src="${data.user_photo ? '/storage/users/' + data.user_photo : '/uploads/user.png'}" height="50" width="50" onerror="this.src='/uploads/user.png'"></td></tr>
                        <tr><td>Contact Number:</td><td>${data.contact_number ?? ''}</td></tr>
                        <tr><td>Birthdate:</td><td>${data.birthdate ?? ''}</td></tr>
                        <tr><td>Roles:</td><td>${data.roles ? data.roles.map(r => r.name).join(', ') : ''}</td></tr>
                    </table>
                `;
                    $("#userDetails").html(html);
                    $("#userModal").modal("show");
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    $("#userDetails").html("<p class='text-danger'>Failed to load data.</p>");
                }
            });
        });

    </script>
@endpush