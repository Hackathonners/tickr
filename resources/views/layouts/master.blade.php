<!DOCTYPE html>
<html class="with-sticky-footer" lang="pt">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="/images/fav.png"/>

    <title>Tickr - Built by Hackathonners</title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
</head>
<body class="sticky-footer-wrapper">

    @yield('content')

    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
