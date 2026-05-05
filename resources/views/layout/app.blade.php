@include('layout.header')
@include('layout.sidebar')
    <div class="content-area">
        @yield('content')
    </div>
</div>

@include('layout.footer')
@yield('script')
