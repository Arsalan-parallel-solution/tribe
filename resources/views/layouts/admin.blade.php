<?php 
$menu_options = request()->route()->getAction();
$MainValues = substr($menu_options['controller'], strrpos($menu_options['controller'], "\\") + 1);
$MainSettings = explode('@', $MainValues);
$controller = $MainSettings[0];
$action = $MainSettings[1];  
$version=MyFunctions::getCurrentVersion(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo MyFunctions::getSiteTitle(); ?> - Adminstrator</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Leuke" />
    <meta name="keywords" content="Leuke, admin Admin">
    <meta name="_token" content="{{csrf_token()}}" /> 

	<!-- new Stylesheets -->

	<link href="https://fonts.googleapis.com/css2?family=Fjalla+One&display=swap" rel="stylesheet">
	<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
	<link href="{{ asset('css/jquery.atAccordionOrTabs.css?v=').$version }}" rel="stylesheet" type="text/css">

	<!-- new Stylesheets -->

	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css?v=').$version }}"/>
	<link rel="stylesheet" href="{{ asset('css/font-awesome.min.css?v=').$version }}"/>
	<link rel="stylesheet" href="{{ asset('css/admin-style.css?v=').$version }}"/>
	<link rel="stylesheet" href="{{ asset('css/style1.css?v=').$version }}"/>
	<!-- Style.css -->
	<link href="https://fonts.googleapis.com/css2?family=Fjalla+One&display=swap" rel="stylesheet">
	<link rel="icon" href="{{ asset('default/favicon.ico') }}" type="image/x-icon">
	<script type="text/javascript" src="{{ asset('files/jquery/js/jquery.min.js?v=').$version }}"></script>
    <script type="text/javascript" src="{{ asset('files/jquery-ui/js/jquery-ui.min.js?v=').$version }}"></script>
    <script type="text/javascript" src="{{ asset('files/popper.js/js/popper.min.js?v=').$version }}"></script>
    <script type="text/javascript" src="{{ asset('files/bootstrap/js/bootstrap.min.js?v=').$version }}"></script>
	<link href="{{ asset('datatables/datatables.min.css?v=').$version }}" rel="stylesheet" type="text/css" />
<!--     <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"> -->
    <link href="{{ asset('datatables/plugins/bootstrap/datatables.bootstrap.css?v=').$version }}" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="{{ asset('js/jquery.dataTables.js?v=').$version }}"></script>
	
	<script src="{{ asset('files/amchart/amcharts.js?v=').$version }}"></script>
    <script src="{{ asset('files/amchart/serial.js?v=').$version }}"></script>
    <script src="{{ asset('files/amchart/light.js?v=').$version }}"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
	
	<link href="{{ asset('css/bootstrap-toggle.min.css?v=').$version }}" rel="stylesheet" />
	<link href="{{ asset('gradx-master/gradX.css?v=').$version }}" rel="stylesheet" />
	<link href="{{ asset('gradx-master/colorpicker/css/colorpicker.css?v=').$version }}" rel="stylesheet" />
	
	<script src="{{ asset('js/bootstrap-toggle.min.js?v=').$version }}"></script>
	<script src="{{ asset('js/sweetalert.min.js?v=').$version }}"></script>
	<?php if(Route::currentRouteName()!='admin.app_settings'){ ?>
	<script src="{{ asset('files/wysiwyg-editor/js/tinymce.min.js?v=').$version }}"></script>
	<?php } ?>
    <!-- Custom js -->
    <script src="{{ asset('files/wysiwyg-editor/wysiwyg-editor.js?v=').$version }}"></script>
    <script src="{{ asset('gradx-master/lib/js/jquery.js?v=').$version }}"></script>
    <script src="{{ asset('gradx-master/colorpicker/js/colorpicker.js?v=').$version }}"></script>
    <script src="{{ asset('gradx-master/dom-drag.js?v=').$version }}"></script>
    <script src="{{ asset('gradx-master/gradX.js?v=').$version }}"></script>
</head>
<body class="menuopened" >
	<section class="no-pad"> 
		<div class="main">	
		<!-- topbar -->	
		<div class="container-fluid">
			<!-- <div class="row topbar">
				
				<div class="col-lg-12">
					<div class="top-rightside">
					  
						<a href="{{ route('admin.logout') }}" class="btn btn-primary logout">
							<span>Logout</span> <i class="fa fa-sign-out" aria-hidden="true"></i> 
						</a>
					</div>
				</div>
			</div> -->
		<!-- topbar -->	
		<div class="row">
			
		<!-- left menu -->	
		<div class="container-fluid">	
            @include('includes.admin.sidebar')
				
		<!-- left menu -->		
		<div class="row">
		<!-- rightside-main -->
		<div class="col-lg-10 no-pad right-main" id="main-content">
			<div class="topbar">
				
				<div class="col-lg-12">
					<div class="top-rightside">
					  
						<a href="{{ route('admin.logout') }}" class="btn btn-primary logout">
							<span>Logout</span> <i class="fa fa-sign-out" aria-hidden="true"></i> 
						</a>
					</div>
				</div>
			</div>
             @yield('content')
        </div>
		<!-- rightside-main -->			
				</div>
				</div>	
			</div>	
			</div>	
			
			
			
			
		
	</section>
		
	<!-- <script src="js/bootstrap.min.js"></script> -->
    <script src="{{ asset('js/bootstrap.min.js?v=').config('app.file_version') }}"></script>

	<script>
		$( document ).ready(function() {
		var url = window.location;
			$('ul li a').filter(function() {
				return this.href == url;
			}).addClass('active').closest('.sub-menu').addClass('show').parent().removeClass('collapsed');

		});
</script>
<script>
		$(document).ready(function(){
			$('#closeNav').click(function(){
				$(this).hide();
				var body = $("body");
			  	var element = $("#navBar");
				if(element.hasClass("mystyle")){
					element.removeClass("mystyle");
					body.removeClass("menuopened");
				}else{
					element.addClass("mystyle");
					body.addClass("menuopened");
				}	
			});			
		});
		
		function openNav() {
			var element = $("#navBar");
			var body = $("body");
			if(element.hasClass("mystyle")){
				$('#closeNav').hide();
				element.removeClass("mystyle");
				body.removeClass("menuopened");
		
			}else{
				$('#closeNav').show();
				element.addClass("mystyle");
				body.addClass("menuopened");
			}		  
		}
	</script>
	<script>
		/* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
		var dropdown = document.getElementsByClassName("dropdown-btn");
		var i;

		for (i = 0; i < dropdown.length; i++) {
			dropdown[i].addEventListener("click", function() {
				this.classList.toggle("active");
				var dropdownContent = this.nextElementSibling;
				if (dropdownContent.style.display === "block") {
					dropdownContent.style.display = "none";
				} else {
					dropdownContent.style.display = "block";
				}
			});
		}
		if($(window).width() < 769)
		{
			$("body").removeClass("menuopened");
			$("#navBar").removeClass("mystyle");
		}
	</script>
</body>
</html>
