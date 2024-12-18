@extends("base")

@section("title", "Uploaded Files")

@section("main")
<h2>Uploaded Files</h2>
@if (isset($dirContents) && count($dirContents) > 0)
    <table>
    	<tr>
    		<th>Title</th>
			<th>Size</th>
    		<th>Created</th>
			<th>Actions</th>
    	</tr>
        @foreach($dirContents as $item)
			@if ($item['type'] == 'file')
    	    	<tr>
    	    		<td><a href='{{ route("files.download.method", $item["path"]) }}'>{{ $item['name'] }}</a></td>
    	    		<td>{{ $item['size'] }}</td>
    	    		<td>{{ $item['created_at'] }}</td>
					<td>
						<form method='POST' action='{{ route("files.delete.method", $item["path"]) }}'>
						    @csrf
							@method('DELETE')
							<button type='submit' title='Delete'><i class='fas fa-trash'></i></button>
						</form>
						<button type='submit' title='Rename'>
							<a href='{{ route("files.rename", $item["path"]) }}'>
								<i class='fas fa-pencil-alt'></i>
							</a>
						</button>
						<form method='POST' action='{{ route("files.download.method", $item["path"]) }}'>
						    @csrf
							@method('GET')
    						<button type='submit' title='Download'><i class='fas fa-cloud-download-alt'></i></button>
						</form>
					</td>
    	    	</tr>
			@elseif ($item['type'] == 'folder')
    	    	<tr>
    	    		<td><a href='{{ route("files.list", $item["path"]) }}'>{{ $item['name'] }}/</a></td>
    	    		<td></td>
    	    		<td>{{ $item['created_at'] }}</td>
					<td>
						<form method='POST' action='{{ route("files.delete.method", $item["path"]) }}'>
						    @csrf
							@method('DELETE')
							<button type='submit' title='Delete'><i class='fas fa-trash'></i></button>
						</form>
						<button type='submit' title='Rename'>
							<a href='{{ route("files.rename", $item["path"]) }}'>
								<i class='fas fa-pencil-alt'></i>
							</a>
						</button>
					</td>
    	    	</tr>
			@endif
        @endforeach
    </table>
@else
    <p>There's nothing to see here yet!</p>
@endif
@endsection