@include('admin.layout.header')
@include('admin.layout.sidebar')

    @yield('content1')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>
           
    <!-- JAVASCRIPT -->
    <script src="{{asset('theme/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('theme/admin/assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('theme/admin/assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('theme/admin/assets/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('theme/admin/assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
    <script src="{{asset('theme/admin/assets/js/plugins.js')}}"></script>

    <!-- apexcharts -->
    <script src="{{asset('theme/admin/assets/libs/apexcharts/apexcharts.min.js')}}"></script>

    <!-- Vector map-->
    <script src="{{asset('theme/admin/assets/libs/jsvectormap/js/jsvectormap.min.js')}}"></script>
    <script src="{{asset('theme/admin/assets/libs/jsvectormap/maps/world-merc.js')}}"></script>

    <!--Swiper slider js-->
    <script src="{{asset('theme/admin/assets/libs/swiper/swiper-bundle.min.js')}}"></script>

    <!-- Dashboard init -->
    <script src="{{asset('theme/admin/assets/js/pages/dashboard-ecommerce.init.js')}}"></script>

    @yield('script-libs')
    @yield('scripts')
    <!-- App js -->
    <script src="{{asset('theme/admin/assets/js/app.js')}}"></script>
</body>

</html>