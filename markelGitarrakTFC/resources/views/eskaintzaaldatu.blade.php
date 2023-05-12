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

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>

    <script src="https://unpkg.com/@geoapify/geocoder-autocomplete@^1/dist/index.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@geoapify/geocoder-autocomplete@^1/styles/minimal.css">


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

        .autocomplete-container {
            position: relative;
        }

        .autocomplete-panel{
            z-index: 1000;
        }

        #map {
            width: calc(100% - 40px);
            height: 50vh;
            margin: 0;
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
            <label>kokapena</label>
            <h5 id="zurekokapena">{{$eskaintza->kokapena}}</h5>
            <div class="autocomplete-panel">
                <div id="autocomplete" class="autocomplete-container"></div>
            </div>
            <div id="map"></div><br>
            

            <input type="submit" value="Aldaketak gorde">
        </form>
    </div>
    <script>
        // The API Key provided is restricted to JSFiddle website
        // Get your own API Key on https://myprojects.geoapify.com
        const myAPIKey = "86c99c7541ee4dd681d48fef26c1d135";

        // The Leaflet map Object
        const map = L.map('map', {
            zoomControl: false
        }).setView([43.310557, -1.983392], 12);

        // Retina displays require different mat tiles quality
        const isRetina = L.Browser.retina;

        const baseUrl = "https://maps.geoapify.com/v1/tile/osm-bright/{z}/{x}/{y}.png?apiKey={apiKey}";
        const retinaUrl = "https://maps.geoapify.com/v1/tile/osm-bright/{z}/{x}/{y}@2x.png?apiKey={apiKey}";

        // Add map tiles layer. Set 20 as the maximal zoom and provide map data attribution.
        L.tileLayer(isRetina ? retinaUrl : baseUrl, {
            attribution: 'Powered by <a href="https://www.geoapify.com/" target="_blank">Geoapify</a> | <a href="https://openmaptiles.org/" rel="nofollow" target="_blank">© OpenMapTiles</a> <a href="https://www.openstreetmap.org/copyright" rel="nofollow" target="_blank">© OpenStreetMap</a> contributors',
            apiKey: myAPIKey,
            maxZoom: 20,
            id: 'osm-bright'
        }).addTo(map);

        // add a zoom control to bottom-right corner
        L.control.zoom({
            position: 'bottomright'
        }).addTo(map);

        // check the available autocomplete options on the https://www.npmjs.com/package/@geoapify/geocoder-autocomplete 
        const autocompleteInput = new autocomplete.GeocoderAutocomplete(
            document.getElementById("autocomplete"),
            myAPIKey, {
                /* Geocoder options */
                placeholder: "Zure helbidea idatzi",
            });


        // generate an marker icon with https://apidocs.geoapify.com/playground/icon
        const markerIcon = L.icon({
            iconUrl: `https://api.geoapify.com/v1/icon/?type=awesome&color=%232ea2ff&size=large&scaleFactor=2&apiKey=${myAPIKey}`,
            iconSize: [38, 56], // size of the icon
            iconAnchor: [19, 51], // point of the icon which will correspond to marker's location
            popupAnchor: [0, -60] // point from which the popup should open relative to the iconAnchor
        });

        let marker;

        autocompleteInput.on('select', (location) => {
            // Add marker with the selected location
            if (marker) {
                marker.remove();
            }

            if (location) {
                marker = L.marker([location.properties.lat, location.properties.lon], {
                    icon: markerIcon
                }).addTo(map);

                map.panTo([location.properties.lat, location.properties.lon]);

            }
        });

        $(document).ready(function() {
            $('.geoapify-autocomplete-input').attr('name', 'kokapena').prop('required',true);
        });
        
    </script>
</body>

</html>