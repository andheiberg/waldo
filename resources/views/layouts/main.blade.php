<!DOCTYPE html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <title>@yield('title', ''){{ trans('brand.title-append') }}</title>
    <meta name="description" content="@yield('description', trans('brand.description'))">

    @section('css')
        <link rel="stylesheet" type="text/css" href="{{ elixir('css/app.css') }}">
    @show
</head>

<body class="@yield('body-class')">
@include('layouts.partials.header')

<div class="page container">
    @section('main')

    @show
</div>

@include('layouts.partials.footer')

@section('js')
    <script src="{{ elixir('js/app.js') }}"></script>
@show

</body>
</html>
