<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? config( 'app.name') }}</title>
    <link rel="icon" href="{{ asset('logo-dasbry.png') }}">
</head>
@vite(['resources/js/app.js', 'resources/sass/app.scss'])
<body>

    <div class="container-fluid">
        <div class="d-flex justify-content-center">  
            <div class="col col-md-8  col-lg-4 py-4 rounded"> 
                {{ $slot }}
            </div>
        </div>
    </div>
   

    
</body>
</html>