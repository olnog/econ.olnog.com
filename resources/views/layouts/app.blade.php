<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-LF1MZC30BZ"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-LF1MZC30BZ');
    </script>

    <!-- Styles -->
    <style>
      .forest{
        border:2px DarkGreen solid;
      }
      .mountains{
        border:2px DarkRed solid ;
      }
      .noQuantity{
        background-color:whitesmoke;
      }
      .plains {
        border:2px DarkKhaki solid;
      }
      .jungle {
        border:2px Indigo solid;
      }
      .desert {
        border:2px SaddleBrown solid;
      }
      .contracts{
        border: 1px black solid;
      }
      #status{
        color:white;
      }
      #workHoursCent{
        width:50px;
      }
    </style>
</head>
<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src='https://code.jquery.com/jquery-3.6.0.js'></script>
<!---->
    <script src='/js/variables.js'></script>
    <script src='/js/js.js'></script>
    <script src='/js/ajax.js'></script>
    <script src='/js/buildings.js'></script>
    <script src='/js/contracts.js'></script>
    <script src='/js/equipment.js'></script>
    <script src='/js/items.js'></script>
    <script src='/js/events/change.js'></script>
    <script src='/js/events/click.js'></script>
    <script src='/js/events/contractClick.js'></script>
    <script src='/js/events/actionClick.js'></script>

    <script src='/js/action.js'></script>

    <script src='/js/ui/displayActions.js'></script>
    <script src='/js/ui/displayContracts.js'></script>

    <script src='/js/ui/displayBuildings.js'></script>
    <script src='/js/ui/displayItems.js'></script>
    <script src='/js/ui/ui.js'></script>
    <script src='/js/ui/whyNot.js'></script>
    <script src='/js/ui/refreshui.js'></script>

</body>
</html>
