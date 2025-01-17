<!doctype html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">

<head>
    <title>@yield('title', 'Plus Plus')</title>

    @include('layouts.head')
</head>

<body>

    @include('layouts.navbar')
    <div id="content-page" class="content-page">
        <div class="container-fluid relative">
            @yield('content')
        </div>
    </div>
    @include('layouts.footer')

</body>

</html>
