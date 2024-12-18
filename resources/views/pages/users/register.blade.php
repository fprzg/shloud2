@extends("base")

@section("title', 'Register")

@section("main")
<h2>Login</h2>
<form method='POST' action='{{ route("users.register.method") }}'>
    @csrf
    <div>
        <label for='name'>Name:</label>
        <input id='name' type='name' name='name' value='{{ old("name") }}' required autofocus>
    </div>
    <div>
        <label for='email'>Email</label>
        <input id='email' type='email' name='email' value='{{ old("email") }}' required>
    </div>
    <div>
        <label for='password'>Password</label>
        <input id='password' type='password' name='password' required>
    </div>
    <div>
        <label for='password-confirm'>Confirm Password</label>
        <input type='password' name='password_confirmation' id='password-confirm' required>
    </div>

    <div>
        <button type='submit'>Register</button>
    </div>
</form>
@endsection