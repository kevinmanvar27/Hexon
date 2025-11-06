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
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Edit Role:</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('roles.index') }}">
                                            <i class="fa fa-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <form method="POST" action="{{ route('roles.update', $role->id) }}">
                            @csrf
                            @method('PUT')
                                <div class="card-body general-info">
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="nameInput" class="fw-semibold">Name: </label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-user"></i></div>
                                                <input type="text" name="name" class="form-control" placeholder="Name"  value="{{ $role->name }}" id="nameInput">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="permInput" class="fw-semibold">Permissions: </label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="accordion" id="permissionsAccordion">
                                                @foreach($permissions as $group => $perms)
                                                    <div class="accordion-item mb-2">
                                                        <h2 class="accordion-header" id="heading-{{ $group }}">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $group }}" aria-expanded="false" aria-controls="collapse-{{ $group }}">
                                                                {{ ucfirst($group) }} Permissions
                                                            </button>
                                                        </h2>
                                                        <div id="collapse-{{ $group }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $group }}" data-bs-parent="#permissionsAccordion">
                                                            <div class="accordion-body">
                                                                @foreach($perms as $perm)
                                                                    <label>
                                                                        <input type="checkbox" name="permission[{{ $perm->id }}]" value="{{ $perm->id }}" class="me-1"
                                                                            {{ in_array($perm->id, $rolePermissions) ? 'checked' : '' }}>
                                                                        {{ $perm->name }}
                                                                    </label><br>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>      
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="roleInput" class="fw-semibold"></label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <button type="submit" class="btn btn-primary mt-2 mb-3">
                                                    <i class="fa-solid fa-floppy-disk me-2"></i>
                                                    Submit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </main>

@endsection