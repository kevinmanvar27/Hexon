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
                                    <h5 class="card-title">Internal Rejection Parts</h5>
                                    <div class="card-header-action">    
                                        @can('internalRejections-create')           
                                            <a class="btn btn-success btn-sm" href="{{ route('rejections.create') }}">
                                                <i class="fa fa-plus"></i> Add Internal Rejection
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
    {{-- DataTable Scripts --}}
    {!! $dataTable->scripts() !!}
@endpush
