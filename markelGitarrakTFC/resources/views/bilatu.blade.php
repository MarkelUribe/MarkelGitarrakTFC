<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            filtratu();

            function filtratu() {
                let bilaketa = $('#input_bilaketa').val();
                let mota = $('#input_mota').val();
                $.ajax({
                    type: 'POST',
                    url: '/eskaintzakfiltratu',
                    data: {
                        'bilaketa': bilaketa,
                        'mota': mota,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $("#eskaintzak").html(data.eskaintzak);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            }

            $("#input_bilaketa").on('change', function() {
                filtratu();
            });
            $("#input_mota").on('change', function() {
                filtratu();
            });
        });
    </script>

<body class="antialiased">
    @include('navbar')
    <div id="content">
        <input type="text" id="input_bilaketa" placeholder="Bilatu..." name="search">
        <div>
            <label>Kategoriak filtratu</label><br>
        <select id="input_mota">
        <option value="" selected></option>
        @foreach ($eskaintzamotak as $m)
            <option value="{{$m->id}}">{{$m->mota}}</option>
        @endforeach
        </select>
        </div>
        
        <div id="eskaintzak"></div>

    </div>
</body>

</html>