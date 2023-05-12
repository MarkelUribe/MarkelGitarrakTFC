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


    <!-- Map stuff -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js" integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>

    <!-- Styles -->
    <style>
        #map {
            height: 280px;
            margin: 15px;
        }

        #content {
            width: 70%;
            margin: auto;
        }

        #eskaintzajabeaimg {
            width: 40px;
            height: 40px;
            border-radius: 30px;
        }

        #likeetaerosi {
            display: flex;
        }

        #btn_erosi {
            margin: 10px;
            font-size: large;
            font-weight: bold;
            width: 30%;
            border-radius: 15px;
        }

        #btn_like {
            display: flex;
            flex-direction: column;
            justify-content: center;
            cursor: pointer;
        }

        #btn_like img {
            width: 30px;
        }


        .fade:not(.show) {
            opacity: 1;
        }

        .slideshow-container {

            padding: 15px;
            border-radius: 10px;
            margin: auto;
            background-color: #b1b1b1;
        }

        .slideshow-container img {
            max-height: 300px;
            max-width: 100%;
            width: auto !important;
        }

        .mySlides {
            text-align: center;
        }

        .next {
            text-decoration: none;
        }

        .prev {
            text-decoration: none;
        }
    </style>
    <link rel="stylesheet" href="{{ URL::asset('css/carousel.css') }}" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#btn_ezabatu").click(function() {
                let text = "Zure eskaintza ezabatzea nahi duzu?\nDatu eta argazki denak ezabatuko dira.";
                if (confirm(text) == true) {
                    window.location.href = "{{ url('eskaintzaezabatu') }}/{{$eskaintza->id}}";
                }
            });

            $('#btn_like').click(function() {
                $.ajax({
                    type: 'POST',
                    url: '/likeorunlike',
                    data: {
                        'eskaintzaId': '{{$eskaintza->id}}',
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('#img_like').attr("src",data.img);
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
        @if( session('user') !== null)
        @if( $user->id === $eskaintza->userId)
        <a href="{{ url('eskaintzaaldatu') }}/{{$eskaintza->id}}">✎</a>
        <button id="btn_ezabatu">Ezabatu</button>
        @else
        <i>Saltzailea</i><br>
        <img id="eskaintzajabeaimg" src="{{ asset('storage/'. $eskaintzajabea->argazkia ) }}">
        <label>{{$eskaintzajabea->name}}</label>
        @endif
        @endif
        <div class="slideshow-container">
            <?php
            $img_arr = array_filter(explode(",", $eskaintza->argazkiak), fn ($value) => !is_null($value));
            foreach ($img_arr as $key => $newarr) {
                if (trim($newarr) === '') {
                    unset($img_arr[$key]);
                }
            }
            $img_arr = array_values($img_arr);
            foreach ($img_arr as $key => $value) {
            ?>
                <div class="mySlides fade">
                    <div class="numbertext">{{$key+1}} / {{count($img_arr)}}</div>
                    <img src="{{ asset('storage/'. trim($value))}}" style="width:100%">
                </div>
            <?php
            }
            ?>

            <a class="prev" onclick="plusSlides(-1)">❮</a>
            <a class="next" onclick="plusSlides(1)">❯</a>

        </div>
        <br>

        <div style="text-align:center">
            <?php
            foreach ($img_arr as $key => $value) {
            ?>
                <span class="dot" onclick="currentSlide(<?php echo $key + 1; ?>)"></span>

            <?php
            }
            ?>
        </div>
        <h2><b>{{$eskaintza->prezioa}}€</b></h2>
        <h3>{{$eskaintza->izena}}</h3>

        @if( $user->id != $eskaintza->userId)
        <div id="likeetaerosi">
            <a id="btn_like"><img id="img_like" src="{{$likeimg}}"></a>
            <button id="btn_erosi">EROSI</button><br>
        </div>

        @endif

        <i>{{$eskaintza->estatua}}</i><br>
        <i>{{$mota->mota}}</i><br><br>
        <p>{{$eskaintza->azalpena}}</p>
        <label>Kokapena</label>
        @if( session('user') !== null)
        @if( $user->id === $eskaintza->userId)
        <br><i>{{$eskaintza->kokapena}}</i>
        @endif
        @endif
        @if($latlon == [0,0])
        <p>Kokapen hau ezin da mapan erakutsi</p>
        @else
        <p>Zure kokapenetik {{$distantzia}}-tara dago</p>
        @endif

        <div id="map"></div><br>




    </div>

    <script>
        let slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("dot");
            if (n > slides.length) {
                slideIndex = 1
            }
            if (n < 1) {
                slideIndex = slides.length
            }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
        }


        var tileLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: false
        });

        var map = L.map('map', {
                zoomControl: true,
                layers: [tileLayer],
                maxZoom: 18,
                minZoom: 6
            })
            .setView([<?php echo $latlon[0] ?>, <?php echo $latlon[1] ?>], 15);

        var circle = L.circle([<?php echo $latlon[0] ?>, <?php echo $latlon[1] ?>], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: 350
        }).addTo(map);


        setTimeout(function() {
            map.invalidateSize()
        }, 800);
    </script>

</body>

</html>