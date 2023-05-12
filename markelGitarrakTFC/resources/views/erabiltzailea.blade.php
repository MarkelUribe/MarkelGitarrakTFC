<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <title>{{ session('name')}}</title>

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
        .content {
            width: 80%;
            margin: auto;
        }

        #datuak {
            display: flex;
            flex-direction: column;

        }

        .autocomplete-container {
            position: relative;
        }

        .autocomplete-panel {
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

            $("#btn_aldatuKokapena").on('click', function() {
                let address = $('.geoapify-autocomplete-input').val();

                $.ajax({
                    type: 'POST',
                    url: '/erabiltzailekokapenaaldatu',
                    data: {
                        'address': address,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('#zurekokapena').html(data.address);
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
    <h1>Erabiltzailearen ezarpenak</h1>
    <div class="content">
        <div id="datuak">
            <label>Izena</label>
            <input disabled type="text" value="{{$user['name']}}">
            <label>Abizena</label>
            <input disabled type="text" value="{{$user['surname']}}">
            <label>Helbide elektronikoa</label>
            <input disabled type="text" value="{{$user['email']}}">

            <label>Argazkia</label>
            @if($user['argazkia']==null)
            <p>Ez daukazu zure perfileko argazkirik igota</p>
            @else
            <img width="150px" src="{{ asset('storage/'. $user->argazkia) }}" alt="Profile Picture">
            @endif
            <form method="POST" action="/erabiltzaileimgaldatu" enctype="multipart/form-data">
                @csrf
                <input required type="file" name="file" accept="image/*">
                <button type="submit">Igo argazkia</button>
            </form>
            <label>Zure kokapena</label>
            <h5 id="zurekokapena">{{$user->kokapena}}</h5>
            <button id="btn_aldatuKokapena">Aldatu kokapena</button>
            <div class="autocomplete-panel">
                <div id="autocomplete" class="autocomplete-container"></div>
            </div>
            <div id="map"></div><br>
            @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
            @endif
        </div>

    </div>
    <script>
        // The API Key provided is restricted to JSFiddle website
        // Get your own API Key on https://myprojects.geoapify.com
        const myAPIKey = "86c99c7541ee4dd681d48fef26c1d135";

        // The Leaflet map Object
        const map = L.map('map', {
            zoomControl: false,
            maxZoom: 18,
            minZoom: 6
        }).setView([<?php echo $latlon[0] ?>, <?php echo $latlon[1] ?>], 12);

        var marker1 = new L.Marker([<?php echo $latlon[0] ?>, <?php echo $latlon[1] ?>]).addTo(map);
        

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
            $('.geoapify-autocomplete-input').attr('name', 'kokapena').prop('required', true);
        });
    </script>
</body>

</html>