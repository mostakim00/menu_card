<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf_token" content="{{ csrf_token() }}" />
    {{-- <link rel="icon" type="image/x-icon" href="{{ $setting->favicon ?? 'frontend/images/logo.svg' }}"> --}}

    <title>@yield('title')</title>

    @include('partials.style')
</head>

<body class="sidebar-fixed with-welcome-text">
    <div class="container-scroller">
        {{-- partial:partials/_navbar.html --}}
        @include('partials.header')
        {{-- partial --}}
        <div class="container-fluid page-body-wrapper">

            {{-- partial:partials/_sidebar.html --}}
            @include('partials.sidebar')
            {{-- partial --}}
            <div class="main-panel">
                @yield('content')
                {{-- content-wrapper ends --}}
                {{-- partial:partials/_footer.html --}}
                @include('partials.footer')
                {{-- partial --}}
            </div>
            {{-- main-panel ends --}}
        </div>
        {{-- page-body-wrapper ends --}}
    </div>

    @include('partials.script')
</body>

</html>
