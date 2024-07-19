<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&display=swap" rel="stylesheet">
    <link href="{{mix('assets/css/app.css')}}?ver=22" rel="stylesheet" type="text/css">
    <title>бронирование</title>
</head>
<body class="">
<div class="app container mt-4 mb-4" id ="app">
    <booking></booking>
</div>
<script src="{{mix('assets/js/app.js')}}?ver=32"></script>
</body>
</html>
