<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title')</title>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"/>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />

	<link rel="stylesheet" href="{{asset('css/styles.css')}}">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>
<body>
	<nav class="navbar navbar-dark bg-primary d-fixed">
		<div class="container-fluid">
			<a class="navbar-brand" href="{{url('tasks')}}">
				<span>1210 Service Inc.</span>
			</a>
			<h6 class="text-white">Welcome, {{auth()->user()->name}}</h6>
		</div>
	</nav>
	
	<div class="main-content container-fluid">
		<div class="container">
			<a class="btn btn-secondary mb-5 float-end logout" href="{{url('logout')}}">Logout</a>
		</div>
		@yield('main-content')
	</div>

	<footer>
		<p class="mt-2 text-center">All rights reserved. {{env('APP_NAME')}} &copy; {{date('Y')}}</p>
	</footer>
    
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>