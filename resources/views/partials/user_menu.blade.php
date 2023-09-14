<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-text mx-3">SIAMS V2.0</div>
    </a>

    <!-- Heading -->
    <div class="sidebar-heading">
        User Menu
    </div>
    @php
        if(isset($user)){
            $userAccesses = Helpers::userAccessCategories($user->access->toArray());
            if($userAccesses){
                if($user->utype == \App\Models\User::TYPE_ADMIN)
                {
                    foreach ($userAccesses as $access){
                        $accessCategory = Str::replaceFirst(' ', '_', $access['category']);
                        @endphp
                            <!-- Nav Item - Pages Collapse Menu -->
                            <li class="nav-item">
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse{{ $accessCategory }}"
                                    aria-expanded="true" aria-controls="collapse{{ $accessCategory }}">
                                    <i class="fas fa-fw {{ Helpers::menuCategoryIcon($access['category']) }}"></i>
                                    <span>{{ $access['category'] }}</span>
                                </a>
                                <div id="collapse{{ $accessCategory }}" class="collapse" aria-labelledby="heading{{ $accessCategory }}" data-parent="#accordionSidebar">
                                    <div class="bg-white py-2 collapse-inner rounded">
                                        <h6 class="collapse-header">Category Menu</h6>
                                        @php
                                            foreach ($access['access'] as $key => $v) {
                                                @endphp
                                                    <a class="collapse-item" href="/{{ $v['access'] }}">{{ $v['title'] }}</a>
                                                @php
                                            }
                                        @endphp
                                    </div>
                                </div>
                            </li>
                        @php
                    }
                }else if($user->utype == \App\Models\User::TYPE_INSTRUCTOR){
                    foreach ($userAccesses as $access){
                        $accessCategory = Str::replaceFirst(' ', '_', $access['category']);
                        @endphp
                            <!-- Nav Item - Pages Collapse Menu -->
                            <li class="nav-item">
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse{{ $accessCategory }}"
                                    aria-expanded="true" aria-controls="collapse{{ $accessCategory }}">
                                    <i class="fas fa-fw {{ Helpers::menuCategoryIcon($access['category']) }}"></i>
                                    <span>{{ $access['category'] }}</span>
                                </a>
                                <div id="collapse{{ $accessCategory }}" class="collapse" aria-labelledby="heading{{ $accessCategory }}" data-parent="#accordionSidebar">
                                    <div class="bg-white py-2 collapse-inner rounded">
                                        <h6 class="collapse-header">Category Menu</h6>
                                        @php
                                            foreach ($access['access'] as $key => $v) {
                                                @endphp
                                                    <a class="collapse-item" href="/{{ $v['access'] }}">{{ $v['title'] }}</a>
                                                @php
                                            }
                                        @endphp
                                    </div>
                                </div>
                            </li>
                        @php
                    }
                }else{
                    foreach ($userAccesses as $access){
                        @endphp
                            <li class="nav-item">
                                @php
                                    foreach ($access['access'] as $key => $v) {
                                        @endphp
                                            <a class="nav-link" href="/{{ $v['access'] }}">
                                                <i class="fas fa-fw {{ Helpers::studentMenuCategoryIcon($v['title']) }}"></i>
                                                <span>{{ $v['title'] }}</span>
                                            </a>
                                        @php
                                    }
                                @endphp
                                
                            </li>
                        @php
                    }
                }
            }
        }
    @endphp
     
    {{-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-folder-open"></i>
            <span>Offerings</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Utilities:</h6>
                <a class="collapse-item" href="/courses">Courses</a>
                <a class="collapse-item" href="/curriculum">Curriculum</a>
                <a class="collapse-item" href="/instructors">Instructors</a>
                <a class="collapse-item" href="/rooms">Rooms</a>
                <a class="collapse-item" href="/sections">Sections</a>
                <a class="collapse-item" href="/subjects">Subjects</a>
            </div>
        </div>
    </li> --}}
</ul>