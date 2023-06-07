<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test Gerardo</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js') }}?v={{ strtotime('now') }}" defer></script>
</head>

<body>
    <div id="app">
        {{-- <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form> --}}
        <app />
    </div>
</body>

</html>
