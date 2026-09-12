<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ APPLICATION_NAME }} - @yield('title')</title>
    <link rel="icon" href="https://img.icons8.com/fluency/48/shopping-bag.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>

<body>

    @include('layout.shop.header')

    @yield('content')

    @include('layout.shop.footer')

</body>
@stack('scripts')

</html>
