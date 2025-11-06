@extends('layouts.app')

@section('content')

    <main class="nxl-container">
        <div class="nxl-content">
            <!-- [ Main Content ] start -->
            <div class="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        @if ($errors->any())
                            <div class="alert alert-danger mt-2">
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
                                <h5 class="card-title">Edit User:</h5>
                                <div class="card-header-action">
                                    <div class="card-header-btn">         
                                        <a class="btn btn-sm btn-primary" href="{{ route('users.index') }}">
                                            <i class="fa fa-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <hr class="mt-0">
                            <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                                <div class="card-body general-info">
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="fullnameInput" class="fw-semibold">Name: </label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-user"></i></div>
                                                <input type="text" name="name" placeholder="Name" class="form-control"  id="fullnameInput"  value="{{ old('name', $user->name) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="username" class="fw-semibold">Username: </label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-user"></i></div>
                                                <input type="text" name="username" placeholder="Username" class="form-control"  id="username"  value="{{ old('username', $user->username) }}">
                                            </div>
                                            @error('username')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="mailInput" class="fw-semibold">Email: </label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-mail"></i></div>
                                                <input type="email" name="email" placeholder="Email" class="form-control" id="mailInput" value="{{ old('email', $user->email) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="contact_number" class="fw-semibold">Contact Number</label>
                                        </div>  
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-phone"></i></div>
                                                <input type="phone" name="contact_number" placeholder="Contact Number" class="form-control"  id="contact_number"  value="{{ old('contact_number', $user->contact_number) }}">
                                            </div>
                                            @error('contact_number')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    @if(isset($user->user_photo))
                                        <div class="row mb-4">  
                                            <div class="col-lg-4">
                                                <label for="user_photo" class="form-label">Old User Photo</label>
                                            </div>
                                            <div class="col-md-6">
                                                <a href="{{ asset('storage/users/' . $user->user_photo) }}" target="_blank">
                                                    <img src="{{ asset('storage/users/' . $user->user_photo) }}" alt="{{$user->user_photo}}" width="50">
                                                </a>                    
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="user_photo" class="form-label">Upload Photo</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-image"></i></div>
                                                <input type="file" name="user_photo" id="user_photo" class="form-control" accept="image/*">
                                            </div>
                                            @error('user_photo')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="birthdate" class="fw-semibold">Birthdate: </label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-calendar"></i></div>
                                                <input type="date" name="birthdate" placeholder="Birthdate" class="form-control" id="birthdate" value="{{ old('birthdate', $user->birthdate) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="passwordInput" class="fw-semibold">Password: </label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-key"></i></div>
                                                <input type="password" name="password" placeholder="Password" class="form-control" id="passwordInput">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="confirmPasswordInput" class="fw-semibold">Confirm Password: </label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-key"></i></div>
                                                <input type="password" name="confirm-password" placeholder="Confirm Password" class="form-control" id="confirmPasswordInput">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="roleInput" class="fw-semibold">Role:</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-user"></i></div>
                                                    <select name="roles[]" class="form-control" multiple id="roleInput">
                                                        @foreach ($roles as $value => $label)
                                                            <option value="{{ $value }}" {{ isset($userRole[$value]) ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </select>
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