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

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        #eskaintzak {
            display: flex;
            flex-wrap: wrap;
            flex-direction: column;
        }

        .eskaintzabox img {
            border-radius: 10px;
            width: 150px;
            object-fit: cover;
        }

        .eskaintzabox {
            border-radius: 10px;
            margin: 10px;
            height: 200px;
            display: flex;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            mezuakJaso();

            function mezuakJaso() {
                $.ajax({
                    type: 'POST',
                    url: '/mezuakjaso',
                    data: {
                        'chatId': '{{$chat->id}}',
                        'besteuserid': '{{$besteuser->id}}',
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {

                        $('#dirueskaintzak').html(data.html);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        console.log(error);
                    }
                });
            }

            $('#btn_eskaini').click(function() {
                let prezioa = $('#prezioa').val();
                $.ajax({
                    type: 'POST',
                    url: '/dirueskaintza',
                    data: {
                        'chatId': '{{$chat->id}}',
                        'prezioa': prezioa,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('#input_prezioa').hide();
                        mezuakJaso();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        console.log(error);
                    }
                });
            });
            $("#dirueskaintzak").on('click', '#btn_bai', function() {
                $.ajax({
                    type: 'POST',
                    url: '/ofertaonartu',
                    data: {
                        'chatId': '{{$chat->id}}',
                        'besteuserId': '{{$besteuser->id}}'
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        mezuakJaso();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        console.log(error);
                    }
                });
            });

            $("#dirueskaintzak").on('click', '#btn_ez', function() {
                $.ajax({
                    type: 'POST',
                    url: '/ofertaez',
                    data: {
                        'chatId': '{{$chat->id}}',
                        'besteuserid': '{{$besteuser->id}}',
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        mezuakJaso();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        console.log(error);
                    }
                });
            });
        });
    </script>
</head>

<body class="antialiased">
    @include('navbar')

    <div id="content">
        <?php
        $img_arr = array_filter(explode(",", $eskaintza->argazkiak), fn ($value) => !is_null($value));
        foreach ($img_arr as $key => $newarr) {
            if (trim($newarr) === '') {
                unset($img_arr[$key]);
            }
        }
        ?>
        <h5>{{$eskaintza->izena}}</h5><br>
        <img width="100" src="{{ asset('storage/'. trim($img_arr[1]))}}">
        <h5>{{$eskaintza->prezioa}}€</h5><br>

        @if($eskaintza->userId == $user->id)
        <!-- Gu saltzaileak garen kasuan -->
        <div id="dirueskaintzak">

        </div>
        @else
        <!-- Gu erosleak garen kasuan -->
        <div id="dirueskaintzak">

        </div>
        <?php



        if ($mezuak->count() > 0) {
            $azkenmezua = $mezuak[$mezuak->count() - 1];
            if ($eskaintza->erosleId == Null && $azkenmezua->userId != $user->id) {
        ?>
                <div id="input_prezioa">
                    <label>Zenbat diru eskaintzen diozu</label>
                    <input type="number" id="prezioa">
                    <button id="btn_eskaini">Eskaini</button>
                </div>

        <?php
            }
        }else{  
        ?>
        <div id="input_prezioa">
                    <label>Zenbat diru eskaintzen diozu</label>
                    <input type="number" id="prezioa">
                    <button id="btn_eskaini">Eskaini</button>
                </div>
        <?php }?>
        @endif



    </div>
</body>

</html>