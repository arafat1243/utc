<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'UTC') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&family=Russo+One&display=swap"
        rel="stylesheet">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
    <script src="{{ mix('/js/app.js') }}" defer></script>
    <style>
        .primary{
            background: #5d9cec !important;
        }
        .primary--text{
            color: #5d9cec !important;
        }
        .error--text{
            color: rgb(196, 50, 50) !important;
        }
    </style>
</head>
<body>
    <div id="no-app" data-app="true" class="v-application v-application--is-ltr theme--light">
        <div class="v-application--wrap">
            <main class="v-main" style="padding: 0px" data-booted="true">
                <div class="v-main__wrap">
                    <header class="white--text v-sheet theme--light v-toolbar v-app-bar primary" style="height: 64px; margin-top: 0px; transform: translateY(0px); left: 0px; right: 0px;" data-booted="true">
                        <div class="v-toolbar__content" style="height: 64px">
                            <div class="v-toolbar__title">
                                <a href="/" class="brand-text white--text v-btn v-btn--flat v-btn--text theme--light v-size--default">
                                    <span class="v-btn__content">UTC</span>
                                </a>
                            </div>
                        </div>
                    </header>
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
<script>
    const inputs = document.querySelectorAll('.input');
        inputs.forEach(input => {
            if(input.value){
                input.previousElementSibling.classList.add('v-label--active','primary--text');
                input.parentElement.parentElement.parentElement.parentElement.classList.add('v-input--is-focused','primary--text');
                input.parentElement.parentElement.parentElement.parentElement.childNodes[1].childNodes[1].childNodes[1].classList.add('primary--text');
            }
            input.addEventListener('input' && 'focus',()=>{
                input.previousElementSibling.classList.add('v-label--active','primary--text');
                input.parentElement.parentElement.parentElement.parentElement.classList.add('v-input--is-focused','primary--text'); 
                input.parentElement.parentElement.parentElement.parentElement.childNodes[1].childNodes[1].childNodes[1].classList.add('primary--text');
            });
            input.addEventListener('blur',()=>{
                if(!input.value){
                    input.previousElementSibling.classList.remove('v-label--active','primary--text');
                    input.parentElement.parentElement.parentElement.parentElement.classList.remove('v-input--is-focused','primary--text');
                    input.parentElement.parentElement.parentElement.parentElement.childNodes[1].childNodes[1].childNodes[1].classList.remove('primary--text');
                }
            });
        });
</script>
</body>
</html>
