@include('layout.header')
@include('layout.sidebar')
    <div class="content-area">
        @include('layout.alert')
        @yield('content')
    </div>
</div>

@include('layout.footer')
@yield('script')
