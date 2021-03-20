<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" charset="utf-8">

        <title>Laravel Vue Test</title>
        <script>window.Laravel = { csrfToken: '{{ csrf_token() }}' }</script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

        <style>
            body {
                font-size: 12px;
            }
            .card {
                margin-bottom: 10px;
            }
            figure, .blockquote-footer {
                margin-bottom: 0;
            }
            .collapse {
                margin-top: 20px;
            }
        </style>
    </head>
    <body>

        <div id="app">
            <div class="container">
                <comments></comments>
            </div>
        </div>
        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
