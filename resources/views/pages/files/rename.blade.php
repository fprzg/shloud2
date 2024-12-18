@extends("base")

@section("title", "Rename")

@section("main")
<h2>Current Directory: {{ $currentDir }}</h2>
<h2>Create Directory</h2>
<form method='POST' action='{{ route("files.rename.method", $currentDir) }}'>
    @csrf
    @method('PUT')
    <div>
        <label for='new_name'>New name:</label>
        <input id='name' type='name' name='new_name' value='' required autofocus>
    </div>
    <div>
        <button type='submit'>Rename</button>
    </div>
</form>
@endsection