<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite('resources/js/app.js')
    @inertiaHead
    <style>
        html,
        body,
        #app {
            height: 100%;
            margin: 0;
        }
    </style>
</head>

<body>
    @inertia
</body>

</html>