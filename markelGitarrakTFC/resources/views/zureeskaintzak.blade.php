<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <title>Markel Gitarrak</title>
    <link rel="shortcut icon" href="{{ asset('storage/icon/logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        #eskaintzak {
            display: flex;
            flex-wrap: wrap;
        }

        .eskaintzabox {
            border-radius: 10px;
            margin: 10px;
            width: 200px;
            height: 350px;
        }

        .eskaintzabox img {
            width: 100%;
            border-radius: 10px;
        }
    </style>
</head>

<body class="antialiased">
    @include('navbar')

    <div id="content">
        <div id="eskaintzak">
            @if($eskaintzak->count() > 0)
            @foreach ($eskaintzak as $esk)
            <a href="eskaintza/{{$esk->id}}" style="color: inherit;text-decoration: none;">
            <div class="eskaintzabox">
                <?php
                $img_arr = array_filter(explode(",", $esk->argazkiak), fn ($value) => !is_null($value));
                foreach ($img_arr as $key => $newarr) {
                    if (trim($newarr) === '') {
                        unset($img_arr[$key]);
                    }
                }
                ?>
                <img src="{{ asset('storage/'. trim($img_arr[1]))}}">
                <h5>{{ $esk->prezioa }}€</h5>
                <p>{{ $esk->izena }}</p>



            </div>
            </a>
            @endforeach
            @else
            <p>Ez daukazu eskaintzarik</p>

            @endif
        </div>
        <a href="/eskaintzaberria">Sortu eskaintza bat</a>
    </div>
</body>

</html>