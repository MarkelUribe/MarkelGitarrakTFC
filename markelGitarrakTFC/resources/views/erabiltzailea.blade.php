<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <title>{{ session('name')}}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

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
    </style>
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
            <img width="150px" src="{{ asset('storage/' . $user['argazkia']) }}" alt="Profile Picture">

            @endif
            <form method="POST" action="/erabiltzaileimgaldatu" enctype="multipart/form-data">
                @csrf
                <input required type="file" name="file">
                <button type="submit">Igo argazkia</button>
            </form>
            @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        </div>

    </div>
</body>

</html>