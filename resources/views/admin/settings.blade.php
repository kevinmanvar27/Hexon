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
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Site Settings</h5>
                            </div>
                            <hr class="mt-0">
                            <form method="POST" action="{{ route('settings.update') }}"  enctype="multipart/form-data">
                            @csrf
                                <div class="card-body general-info">
                                    @if(isset($settings->logo))
                                        <div class="row mb-4">  
                                            <div class="col-lg-4">
                                                <label for="logo" class="form-label">Old Logo</label>
                                            </div>
                                            <div class="col-md-6">
                                                <a href="{{ asset('storage/uploads/' . $settings->logo) }}" target="_blank">
                                                    <img src="{{ asset('storage/uploads/' . $settings->logo) }}" alt="{{$settings->logo}}" width="50">
                                                </a>                    
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="logo" class="form-label">Upload Logo</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-image"></i></div>
                                                <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                                            </div>
                                            @error('logo')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="fullnameInput" class="fw-semibold">Company Name</label>
                                        </div>  
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-briefcase"></i></div>
                                                <input type="text" name="name" placeholder="Name" class="form-control"  id="fullnameInput"  value="{{ old('name', $settings->name ?? '') }}">
                                            </div>
                                            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="tagline" class="fw-semibold">Tagline</label>
                                        </div>  
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-type"></i></div>
                                                <input type="text" name="tagline" placeholder="Tagline" class="form-control"  id="tagline"  value="{{ old('tagline', $settings->tagline ?? '') }}">
                                            </div>
                                            @error('tagline')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    @if(isset($settings->favicon))
                                        <div class="row mb-4">  
                                            <div class="col-lg-4">
                                                <label for="favicon" class="form-label">Old Favicon</label>
                                            </div>
                                            <div class="col-md-6">
                                                <a href="{{ asset('storage/uploads/' . $settings->favicon) }}" target="_blank">
                                                    <img src="{{ asset('storage/uploads/' . $settings->favicon) }}" alt="{{$settings->favicon}}" width="50">
                                                </a>                    
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="favicon" class="form-label">Upload Favicon</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-image"></i></div>
                                                <input type="file" name="favicon" id="favicon" class="form-control" accept="image/*">
                                            </div>
                                            @error('favicon')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="gst_number" class="fw-semibold">GST Number</label>
                                        </div>  
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-hash"></i></div>
                                                <input type="text" name="gst_number" maxlength="15" placeholder="GST Number" class="form-control"  id="gst_number"  value="{{ old('gst_number', $settings->gst_number ?? '') }}">
                                            </div>
                                            @error('gst_number')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="address" class="fw-semibold">Address</label>
                                        </div>  
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-map-pin"></i></div>
                                                <textarea name="address" placeholder="Address" class="form-control"  id="address">{{ old('address', $settings->address ?? '') }}</textarea>
                                            </div>
                                            @error('address')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="location" class="fw-semibold">Location</label>
                                        </div>  
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-map-pin"></i></div>
                                                <input type="url" name="location" placeholder="Location" class="form-control"  id="location"  value="{{ old('location', $settings->location ?? '') }}">
                                            </div>
                                            @error('location')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="contact_number" class="fw-semibold">Contact Number</label>
                                        </div>  
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-phone"></i></div>
                                                <input type="phone" name="contact_number" placeholder="Contact Number" class="form-control"  id="contact_number"  value="{{ old('contact_number', $settings->contact_number ?? '') }}">
                                            </div>
                                            @error('contact_number')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="whatsapp_number" class="fw-semibold">Whatsapp Number</label>
                                        </div>  
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-message-circle"></i></div>
                                                <input type="phone" name="whatsapp_number" placeholder="Whatsapp Number" class="form-control"  id="whatsapp_number"  value="{{ old('whatsapp_number', $settings->whatsapp_number ?? '') }}">
                                            </div>
                                            @error('whatsapp_number')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="copyright" class="fw-semibold">Copyright</label>
                                        </div>  
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-circle"></i></div>
                                                <input type="text" name="copyright" placeholder="Copyright" class="form-control"  id="copyright"  value="{{ old('copyright', $settings->copyright ?? '') }}">
                                            </div>
                                            @error('copyright')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="facebook" class="fw-semibold">Facebook</label>
                                        </div>  
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-facebook"></i></div>
                                                <input type="url" name="facebook" placeholder="Facebook" class="form-control"  id="facebook"  value="{{ old('facebook', $settings->facebook ?? '') }}">
                                            </div>
                                            @error('facebook')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="twitter" class="fw-semibold">Twitter</label>
                                        </div>  
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-twitter"></i></div>
                                                <input type="url" name="twitter" placeholder="Twitter" class="form-control"  id="twitter"  value="{{ old('twitter', $settings->twitter ?? '') }}">
                                            </div>
                                            @error('twitter')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="instagram" class="fw-semibold">Instagram</label>
                                        </div>  
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-instagram"></i></div>
                                                <input type="url" name="instagram" placeholder="Instagram" class="form-control"  id="instagram"  value="{{ old('instagram', $settings->instagram ?? '') }}">
                                            </div>
                                            @error('instagram')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="linkedin" class="fw-semibold">Linkedin</label>
                                        </div>  
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-linkedin"></i></div>
                                                <input type="url" name="linkedin" placeholder="Linkedin" class="form-control"  id="linkedin"  value="{{ old('linkedin', $settings->linkedin ?? '') }}">
                                            </div>
                                            @error('linkedin')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="youtube" class="fw-semibold">Youtube</label>
                                        </div>  
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-youtube"></i></div>
                                                <input type="url" name="youtube" placeholder="Youtube" class="form-control"  id="youtube"  value="{{ old('youtube', $settings->youtube ?? '') }}">
                                            </div>
                                            @error('youtube')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <hr class="mt-0">
                                    @if(isset($settings->authorized_signatory))
                                        <div class="row mb-4">  
                                            <div class="col-lg-4">
                                                <label for="authorized_signatory" class="form-label">Authorized Signatory</label>
                                            </div>
                                            <div class="col-md-6">
                                                <a href="{{ asset('storage/uploads/' . $settings->authorized_signatory) }}" target="_blank">
                                                    <img src="{{ asset('storage/uploads/' . $settings->authorized_signatory) }}" alt="{{$settings->authorized_signatory}}" width="50">
                                                </a>                    
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-lg-4">
                                            <label for="authorized_signatory" class="form-label">Upload Authorized Signatory</label>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="input-group">
                                                <div class="input-group-text"><i class="feather-image"></i></div>
                                                <input type="file" name="authorized_signatory" id="authorized_signatory" class="form-control" accept="image/*">
                                            </div>
                                            @error('authorized_signatory')<div class="text-danger">{{ $message }}</div>@enderror
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