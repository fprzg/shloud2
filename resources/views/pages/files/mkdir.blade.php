@extends("base")

@section("title", "Create Directory")

@section("main")
<h2>Current Directory: {{ $currentDir }}</h2>
<h2>Create Directory</h2>
<form method='POST' action='{{ route("files.mkdir.method", $currentDir) }}'>
    @csrf
    <div>
        <label for='dir_name'>Directory name:</label>
        <input id='name' type='name' name='dir_name' value='' required autofocus>
    </div>
    <div>
        <button type='submit'>Create</button>
    </div>
</form>
@endsection