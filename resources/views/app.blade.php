<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet"></link>
    <script src="{{ mix('/js/app.js') }}" defer></script>
</head>
<body class="min-h-screen w-full flex flex-col">
@routes
@inertia
</body>
</html>
