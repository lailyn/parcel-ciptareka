<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

		<title>{{ get_setting('app') }}</title>

		<meta name="description" content="{{ get_setting('app') }}">
		<meta name="author" content="{{ get_setting('app') }}">
		<meta name="robots" content="noindex, nofollow">

		<!-- Open Graph Meta -->
		<meta property="og:title" content="{{ get_setting('app') }}">
		<meta property="og:site_name" content="{{ get_setting('app') }}">
		<meta property="og:description" content="{{ get_setting('app') }}">
		<meta property="og:type" content="website">
		<meta property="og:url" content="">
		<meta property="og:image" content="">

		<!-- Icons -->
		<!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
		<link rel="shortcut icon" href="{{ asset('ima49es/'.get_setting('favicon')) }}">
		<link rel="icon" type="image/png" sizes="192x192" href="{{ asset('ima49es/'.get_setting('favicon')) }}">
		<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('ima49es/'.get_setting('favicon')) }}">
		<!-- END Icons -->
		<link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
		<!-- Stylesheets -->
		<!-- Fonts and OneUI framework -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">
		<link rel="stylesheet" id="css-main" href="{{ asset('css/oneui.min.css') }}">
		<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">		
		
		<style type="text/css">
		#loading-status{
			background-color: #fff;
			opacity: .7;
			position: fixed;
			top: 0;
			left:0;
			width: 100%;
			height: 100%;
			z-index: 1000;
		}
		</style>
		<!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
		<!-- <link rel="stylesheet" id="css-theme" href="{{ asset('css/themes/amethyst.min.css') }}"> -->
		<!-- END Stylesheets -->
	</head>
	<body onload="hidekan()">		
		<div id="loading-status">	  
			<center><img style="margin-top:200px;" src="{{ asset('loading.gif') }}"></center>
	      <div class="textLoader">
	          <center>	          
	          <br>
	          <b>... Please Wait ... </b>
	          </center>
	      </div>
	  </div>
		<div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed">
						
			<nav id="sidebar" aria-label="Main Navigation">
				<!-- Side Header -->
				<div class="content-header bg-white-5">
					<!-- Logo -->
					<a class="font-w600 text-dual" href="{{ route('dashboard.index') }}">
						<img width="30px" src="{{ asset('ima49es/'.get_setting('logo')) }}">  
						<span class="smini-hide">
							{{ get_setting('app') }}
						</span>
					</a>

					<div>
						<!-- Color Variations -->
						<div class="dropdown d-inline-block ml-3">
							<a class="text-dual font-size-sm" id="sidebar-themes-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">
								<i class="si si-drop"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-right font-size-sm smini-hide border-0" aria-labelledby="sidebar-themes-dropdown">
								<!-- Color Themes -->
								<!-- Layout API, functionality initialized in Template._uiHandleTheme() -->
								<a class="dropdown-item d-flex align-items-center justify-content-between" data-toggle="theme" data-theme="default" href="#">
									<span>Default</span>
									<i class="fa fa-circle text-default"></i>
								</a>
								<a class="dropdown-item d-flex align-items-center justify-content-between" data-toggle="theme" data-theme="assets/css/themes/amethyst.min.css" href="#">
									<span>Amethyst</span>
									<i class="fa fa-circle text-amethyst"></i>
								</a>
								<a class="dropdown-item d-flex align-items-center justify-content-between" data-toggle="theme" data-theme="assets/css/themes/city.min.css" href="#">
									<span>City</span>
									<i class="fa fa-circle text-city"></i>
								</a>
								<a class="dropdown-item d-flex align-items-center justify-content-between" data-toggle="theme" data-theme="assets/css/themes/flat.min.css" href="#">
									<span>Flat</span>
									<i class="fa fa-circle text-flat"></i>
								</a>
								<a class="dropdown-item d-flex align-items-center justify-content-between" data-toggle="theme" data-theme="assets/css/themes/modern.min.css" href="#">
									<span>Modern</span>
									<i class="fa fa-circle text-modern"></i>
								</a>
								<a class="dropdown-item d-flex align-items-center justify-content-between" data-toggle="theme" data-theme="assets/css/themes/smooth.min.css" href="#">
									<span>Smooth</span>
									<i class="fa fa-circle text-smooth"></i>
								</a>
								<!-- END Color Themes -->

								<div class="dropdown-divider"></div>

								<!-- Sidebar Styles -->
								<!-- Layout API, functionality initialized in Template._uiApiLayout() -->
								<a class="dropdown-item" data-toggle="layout" data-action="sidebar_style_light" href="#">
									<span>Sidebar Light</span>
								</a>
								<a class="dropdown-item" data-toggle="layout" data-action="sidebar_style_dark" href="#">
									<span>Sidebar Dark</span>
								</a>
								<!-- Sidebar Styles -->

								<div class="dropdown-divider"></div>

								<!-- Header Styles -->
								<!-- Layout API, functionality initialized in Template._uiApiLayout() -->
								<a class="dropdown-item" data-toggle="layout" data-action="header_style_light" href="#">
									<span>Header Light</span>
								</a>
								<a class="dropdown-item" data-toggle="layout" data-action="header_style_dark" href="#">
									<span>Header Dark</span>
								</a>
								<!-- Header Styles -->
							</div>
						</div>
						<!-- END Themes -->

						<!-- Close Sidebar, Visible only on mobile screens -->
						<!-- Layout API, functionality initialized in Template._uiApiLayout() -->
						<a class="d-lg-none text-dual ml-3" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
							<i class="fa fa-times"></i>
						</a>
						<!-- END Close Sidebar -->
					</div>
					<!-- END Options -->
				</div>
				<!-- END Side Header -->

				@include('layout.sidebar')

			<!-- Header -->
			<header id="page-header">
				<!-- Header Content -->
				<div class="content-header">
					<!-- Left Section -->
					<div class="d-flex align-items-center">
						<!-- Toggle Sidebar -->
						<!-- Layout API, functionality initialized in Template._uiApiLayout()-->
						<button type="button" class="btn btn-sm btn-dual mr-2 d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
							<i class="fa fa-fw fa-bars"></i>
						</button>
						<!-- END Toggle Sidebar -->

						<!-- Toggle Mini Sidebar -->
						<!-- Layout API, functionality initialized in Template._uiApiLayout()-->
						<button type="button" class="btn btn-sm btn-dual mr-2 d-none d-lg-inline-block" data-toggle="layout" data-action="sidebar_mini_toggle">
							<i class="fa fa-fw fa-ellipsis-v"></i>
						</button>
						<!-- END Toggle Mini Sidebar -->

						<!-- Apps Modal -->
						<!-- Opens the Apps modal found at the bottom of the page, after footer’s markup -->
						<button type="button" class="btn btn-sm btn-dual mr-2" data-toggle="modal" data-target="#one-modal-apps">
							<i class="si si-grid"></i>
						</button>
						<!-- END Apps Modal -->

						<!-- Open Search Section (visible on smaller screens) -->
						<!-- Layout API, functionality initialized in Template._uiApiLayout() -->
						<button type="button" class="btn btn-sm btn-dual d-sm-none" data-toggle="layout" data-action="header_search_on">
							<i class="si si-magnifier"></i>
						</button>
						<!-- END Open Search Section -->
						
					</div>
					<!-- END Left Section -->

					<!-- Right Section -->
					<div class="d-flex align-items-center">
						<!-- User Dropdown -->
						<div class="dropdown d-inline-block ml-2">
							<button type="button" class="btn btn-sm btn-dual" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<img class="rounded" src="{{ asset('media/avatars/avatar10.jpg') }}" alt="Header Avatar" style="width: 18px;">
								<span class="d-none d-sm-inline-block ml-1">{{ session()->get('username') }}</span>
								<i class="fa fa-fw fa-angle-down d-none d-sm-inline-block"></i>
							</button>
							<div class="dropdown-menu dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="page-header-user-dropdown">
								<div class="p-3 text-center bg-primary">
									<img class="img-avatar img-avatar48 img-avatar-thumb" src="{{ asset('media/avatars/avatar10.jpg') }}" alt="">
								</div>
								<div class="p-2">									
									<a class="dropdown-item d-flex align-items-center justify-content-between" href="be_pages_generic_profile.html">
										<span>Profile</span>										
										<i class="si si-user ml-1"></i>
									</a>									
									<a class="dropdown-item d-flex align-items-center justify-content-between" onclick="return confirm('Anda yakin ingin logout dari sistem?')" href="{{ route('logout') }}">
										<span>Log Out</span>
										<i class="si si-logout ml-1"></i>
									</a>
								</div>
							</div>
						</div>
						<!-- END User Dropdown -->

						<!-- Notifications Dropdown -->
						<!-- <div class="dropdown d-inline-block ml-2">
							<button type="button" class="btn btn-sm btn-dual" id="page-header-notifications-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="si si-bell"></i>
								<span class="badge badge-primary badge-pill">6</span>
							</button>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="page-header-notifications-dropdown">
								<div class="p-2 bg-primary text-center">
									<h5 class="dropdown-header text-uppercase text-white">Notifications</h5>
								</div>
								<ul class="nav-items mb-0">
									<li>
										<a class="text-dark media py-2" href="javascript:void(0)">
											<div class="mr-2 ml-3">
												<i class="fa fa-fw fa-check-circle text-success"></i>
											</div>
											<div class="media-body pr-2">
												<div class="font-w600">You have a new follower</div>
												<small class="text-muted">15 min ago</small>
											</div>
										</a>
									</li>
									<li>
										<a class="text-dark media py-2" href="javascript:void(0)">
											<div class="mr-2 ml-3">
												<i class="fa fa-fw fa-plus-circle text-info"></i>
											</div>
											<div class="media-body pr-2">
												<div class="font-w600">1 new sale, keep it up</div>
												<small class="text-muted">22 min ago</small>
											</div>
										</a>
									</li>
									<li>
										<a class="text-dark media py-2" href="javascript:void(0)">
											<div class="mr-2 ml-3">
												<i class="fa fa-fw fa-times-circle text-danger"></i>
											</div>
											<div class="media-body pr-2">
												<div class="font-w600">Update failed, restart server</div>
												<small class="text-muted">26 min ago</small>
											</div>
										</a>
									</li>
									<li>
										<a class="text-dark media py-2" href="javascript:void(0)">
											<div class="mr-2 ml-3">
												<i class="fa fa-fw fa-plus-circle text-info"></i>
											</div>
											<div class="media-body pr-2">
												<div class="font-w600">2 new sales, keep it up</div>
												<small class="text-muted">33 min ago</small>
											</div>
										</a>
									</li>
									<li>
										<a class="text-dark media py-2" href="javascript:void(0)">
											<div class="mr-2 ml-3">
												<i class="fa fa-fw fa-user-plus text-success"></i>
											</div>
											<div class="media-body pr-2">
												<div class="font-w600">You have a new subscriber</div>
												<small class="text-muted">41 min ago</small>
											</div>
										</a>
									</li>
									<li>
										<a class="text-dark media py-2" href="javascript:void(0)">
											<div class="mr-2 ml-3">
												<i class="fa fa-fw fa-check-circle text-success"></i>
											</div>
											<div class="media-body pr-2">
												<div class="font-w600">You have a new follower</div>
												<small class="text-muted">42 min ago</small>
											</div>
										</a>
									</li>
								</ul>
								<div class="p-2 border-top">
									<a class="btn btn-sm btn-light btn-block text-center" href="javascript:void(0)">
										<i class="fa fa-fw fa-arrow-down mr-1"></i> Load More..
									</a>
								</div>
							</div>
						</div> -->
						<!-- END Notifications Dropdown -->						
					</div>
					<!-- END Right Section -->
				</div>
				<!-- END Header Content -->

				<!-- Header Search -->
				<div id="page-header-search" class="overlay-header bg-white">
					<div class="content-header">
						<form class="w-100" action="be_pages_generic_search.html" method="POST">
							<div class="input-group input-group-sm">
								<div class="input-group-prepend">
									<!-- Layout API, functionality initialized in Template._uiApiLayout() -->
									<button type="button" class="btn btn-danger" data-toggle="layout" data-action="header_search_off">
										<i class="fa fa-fw fa-times-circle"></i>
									</button>
								</div>
								<input type="text" class="form-control" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
							</div>
						</form>
					</div>
				</div>
				<!-- END Header Search -->

				<!-- Header Loader -->
				<!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
				<div id="page-header-loader" class="overlay-header bg-white">
					<div class="content-header">
						<div class="w-100 text-center">
							<i class="fa fa-fw fa-circle-notch fa-spin"></i>
						</div>
					</div>
				</div>
				<!-- END Header Loader -->
			</header>
			<!-- END Header -->

			<!-- Main Container -->
			@yield('content')
			<!-- END Main Container -->

			<!-- Footer -->
			<footer id="page-footer" class="bg-body-light">
				<div class="content py-3">
					<div class="row font-size-sm">
						<div class="col-sm-6 order-sm-2 py-1 text-center text-sm-right">
							Crafted by <a class="font-w600" href="https://monju.co.id" target="_blank">klik-ciptareka</a>
						</div>
						<div class="col-sm-6 order-sm-1 py-1 text-center text-sm-left">
							<a class="font-w600" href="" target="_blank">{{ get_setting('app') }} 0.0.1</a> &copy; <span data-toggle="year-copy"><?=date("Y")?></span>
						</div>
					</div>
				</div>
			</footer>
			<!-- END Footer -->

			<!-- Apps Modal -->
			<!-- Opens from the modal toggle button in the header -->
			<div class="modal fade" id="one-modal-apps" tabindex="-1" role="dialog" aria-labelledby="one-modal-apps" aria-hidden="true">
				<div class="modal-dialog modal-dialog-top modal-sm" role="document">
					<div class="modal-content">
						<div class="block block-themed block-transparent mb-0">
							<div class="block-header bg-primary-dark">
								<h3 class="block-title">Apps</h3>
								<div class="block-options">
									<button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
										<i class="si si-close"></i>
									</button>
								</div>
							</div>
							<div class="block-content block-content-full">
								<div class="row gutters-tiny">
									<div class="col-6">
										<!-- CRM -->
										<a class="block block-rounded block-themed bg-default" href="javascript:void(0)">
											<div class="block-content text-center">
												<i class="si si-speedometer fa-2x text-white-75"></i>
												<p class="font-w600 font-size-sm text-white mt-2 mb-3">
													Dashboard
												</p>
											</div>
										</a>
										<!-- END CRM -->
									</div>
									<div class="col-6">
										<!-- Products -->
										<a class="block block-rounded block-themed bg-danger" href="javascript:void(0)">
											<div class="block-content text-center">
												<i class="si si-rocket fa-2x text-white-75"></i>
												<p class="font-w600 font-size-sm text-white mt-2 mb-3">
													Transaction
												</p>
											</div>
										</a>
										<!-- END Products -->
									</div>
									<div class="col-6">
										<!-- Sales -->
										<a class="block block-rounded block-themed bg-success mb-0" href="javascript:void(0)">
											<div class="block-content text-center">
												<i class="si si-printer fa-2x text-white-75"></i>
												<p class="font-w600 font-size-sm text-white mt-2 mb-3">
													Reports
												</p>
											</div>
										</a>
										<!-- END Sales -->
									</div>
									<div class="col-6">
										<!-- Payments -->
										<a class="block block-rounded block-themed bg-warning mb-0" href="javascript:void(0)">
											<div class="block-content text-center">
												<i class="si si-wallet fa-2x text-white-75"></i>
												<p class="font-w600 font-size-sm text-white mt-2 mb-3">
													Payments
												</p>
											</div>
										</a>
										<!-- END Payments -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END Apps Modal -->
		</div>
		<!-- END Page Container -->

		<!--
			OneUI JS Core

			Vital libraries and plugins used in all pages. You can choose to not include this file if you would like
			to handle those dependencies through webpack. Please check out assets/_es6/main/bootstrap.js for more info.

			If you like, you could also include them separately directly from the assets/js/core folder in the following
			order. That can come in handy if you would like to include a few of them (eg jQuery) from a CDN.

			assets/js/core/jquery.min.js
			assets/js/core/bootstrap.bundle.min.js
			assets/js/core/simplebar.min.js
			assets/js/core/jquery-scrollLock.min.js
			assets/js/core/jquery.appear.min.js
			assets/js/core/js.cookie.min.js
		-->
		<script src="{{ asset('js/oneui.core.min.js') }}"></script>

		<!--
			OneUI JS

			Custom functionality including Blocks/Layout API as well as other vital and optional helpers
			webpack is putting everything together at assets/_es6/main/app.js
		-->
		<script src="{{ asset('js/oneui.app.min.js') }}"></script>

		<!-- Page JS Plugins -->
		<script src="{{ asset('js/plugins/chart.js/Chart.bundle.min.js') }}"></script>
		<script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
		<script src="{{ asset('js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
		<script src="{{ asset('js/plugins/datatables/buttons/dataTables.buttons.min.js') }}"></script>
		<script src="{{ asset('js/plugins/datatables/buttons/buttons.print.min.js') }}"></script>
		<script src="{{ asset('js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script>
		<script src="{{ asset('js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script>
		<script src="{{ asset('js/plugins/datatables/buttons/buttons.colVis.min.js') }}"></script>
		<script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
		

			

		<!-- Page JS Code -->

		<script src="{{ asset('js/pages/be_pages_dashboard.min.js') }}"></script>
		<script src="{{ asset('js/pages/be_tables_datatables.min.js') }}"></script>		

		<script>jQuery(function(){ One.helpers(['datepicker', 'colorpicker', 'maxlength', 'masked-inputs', 'rangeslider']); });</script>
		<script>		
		function hidekan(){
			$("#loading-status").hide();
		}
    $("input[data-type='currency']").on({
        keyup: function() {
          formatCurrency($(this));
        },
        blur: function() { 
          formatCurrency($(this), "blur");
        }
    });


    function formatNumber(n) {      
      return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }


    function formatCurrency(input, blur) {      
      var input_val = input.val();      
      if (input_val === "") { return; }      
      var original_len = input_val.length;
      var caret_pos = input.prop("selectionStart");        
      if (input_val.indexOf(".") >= 0) {
        var decimal_pos = input_val.indexOf(".");
        var left_side = input_val.substring(0, decimal_pos);
        var right_side = input_val.substring(decimal_pos);
        left_side = formatNumber(left_side);
        right_side = formatNumber(right_side);        
        if (blur === "blur") {
          right_side += "00";
        }        
        right_side = right_side.substring(0, 2);
        input_val = left_side + "." + right_side;
      } else {        
        input_val = formatNumber(input_val);
        input_val = input_val;        
      }      
      input.val(input_val);
      var updated_len = input_val.length;
      caret_pos = updated_len - original_len + caret_pos;
      input[0].setSelectionRange(caret_pos, caret_pos);
    }
    </script>
	</body>
</html>
