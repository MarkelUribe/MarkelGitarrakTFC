<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <title>Markel Gitarrak</title>
    <link rel="shortcut icon" href="{{ asset('storage/icon/logo.png') }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        #btn_sortueskaintza{
            border: 2px solid black;
            padding: 5px;
            margin: 5px;
            border-radius: 8px;
            font-size: large;
            font-weight: bold;
            color: inherit; 
            text-decoration:none; 
        }
        #eskaintzak {
            display: flex;
            flex-wrap: wrap;
        }

        .eskaintzabox img {
            width: 100%;
            border-radius: 10px;
        }

        .eskaintzabox {
            border-radius: 10px;
            margin: 10px;
            height: 350px;
        }

        @media (max-width: 600px) {
            .eskaintzabox {
                width: 44vw !important;
            }
        }

        @media (min-width: 601px) {
            .eskaintzabox {
                width: 200px;
            }
        }
    </style>
</head>

<body class="antialiased">
    @include('navbar')

    <div id="content">
        <h2>Zure eskaintzak</h2>
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
        <a id="btn_sortueskaintza" href="/eskaintzaberria">Sortu eskaintza bat</a>
    </div>
</body>

</html>