<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&family=Russo+One&display=swap"
      rel="stylesheet">
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
    @if (!Auth::user())
      <link href="{{ mix('/css/public.css') }}" rel="stylesheet" />
    @else
        <link href="{{ mix('/css/public.css') }}" rel="stylesheet" />
        <link href="{{ mix('/css/admin.css') }}" rel="stylesheet" />
    @endif
    @routes
    <script src="{{ mix('/js/app.js') }}" defer></script>
    <script async defer crossorigin="anonymous"
      src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v7.0&appId=622086945013041&autoLogAppEvents=1"
      nonce="lONT3j3f"></script>
  </head>
  <body>
    @inertia
  </body>
</html>