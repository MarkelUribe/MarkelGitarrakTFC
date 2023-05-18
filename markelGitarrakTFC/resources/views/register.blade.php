<link rel="stylesheet" type="text/css" href="{{ url('/css/register.css') }}" />
<script type="text/javascript" src="{{ URL::asset('js/form.js') }}"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>

<script src="https://unpkg.com/@geoapify/geocoder-autocomplete@^1/dist/index.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://unpkg.com/@geoapify/geocoder-autocomplete@^1/styles/minimal.css">
<style>
    .autocomplete-container {
        position: relative;
    }

    .autocomplete-panel {
        z-index: 1000;
    }
</style>

<!-- https://codepen.io/meodai/pen/rNedxBa -->

<div id="content">
    <h1>Kontu berri bat sortu</h1>

    <form method="POST" action="userRegister">
        {{ csrf_field() }}
        <div class="card">
            <h2><svg class="icon" aria-hidden="true">
                    <use xlink:href="#icon-coffee" href="#icon-coffee" />
                </svg>Sortu kontua</h2>
            <label class="input">
                <input class="input__field" value="{{ old('izena') }}" required type="text" name="izena" placeholder=" " />
                <span class="input__label">Izena</span>
            </label>
            <label class="input">
                <input class="input__field" value="{{ old('abizena') }}" required type="text" name="abizena" placeholder=" " />
                <span class="input__label">Abizena</span>
            </label>
            <label class="input">
                <input class="input__field" value="{{ old('email') }}" required type="email" name="email" placeholder=" " />
                <span class="input__label">Helbide elektronikoa</span>
            </label>
            <label class="input">
                <input class="input__field" required type="password" name="password" placeholder=" " />
                <span class="input__label">Pasahitza</span>
            </label>
            <label class="input">
                <input class="input__field" required type="password" name="password_confirmation" placeholder=" " />
                <span class="input__label">Pasahitza errepikatu</span>
            </label>

            <div class="autocomplete-panel">
                <div id="autocomplete" class="autocomplete-container"></div>
            </div>

            <div id="map"></div>
            <div class="button-group">
                <button type="submit">Bidali</button>
                <button type="reset">Reset</button>
            </div>
        </div>
        @if ($errors->any())
        <div class="text-red-500">
            <ul>
                @foreach ($errors->all() as $error)
                <li style="color:red;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </form>

</div>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        $('.geoapify-autocomplete-input').attr('name', 'kokapena').prop('required', true);
    });
</script>