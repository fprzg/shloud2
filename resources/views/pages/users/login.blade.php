@extends('base')

@section("title", "Register")

@section("main")
<h2>Login</h2>
<form method="POST" action="{{ route('users.login') }}">
    @csrf
    <div>
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
    </div>

    <div>
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>
    </div>

    <div>
        <button type="submit">Login</button>
    </div>
</form>
<div>
    <p>Don't have an account? <a href='{{ route("users.register") }}'>Regiter now!</a>
</div>
@endsection