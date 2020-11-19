<html>
    <head>
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <script src="{{asset('js/app.js')}}"></script>
        <title>South Park API - @yield('title')</title>
        <script async defer src="https://buttons.github.io/buttons.js"></script>
        
    </head>
    <body class="container">
        @include('header')
        <div class="mt-4">
            @yield('content')
        </div>
        <footer>
            <p class="float-left">Created by: Ivo Bot ©<script>document.write(new Date().getFullYear());</script></p>
            <div class="float-right">
                <a class="github-button" href="https://github.com/danoctum" data-size="large" aria-label="Follow @danoctum on GitHub">Follow @danoctum</a>
            </div>
        </footer>
    </body>
    @stack('scripts')
</html>


