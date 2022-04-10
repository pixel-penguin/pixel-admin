<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="author" content="{{ config('app.author') }}">
    <meta name="keywords" content="{{ config('app.keywords') }}">
    <meta name="description" content="{{ isset($HTMLDescription) ? $HTMLDescription : config('app.description') }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" type="image/x-icon" href="/plugins/assets/img/favicon.png">
    <title>Pixel-Penguin Admin</title>
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	
	<link href="{{ asset('css/admin.css') }}" type="text/css" rel="stylesheet" />
	<link href="{{ asset('css/admin-main.css') }}" type="text/css" rel="stylesheet" />
	
  
    <!--[if lt IE 9]>
		<script src="/plugins/assets/js/html5shiv.min.js"></script>
		<script src="/plugins/assets/js/respond.min.js"></script>
	<![endif]-->
	
	<style>
		#changeBusiness{height: 50px; padding: 0 10px; margin-top: 5px}
		
		
	   .submenu ul i{display: none}
		
		.content ul{margin-left: 0 !important; margin-top: 0 !important}
		
		.sidebar{background: #FFF; border-right: #CFCFCF}
		#sidebar-menu ul ul{ background-color: #FFF}
		
		.sidebar-menu li a{ background: #E8E8E8; margin:0 5px 5px 5px}
		.sidebar-menu li a:hover{ background-color: #ff5200; color:#FFF}
		.sidebar-menu li .active{ background: #ff5200; color: #FFF}
		
		.sidebar-menu ul ul a.active{color: #FFF}
		
		.filterStatsProperty input{ width: 100%; border:1px solid #ced4da; height: 40px; padding: 0 20px}
		
		
		.list-group{width: 100%; display: contents}

		.nestable-handle { display: inline-table !important;}
	</style>
	
	@yield('style')
</head>

<body>
	<div id="app">
		<div>
			<div class="main-wrapper">
				<div class="header">
					<div class="header-left">
						<a href="/admin" class="logo">
							<img style="max-height: 50px" src="https://res.cloudinary.com/pixel-penguin/image/upload/c_scale,h_100/v1596973751/pixel_penguin_logos/pixel-admin_sjxmom.png" height="40" alt="">
						</a>
					</div>
					<div class="page-title-box pull-left">
						<!--<h3>Pixel-Penguin Admin</h3>-->
					</div>
					<a id="mobile_btn" class="mobile_btn pull-left" href="#sidebar"><i class="fa fa-bars" aria-hidden="true"></i></a>
					
					<div class="dropdown pull-right" style="margin: 12px">
						<div class="dropdown">
						  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Welcome {{ Auth::user()->name }}
						  </button>
						  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						  	<form id="logout" style="display: none" method="POST" action="{{ route('logout') }}">
							  @csrf							  
							</form>
							<a class="dropdown-item" href="/admin/websitedetail">Website Details</a>
							<a onClick="document.getElementById('logout').submit()" class="dropdown-item" href="#">Logout</a>
						  </div>
						</div>
					</div>
				</div>

				<div>
					<div class="sidebar" id="sidebar">
						<div class="sidebar-inner slimscroll">
							<div id="sidebar-menu" class="sidebar-menu">
								<ul>
									<li style="color: #333" class="menu-title">Main</li>
									
									{!! PixelPenguin\Admin\Http\Controllers\Extras\AdminNavigationController::initiate() !!}

								</ul>

							</div>
						</div>
					</div>
				</div>

				<div class="page-wrapper">
					@yield('content')
				</div>
			</div>    

			<div class="sidebar-overlay" data-reff=""></div>
			
			
			
			
		</div>
	</div> 

	<script src="{{ asset('js/admin.js') }}" type="text/javascript" ></script>
	<script src="{{ asset('js/admin-main.js') }}" type="text/javascript" ></script>

	<script>
		$(document).ready(function(e){
			@if(session()->has('message_text'))
				swal({
				  type: '{{ session()->get('message_type') }}',
				  title: '{{ session()->get('message_title') }}',
				  text: '{{ session()->get('message_text') }}'
				})		

			@endif
		});
	</script>


	@yield('scripts')

	
</body>

</html>