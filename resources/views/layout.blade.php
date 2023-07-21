<!DOCTYPE html>
<html lang="en">
    @include('partials.header')
    <body id="page-top">
        <!-- Topbar -->
        @include('partials.navbar',['dropdown' => true])
        <!-- End of Topbar -->
        <!-- Page Wrapper -->
        <div id="wrapper">
            
            <!-- Sidebar -->
            @include('partials.user_menu')
            <!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <!-- Main Content -->
                <div id="content" class="pt-3">
                    @yield('content')
                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                @include('partials.footer')
                <!-- End of Footer -->
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->
        @include('partials.after_footer')
    </body>
</html>