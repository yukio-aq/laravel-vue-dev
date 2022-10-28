<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel9-Vue3-SPA') }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <title>Laravel9-Vue3-SPA</title>
</head>
<body>

<div id="app">
    <header-component></header-component>
</div>

</body>
</html>
