<style>
    #profilepic{
        width: 30px;
        height: 30px;
        border-radius: 50%;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="/"><img width="200px" src="{{ asset('storage/icon/logoText_beige.png') }}"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class=" collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ms-auto ">
                <li class="nav-item">
                    <a class="nav-link mx-2 <?php if(basename($_SERVER["PHP_SELF"])=='bilatu'){ ?> active <?php } ?> " aria-current="page" href="{{ url('') }}">Eskaintzak</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-2 <?php if(basename($_SERVER["PHP_SELF"])=='likes'){ ?> active <?php } ?> " href="{{ url('likes') }}">Zure LIKE-ak</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mx-2 <?php if(basename($_SERVER["PHP_SELF"])=='zureeskaintzak'){ ?> active <?php } ?> " href="{{ url('zureeskaintzak') }}">Zure Eskaintzak</a>
                </li>
                @if( session('user') !== null)
                <li class="nav-item dropdown">
                    <a class="nav-link mx-2 dropdown-toggle <?php if(basename($_SERVER["PHP_SELF"])=='erabiltzailea'){ ?> active <?php } ?>" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ session('user') }}
                    @if(session('img') == null)
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                        </svg>
                    @else
                        <img id="profilepic" src="{{ asset('storage/'. session('img') ) }}" alt="Profile Picture">
                    @endif
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="{{ url('erabiltzailea') }}">Ezarpenak</a></li>
                        <li><a class="dropdown-item" href="{{ url('logout') }}">Saoia Itxi</a></li>
                    </ul>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link mx-2" href="{{ url('login') }}">Hasi Saioa</a>
                </li>
                @endif
                
            </ul>
        </div>
    </div>
</nav>