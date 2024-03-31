<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>App Title</title>
</head>
@vite(['resources/js/app.js', 'resources/css/app.css'])
<body>
    <x-sidebar-menu />
    <div class="container">
        @yield('content')
    </div>
</body>
</html>