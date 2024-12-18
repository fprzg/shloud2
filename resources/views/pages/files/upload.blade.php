@extends("base")

@section("title", "File Upload")

@section("main")
<form action="{{ route('files.upload.method', $currentDir) }}" method="POST" enctype="multipart/form-data">
    @csrf
	<div>
		<label>File name:</label>
        @if (isset($fieldErrors) && $fieldErrors !== [])
			<label class='error'>{{ $fieldErrors['name'] }}</label>
        @endif
		<input type='text' name='file_name' content=''>
	</div>
    <div>
        <input type="file" name="file" required>
	</div>
    <div>
        <button type="submit">Upload</button>
    </div>
</form>
@endsection