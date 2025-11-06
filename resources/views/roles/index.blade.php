@extends('layouts.app')

@section('content')

    <main class="nxl-container">
        <div class="nxl-content">   
            <div class="main-content">
                <div class="row">
                    <!-- [Leads] start -->
                    <div class="col-xxl-12">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Roles</h5>
                                <div class="card-header-action">
                                    @can('role-create')
                                        <a class="btn btn-success btn-sm" href="{{ route('roles.create') }}">
                                            <i class="fa fa-plus"></i> Create New Role
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
    </main>
    <!-- Product Details Modal -->
    <div class="modal fade" id="roleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Role Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="roleDetails">Loading...</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
        $(document).on("click", ".showRole", function () {
        var id = $(this).data("id");

            $.ajax({
                url: "/roles/" + id,
                type: "GET",
                success: function (data) {
                    const role = data.role || {};
                    const perms = data.permissions || [];

                    let permHtml = perms.length
                        ? perms.map(p => `<span class="badge bg-secondary me-1 mb-2 mt-2">${p.name}</span>`).join('')
                        : '<em>No permissions</em>';


                    let html = `
                        <table class="table table-striped">
                            <tr><td>Name:</td><td>${role.name ?? ''}</td></tr>
                            <tr><td>Permissions:</td><td>${permHtml}</td></tr>
                        </table>
                    `;

                    $("#roleDetails").html(html);
                    $("#roleModal").modal("show");
                },
                error: function () {
                    $("#roleDetails").html("<p class='text-danger'>Failed to load data.</p>");
                    $("#roleModal").modal("show");
                }
            });
        });

    </script>
@endpush    