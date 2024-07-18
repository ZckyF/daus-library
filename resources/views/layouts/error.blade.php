<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', config('app.name'))</title>
    <link rel="icon" href="{{ asset('logo-dasbry.png') }}">
</head>
@vite(['resources/js/app.js', 'resources/sass/app.scss'])

@yield('style')
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col text-center">
                <h1>@yield('code')</h1>
                <h2>@yield('title message')</h2>
                <p>
                    @yield('message')
                </p>
            </div>
        </div>
    </div>     
</body>
</html>