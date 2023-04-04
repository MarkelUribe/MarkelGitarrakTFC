<link rel="stylesheet" type="text/css" href="{{ url('/css/register.css') }}" />
<script type="text/javascript" src="{{ URL::asset('js/form.js') }}"></script>

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