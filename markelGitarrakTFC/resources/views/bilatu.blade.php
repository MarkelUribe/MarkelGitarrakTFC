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


    <!--<script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/MTLLoader.js"></script>-->
    <!--<script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/OBJLoader.js"></script>-->
</head>


<!-- Styles -->
<style>
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

    canvas {
        position: absolute;
        top: 0;
        left: 0;
        z-index: -1;
        overflow: hidden;
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
        @if( session('user') !== null)
        <h3>Kaixo {{ session('user') }}</h3>
        @endif
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
        <canvas id="canvas-container"></canvas>
    </div>

    <script src="{{ asset('js/three.js') }}"></script>
    <script>
        // Add your Three.js code here
        // For example, create a scene, camera, and a cube
        var scene = new THREE.Scene();
        var camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        var renderer = new THREE.WebGLRenderer();

        renderer.setSize(window.innerWidth, window.innerHeight);
        document.getElementById('canvas-container').appendChild(renderer.domElement);

        var geometry = new THREE.BoxGeometry();
        var material = new THREE.MeshBasicMaterial({
            color: 0x00ff00
        });
        var cube = new THREE.Mesh(geometry, material);
        scene.add(cube);

        camera.position.z = 5;

        function animate() {
            requestAnimationFrame(animate);
            cube.rotation.x += 0.01;
            cube.rotation.y += 0.01;
            renderer.render(scene, camera);
        }

        animate();
    </script>

</body>

</html>