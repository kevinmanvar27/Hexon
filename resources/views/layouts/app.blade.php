<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Vite (compiled assets) -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <title>{{ $settings->tagline ?? '' }}</title>

    <!--! BEGIN: Favicon-->
    <link rel="shortcut icon" type="image/x-icon" 
          href="{{ $settings && $settings->favicon ? asset('storage/uploads/' . $settings->favicon) : '' }}">
    <!--! END: Favicon-->

    <!--! BEGIN: Bootstrap CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('build/assets/css/bootstrap.min.css') }}">
    <!--! END: Bootstrap CSS-->

    <!--! BEGIN: Vendors CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('build/assets/vendors/css/vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('build/assets/vendors/css/daterangepicker.min.css')}}" />
    <!--! END: Vendors CSS-->

    <!--! BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('build/assets/css/theme.min.css')}}">
    <!--! END: Custom CSS-->

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <!-- ✅ DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!--! END: Custom CSS-->
    @stack('styles')


</head>
<body>
    @auth
        @include('partials.sidebar')
    @endauth

    <div id="app">
        <main class="py-4">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    @yield('content')
                </div>
            </div>
        </main>        
    </div>

    <!-- ================================================================ -->
    <!-- Footer Scripts -->
    <!-- ================================================================ -->

    <!-- jQuery (required for DataTables + Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!--! BEGIN: Vendors JS -->
    <script src="{{ asset('build/assets/vendors/js/vendors.min.js')}}"></script>
    <!--! END: Vendors JS -->

    <!-- ✅ DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!--! BEGIN: Apps Init -->
    <script src="{{ asset('build/assets/js/common-init.min.js')}}"></script>
    <script src="{{ asset('build/assets/js/theme-customizer-init.min.js')}}"></script>
    <script src="{{ asset('build/assets/js/main.js') }}"></script>
    <!--! END: Apps Init -->

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Allow pages to push custom scripts -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')

    <style>
        .select2-container--bootstrap-5 .select2-selection {
            height: calc(2.5rem + 5px) !important;
            min-height: calc(2.5rem + 5px) !important;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
        }
        .select2-container--bootstrap-5 .select2-selection__arrow {
            height: 100% !important;
            top: 50% !important;
            transform: translateY(-50%);
        }
    </style>
</body>
</html>
