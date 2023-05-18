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
</head>

<body class="antialiased">
    @include('navbar')

    <div id="content">
        <h2>Zure eskaintzak</h2>
        <div id="eskaintzak">
            @if($salerosketaeskaintzak->count() > 0)
            @foreach ($salerosketaeskaintzak as $eskaintza)
            <?php
            $chatId = $chats->where('eskaintzaId', '=', $eskaintza->id)->first()['id'];
            ?>
            <a href="offer/{{$chatId}}" style="color: inherit;text-decoration: none;">
                <div class="eskaintzabox">
                    <?php
                    $img_arr = array_filter(explode(",", $eskaintza->argazkiak), fn ($value) => !is_null($value));
                    foreach ($img_arr as $key => $newarr) {
                        if (trim($newarr) === '') {
                            unset($img_arr[$key]);
                        }
                    }
                    ?>
                    <img src="{{ asset('storage/'. trim($img_arr[1]))}}">
                    <div style="float:left;">
                        <h5>{{ $eskaintza->izena }}</h5>
                        <h5>{{ $eskaintza->prezioa}}€</h5>

                        <?php
                        if ($eskaintza->erosleId == Null) {
                            //Gu saltzailea bagara
                            if ($eskaintza->userId == $user->id) {
                                echo '<p>Zu saltzen</p>';
                                $eroslea = Null;
                                $a = $erabiltzailechatsdenak->where('chatId', '=', $chatId);
                                $saltzailea = Null;
                                foreach ($a as $key => $value) {
                                    if ($value['userId'] != $user->id) {
                                        $eroslea = $erabiltzaileak->where('id', '=', $value['userId'])->first();

                                        echo '<p>Eroslea: ' . $eroslea->name . '</p>';
                                        break;
                                    }
                                }
                            } else {
                                echo '<p>Zu erosten</p>';

                                $a = $erabiltzailechatsdenak->where('chatId', '=', $chatId);
                                $saltzailea = Null;
                                foreach ($a as $key => $value) {
                                    if ($value['userId'] != $user->id) {
                                        $saltzailea = $erabiltzaileak->where('id', '=', $value['userId'])->first();

                                        echo '<p>saltzailea: ' . $saltzailea->name . '</p>';
                                        break;
                                    }
                                }
                            }
                        } else {
                            echo '<h5>Salerosketa hau amaitu da</h5>';
                        }

                        ?>

                    </div>


                </div>
            </a>
            @endforeach
            @else
            <p>Ez daukazu eskaintzarik</p>

            @endif
        </div>
    </div>
</body>

</html>