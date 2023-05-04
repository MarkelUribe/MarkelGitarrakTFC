<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <title>Markel Gitarrak</title>
    <link rel="shortcut icon" href="{{ asset('storage/icon/logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />



    <!-- Styles -->
    <style>
        .container form {
            display: flex;
            flex-direction: column;
        }

        #btn_gehituargazkia {
            display: none;
        }

        #label_gehituargazkia {
            margin: 15px;
            width: 100px;
            height: 150px;
            border: dotted 3px #000000;
            border-radius: 15px;
            text-align: center;
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        #label_gehituargazkia:hover {
            border: dotted 3px #787878;
            transition: 0.2s;
        }

        .argazkiak {
            display: flex;
            justify-content: left;
            flex-wrap: wrap;
        }

        .imgbox {
            position: relative;
        }

        .argazkiak img {
            margin: 15px;
            height: 150px;
            border: solid 3px #000000;
            border-radius: 15px;
        }

        .btn_deleteimg {
            border: solid 3px black;
            border-radius: 100px;
            width: 30px;
            height: 30px;
            color: red;
            font-weight: 900;
            position: absolute;
            right: 3%;
            top: 3%;
            text-align: center;
            background-color: white;
            cursor: pointer;
        }

        .btn_deleteimg:hover {
            transition: 0.1s;
            color: #000000;
            width: 31px;
            height: 31px;
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

            $("#btn_gehituargazkia").on('change', function() {
                let argazkiak = $('#input_argazkiak').val();
                var fileData = new FormData();
                fileData.append('file', this.files[0]);
                fileData.append('argazkiak', argazkiak);
                fileData.append('eskaintzaid', $('#eskaintzaid').val());
                $.ajax({
                    type: 'POST',
                    url: '/argazkiagehitualdaketan',
                    data: fileData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        let imgid = data.path.replace('productimages/', '');
                        imgid = imgid.split('.', 1)[0];
                        let imgbox = "<div class='imgbox' id='" + imgid + "' val='"+data.path+"'><img src='{{ asset('storage/' ) }}/" + data.path + "'><div class='btn_deleteimg'>X</div></div>";
                        $('.argazkiak').prepend(imgbox);
                        $('#input_argazkiak').val(data.argazkiak);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $(".argazkiak").on('click', '.btn_deleteimg', function() {
                var argazkiak = $('#input_argazkiak').val();
                var path = 'productimages/' + $(this).parent().attr('id');
                $('#input_argazkiak').val(argazkiak.replace(path, ''));

                var realpath = $(this).parent().attr('val');
                alert(realpath);

                $.ajax({
                    type: 'POST',
                    url: '/argazkiakendualdaketan',
                    data: {
                        'path': path,
                        'realpath': realpath,
                        'argazkiak': $('#input_argazkiak').val(),
                        'eskaintzaid': $('#eskaintzaid').val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        let imgid = data.path.replace('productimages/', '');
                        imgid = imgid.split('.', 1)[0];
                        console.log(data);
                        $('#' + imgid).remove();
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
    <div class="container">
        <form method="post" action="/submiteskaintzaaldatu">
            {{ csrf_field() }}
            <input style="display:none;" type="text" id="eskaintzaid" name="id" value="{{$eskaintza->id}}">

            <label>Eskaintzaren izenburua</label>
            <input required type="text" name="izena" value="{{$eskaintza->izena}}">
            
            <label for="mota">Produktu mota aukeratu:</label>
            <select name="mota" id="motak">
                @foreach ($motak as $m)
                @if($m->id == $eskaintza->motaId)
                <option selected value="{{$m->id}}">{{$m->mota}}</option>
                @else
                <option value="{{$m->id}}">{{$m->mota}}</option>
                @endif
                @endforeach
            </select>

            <label>Azalpena</label>
            <input required type="textarea" name="azalpena" value="{{$eskaintza->azalpena}}">

            <label>Prezioa</label>
            <input required type="number" name="prezioa" value="{{$eskaintza->prezioa}}">

            <label>Produktuaren egoera</label>
            <select required name="egoera">
                <option <?php if($eskaintza->estatua == 'berria'){?> selected <?php } ?> value="berria">Berria</option>
                <option <?php if($eskaintza->estatua == 'ia_berria'){?>selected<?php } ?> value="ia_berria">Ia berria</option>
                <option <?php if($eskaintza->estatua == 'erabilia'){?>selected<?php } ?> value="erabilia">Erabilia</option>
                <option <?php if($eskaintza->estatua == 'txarra'){?>selected<?php } ?> value="txarra">Txarra</option>
                <option <?php if($eskaintza->estatua == 'apurtuta'){?>selected<?php } ?> value="apurtuta">Apurtuta</option>
            </select>

            <label>Argazkiak</label>
            <i>Argazkiak hemen aldatzean zuzenean aldatuko dira, aldakeak gordetzeko botoiari eman gabe.</i>
            <input style="display: none;" type="text" name="argazkiak" id="input_argazkiak" value="{{$argazkistring}}">
            <div class="argazkiak">
            @foreach ($argazkiak as $img)
            <div class='imgbox' id='<?php explode("\.", trim($img))[0]; ?>' val='{{trim($img)}}'><img src="{{ asset('storage/' ) }}/{{trim($img)}}"><div class='btn_deleteimg'>X</div></div>
            @endforeach
                <label id="label_gehituargazkia">
                    <input type="file" id="btn_gehituargazkia" value="Argazkia gehitu" accept="image/*">
                    Gehitu argazkia
                </label>

            </div>
            

            <input type="submit" value="Aldaketak gorde">
        </form>
    </div>

</body>

</html>