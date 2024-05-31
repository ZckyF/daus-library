<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'My Application')</title>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>
@vite(['resources/js/app.js', 'resources/sass/app.scss'])
<body>
    {{-- <x-sidebar-menu /> --}}



    <div class="container">
        {{ $slot }}
    </div>

    <script src="{{ asset('js/sidebar.js') }}"></script>
</body>
</html>