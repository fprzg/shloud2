<nav>
	<div>
	    <a href='/'>Home</a>
		@if (isset($isAuthenticated) && $isAuthenticated)
	    	<a href='{{ route("files.upload", $currentDir) }}'>Upload file</a>
			@if (isset($currentDir) && $currentDir != null)
			<a href='{{ route("files.mkdir.method", $currentDir) }}'>Create directory</a>
			@endif
		@endif
	</div>
	<div>
		@if (isset($isAuthenticated) && $isAuthenticated)
		    <form action='{{ route("users.logout") }}' method='POST'>
                @csrf
		    	<button>Logout</button>
		    </form>
			<a href='{{ route("users.me") }}'>Account</a>
		@else
	        <a href='{{ route("users.register") }}'>Signup</a>
	        <a href='{{ route("users.login") }}'>Login</a>
		@endif
	</div>
</nav>