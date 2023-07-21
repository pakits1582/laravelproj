<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow">
    <!-- Topbar Search -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-6 my-auto p-2">
                <img src="
                    @isset($configuration)
                        {{ asset('images/'.$configuration->logo) }}
                    @endisset" 
                    height="60" alt="Logo">
            </div>
            <div class="col-xs-6 pl-2">  
                <h3 class="mb-0 text-gray-800">{{ ($configuration) ? $configuration->name : '' }}</h3>
                <h6 class="text-primary font-italic mb-0">{{ ($configuration) ? $configuration->address : '' }}</h6>
                <h6 class="text-black font-bold mb-0" style="font-size: 12px;">{{ config('app.name') }}</h6>
            </div>
        </div>
    </div>

    @if ($dropdown == true)
        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="mr-2 d-none d-lg-inline text-gray-600 small">
                        @auth
                            {{ Auth::user()->{ $info['info'] }->name }}
                            <p class="mb-0  font-weight-bold text-success">{{ session('periodname') }}</p>
                        @endauth
                    </div>
                    <img class="img-profile rounded-circle"
                        src="{{ asset('sbadmin/img/undraw_profile.svg') }}">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                    aria-labelledby="userDropdown">
                    @auth
                        <a class="dropdown-item current_period" href="#" id="{{ session('current_period') }}">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            {{ session('periodname') }}
                        </a>
                    @endauth
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profile
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                        Activity Log
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    @endif
    
</nav>