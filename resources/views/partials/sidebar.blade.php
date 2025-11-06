<nav class="nxl-navigation">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="{{ route('home') }}" class="b-brand">
                    @if(!empty($settings) && !empty($settings->logo))
                        <!-- Display uploaded logo -->
                        <img src="{{ asset('storage/uploads/' . $settings->logo) }}" alt="" class="logo logo-lg" style="width: 160px;"/>
                        <img src="{{ asset('storage/uploads/' . $settings->favicon) }}" alt="" class="logo logo-sm" />
                    @endif
                </a>
            </div>

            <div class="navbar-content">
                <ul class="nxl-navbar">
                    <li class="nxl-item nxl-caption">
                        <label>Navigation</label>
                    </li>   
                    
                    @if(auth()->user()->can('user-list') || auth()->user()->can('user-create') || auth()->user()->can('user-edit') || auth()->user()->can('user-delete'))
                        <li class="nxl-item">
                            <a class="nav-link nxl-link" href="{{ route('users.index') }}">
                                <span class="nxl-micon"><i class="feather-user"></i></span>
                                <span class="nxl-mtext">Manage Users</span>
                            </a>
                        </li>
                    @endif                 
                             
                    @if(auth()->user()->can('role-list') || auth()->user()->can('role-create') || auth()->user()->can('role-edit') || auth()->user()->can('role-delete'))
                        <li class="nxl-item">
                            <a class="nav-link nxl-link" href="{{ route('roles.index') }}">
                                <span class="nxl-micon"><i class="feather-check-square"></i></span> 
                                <span class="nxl-mtext">Manage Roles</span>                                
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->can('part-list') || auth()->user()->can('part-create') || auth()->user()->can('part-edit') || auth()->user()->can('part-delete'))
                        <li class="nxl-item">
                            <a class="nav-link nxl-link" href="{{ route('spareParts.index') }}">
                                <span class="nxl-micon"><i class="feather-tool"></i></span> 
                                <span class="nxl-mtext">Parts</span>                                
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->can('product-list') || auth()->user()->can('product-create') || auth()->user()->can('product-edit') || auth()->user()->can('product-delete'))
                        <li class="nxl-item">
                            <a class="nav-link nxl-link" href="{{ route('products.index') }}">
                                <span class="nxl-micon"><i class="feather-box"></i></span> 
                                <span class="nxl-mtext">Products</span>                                
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->can('finished-product-list') || auth()->user()->can('finished-product-create') || auth()->user()->can('finished-product-edit') || auth()->user()->can('finished-product-delete'))
                        <li class="nxl-item">
                            <a class="nav-link nxl-link" href="{{ route('finishedProducts.index') }}">
                                <span class="nxl-micon"><i class="feather-package"></i></span> 
                                <span class="nxl-mtext">Finished Products</span>                                
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->can('customer-list') || auth()->user()->can('customer-create') || auth()->user()->can('customer-edit') || auth()->user()->can('customer-delete'))
                        <li class="nxl-item">
                            <a class="nav-link nxl-link" href="{{ route('customers.index') }}">
                                <span class="nxl-micon"><i class="feather-users"></i></span> 
                                <span class="nxl-mtext">Customers</span>                                
                            </a>
                        </li>
                    @endif
                    
                    @if(auth()->user()->can('purchaseOrder-list') || auth()->user()->can('purchaseOrder-create') || auth()->user()->can('purchaseOrder-edit') || auth()->user()->can('purchaseOrder-delete'))
                        <li class="nxl-item">
                            <a class="nav-link nxl-link" href="{{ route('newPurchaseOrders.index') }}">
                                <span class="nxl-micon"><i class="feather-clipboard"></i></span> 
                                <span class="nxl-mtext">New Purchase Orders</span>
                            </a>
                        </li>
                    @endif  
                    
                    @if(auth()->user()->can('internalRejections-create') || auth()->user()->can('internalRejections-edit') || auth()->user()->can('internalRejections-delete'))
                        <li class="nxl-item">
                            <a class="nav-link nxl-link" href="{{ route('rejections.index') }}">
                                <span class="nxl-micon"><i class="feather-alert-triangle"></i></span> 
                                <span class="nxl-mtext">Internal Rejections</span>
                            </a>
                        </li>
                    @endif
                    
                    @if(auth()->user()->can('customerRejections-create') || auth()->user()->can('customerRejections-list'))
                        <li class="nxl-item">
                            <a class="nav-link nxl-link" href="{{ route('customerRejections.index') }}">
                                <span class="nxl-micon"><i class="feather-slash"></i></span> 
                                <span class="nxl-mtext">Customer Rejections</span>
                            </a>
                        </li>
                    @endif
                    
                    @if(auth()->user()->can('sales-list') || auth()->user()->can('sales-create') || auth()->user()->can('sales-edit') || auth()->user()->can('sales-delete'))
                        <li class="nxl-item">
                            <a class="nav-link nxl-link" href="{{ route('sales.index') }}">
                                <span class="nxl-micon"><i class="feather-trending-up"></i></span> 
                                <span class="nxl-mtext">Sales</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->can('deliveryChallans-list') || auth()->user()->can('deliveryChallans-create') || auth()->user()->can('deliveryChallans-edit') || auth()->user()->can('deliveryChallans-delete'))
                        <li class="nxl-item">
                            <a class="nav-link nxl-link" href="{{ route('deliveryChallans.index') }}">
                                <span class="nxl-micon"><i class="feather-file-text"></i></span> 
                                <span class="nxl-mtext">Delivery Challans</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->can('settings-list'))
                        <li class="nxl-item">
                            <a class="nav-link nxl-link" href="{{ route('settings.edit') }}">
                                <span class="nxl-micon"><i class="feather-settings"></i></span> 
                                <span class="nxl-mtext">Settings</span>                                
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    <header class="nxl-header">
        <div class="header-wrapper">
            <!--! [Start] Header Left !-->
            <div class="header-left d-flex align-items-center gap-4">
                <!--! [Start] nxl-head-mobile-toggler !-->
                <a href="javascript:void(0);" class="nxl-head-mobile-toggler" id="mobile-collapse">
                    <div class="hamburger hamburger--arrowturn">
                        <div class="hamburger-box">
                            <div class="hamburger-inner"></div>
                        </div>
                    </div>
                </a>
                <!--! [Start] nxl-head-mobile-toggler !-->
                <!--! [Start] nxl-navigation-toggle !-->
                <div class="nxl-navigation-toggle">
                    <a href="javascript:void(0);" id="menu-mini-button">
                        <i class="feather-align-justify"></i>
                    </a>
                    <a href="javascript:void(0);" id="menu-expend-button" style="display: none">
                        <i class="feather-arrow-right"></i>
                    </a>
                </div>
                <!--! [End] nxl-navigation-toggle !-->
                <!--! [Start] nxl-lavel-mega-menu-toggle !-->
                <!--! [End] nxl-lavel-mega-menu-toggle !-->
                <!--! [Start] nxl-lavel-mega-menu !-->
                <div class="nxl-drp-link nxl-lavel-mega-menu">
                    <div class="nxl-lavel-mega-menu-toggle d-flex d-lg-none">
                        <a href="javascript:void(0)" id="nxl-lavel-mega-menu-hide">
                            <i class="feather-arrow-left me-2"></i>
                            <span>Back</span>
                        </a>
                    </div>
                </div>
                <!--! [End] nxl-lavel-mega-menu !-->
            </div>
            <!--! [End] Header Left !-->
            <!--! [Start] Header Right !-->
            <div class="header-right ms-auto">
                <div class="d-flex align-items-center">
                    <div class="nxl-h-item d-none d-sm-flex">
                        <div class="full-screen-switcher">
                            <a href="javascript:void(0);" class="nxl-head-link me-0" onclick="$('body').fullScreenHelper('toggle');">
                                <i class="feather-maximize maximize"></i>
                                <i class="feather-minimize minimize"></i>
                            </a>
                        </div>
                    </div>
                    <div class="nxl-h-item dark-light-theme">
                        <a href="javascript:void(0);" class="nxl-head-link me-0 dark-button">
                            <i class="feather-moon"></i>
                        </a>
                        <a href="javascript:void(0);" class="nxl-head-link me-0 light-button" style="display: none">
                            <i class="feather-sun"></i>
                        </a>
                    </div>
                    <div class="dropdown nxl-h-item">
                        <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                            <img src="{{ Auth::user()->user_photo ? asset('storage/users/' . Auth::user()->user_photo) : asset('uploads/user.png') }}" alt="user-image" class="img-fluid user-avtar me-0" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
                            <div class="dropdown-header">
                                <div class="d-flex align-items-center">
                                    <img src="{{ Auth::user()->user_photo ? asset('storage/users/' . Auth::user()->user_photo) : asset('uploads/user.png') }}" alt="user-image" class="img-fluid user-avtar" />
                                    <div>
                                        <a href="{{ route('users.edit', Auth::user()->id) }}">
                                            <h6 class="text-dark mb-0">{{ Auth::user()->name ?? ''}}</h6>
                                        </a>
                                        <span class="fs-12 fw-medium text-muted">{{ Auth::user()->email ?? ''}}</span>
                                    </div>
                                </div>
                            </div>
                            <!-- <a href="./auth-login-minimal.html" class="dropdown-item"> -->
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="feather-log-out"></i> Logout </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            <!-- </a> -->
                        </div>
                    </div>
                </div>
            </div>  
            <!--! [End] Header Right !-->
        </div>
    </header>