<h1>Hasi Saioa</h1>
<form method="POST" action="userlogin">
    {{ csrf_field() }}
    <label>Email</label><br>
    <input value="{{ old('email') }}" type="email" name="email"><br>

    <label>Pasahitza</label><br>
    <input type="password" name="password">

    <button type="submit">Hasi saioa</button>
    <p>Ez duzu konturik? <a href="/register">Sortu hemen</a></p>
</form>

@foreach ($errors->all() as $error)
<li style="color:red;">{{ $error }}</li>
@endforeach