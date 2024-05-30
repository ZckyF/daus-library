<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? config( 'app.name') }}</title>
</head>
@vite(['resources/js/app.js', 'resources/sass/app.scss'])
<body>

    <div class="container">
        {{ $slot }}
    </div>
   

    
</body>
</html>