<!doctype html>
<html lang='es'>
	<head>
		<meta charset='utf-8'>
		<title>@yield("title", "Home Page") - SHLoud2</title>
		<!-- Link to the CSS stylesheet and favicon -->
		<link rel='stylesheet' href='{{ asset("css/main.css") }}'>
		<link rel='shortcut icon' href='{{ asset("img/favicon.ico") }}' type='image/x-icon'>
		<!-- Also link to some fonts hosted by Google -->
		<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Ubuntu+Mono:400,700'>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	</head>
	<body>
		<header>
			<h1><a href='/'>SHLoud2</a></h1>
		</header>
        @include("partials.nav")
		<main>
            @if(session("flash"))
                <div class='flash'>
                    {{ session("flash") }}
                </div>
            @endif
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class='flash'>
                        {{ $error }}
                    </div>
                @endforeach
            @endif

            @yield("main")
		</main>
		<footer>
			Powered by <a href='https://laravel.com/'>Laravel</a>
		</footer>
		<script src='{{ asset("js/main.js") }}' type='text/javascript'></script>
	</body>
</html>