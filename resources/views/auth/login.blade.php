<!doctype html>
<html lang="en">
	<head>    

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

		<title>{{ get_setting('app') }}</title>

		<meta name="description" content="OneUI - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
		<meta name="author" content="pixelcave">
		<meta name="robots" content="noindex, nofollow">

		<!-- Open Graph Meta -->
		<meta property="og:title" content="OneUI - Bootstrap 4 Admin Template &amp; UI Framework">
		<meta property="og:site_name" content="OneUI">
		<meta property="og:description" content="OneUI - Bootstrap 4 Admin Template &amp; UI Framework created by pixelcave and published on Themeforest">
		<meta property="og:type" content="website">
		<meta property="og:url" content="">
		<meta property="og:image" content="">

		<!-- Icons -->
		<!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
		<link rel="shortcut icon" href="{{ get_setting('favicon') }}">
		<link rel="icon" type="image/png" sizes="192x192" href="{{ get_setting('favicon') }}">
		<link rel="apple-touch-icon" sizes="180x180" href="{{ get_setting('favicon') }}">
		<!-- END Icons -->

		<!-- Stylesheets -->
		<!-- Fonts and OneUI framework -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">
		<link rel="stylesheet" id="css-main" href="{{ asset('css/oneui.min.css') }}">
		<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

		<!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
		<!-- <link rel="stylesheet" id="css-theme" href="{{ asset('css/themes/amethyst.min.css') }}"> -->
		<!-- END Stylesheets -->
	</head>
	<body>
		<!-- Page Container -->
		<!--
			Available classes for #page-container:

		GENERIC

			'enable-cookies'                            Remembers active color theme between pages (when set through color theme helper Template._uiHandleTheme())

		SIDEBAR & SIDE OVERLAY

			'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
			'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
			'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
			'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
			'sidebar-dark'                              Dark themed sidebar

			'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
			'side-overlay-o'                            Visible Side Overlay by default

			'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

			'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

		HEADER

			''                                          Static Header if no class is added
			'page-header-fixed'                         Fixed Header

		HEADER STYLE

			''                                          Light themed Header
			'page-header-dark'                          Dark themed Header

		MAIN CONTENT LAYOUT

			''                                          Full width Main Content if no class is added
			'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
			'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)
		-->
		<div id="page-container">

			<!-- Main Container -->
			<main id="main-container">

				<!-- Page Content -->
				<div class="bg-image" style="background-image: url('media/photos/photo6@2x.jpg');">
					<div class="hero-static bg-white-95">
						<div class="content">
							<div class="row justify-content-center">
								<div class="col-md-8 col-lg-6 col-xl-4">
									<!-- Sign In Block -->
									<div class="block block-themed block-fx-shadow mb-0">
										<div class="block-header bg-warning">
											<h3 class="block-title">Sign In</h3>											
										</div>
										<div class="block-content">
											<div class="p-sm-3 px-lg-4 py-lg-5">
												<h1 class="mb-2">{{ get_setting('app') }}</h1>
												<p>Welcome, please login.</p>

												<!-- Sign In Form -->
												<!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.min.js which was auto compiled from _es6/pages/op_auth_signin.js) -->
												<!-- For more info and examples you can check out https://github.com/jzaefferer/jquery-validation -->
												<form class="js-validation-signin" action="{{ route('login.action') }}" method="POST">													
                          @csrf
                          @method('POST')
													<div class="py-3">
														@if(session('success'))
										        	<p class="alert alert-success">{{ session('success') }}</p>
										        @endif
										        @if($errors->any())
										        @foreach($errors->all() as $err)
										        	<p class="alert alert-danger">{{ $err }}</p>
										        @endforeach
										        @endif
										        
														<div class="form-group">
															<input type="text" class="form-control form-control-alt form-control-lg" id="login-username" name="username" placeholder="Username">
														</div>
														<div class="form-group">
															<input type="password" class="form-control form-control-alt form-control-lg" id="login-password" name="password" placeholder="Password">
														</div>									
														<div class="form-group">
															<div class="captcha">
			                          <span>{!! captcha_img() !!}</span>
			                          <!-- <button type="button" class="btn btn-success btn-refresh"><i class="fa fa-history"></i></button> -->
			                         	</div>
			                          <input id="captcha" type="number" class="form-control form-control-alt form-control-lg" placeholder="Captcha Codes" name="captcha">


			                          @if ($errors->has('captcha'))
			                              <span class="help-block">
			                                  <strong>{{ $errors->first('captcha') }}</strong>
			                              </span>
			                          @endif
														</div>					
														<div class="form-group">			                  
															<button type="submit" class="btn btn-block btn-success">
																<i class="fa fa-fw fa-sign-in-alt mr-1"></i> Enter Me
															</button>
														</div>
													</div>
												</form>
												<!-- END Sign In Form -->
											</div>
										</div>
									</div>
									<!-- END Sign In Block -->
								</div>
							</div>
						</div>
						<div class="content content-full font-size-sm text-muted text-center">
							<strong>{{ get_setting('app') }}</strong> &copy; <span data-toggle="year-copy">2022</span>
						</div>
					</div>
				</div>
				<!-- END Page Content -->

			</main>
			<!-- END Main Container -->
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
		<script src="{{ asset('js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>

		<!-- Page JS Code -->
		<script src="{{ asset('js/pages/op_auth_signin.min.js') }}"></script>

		<script type="text/javascript">


		$(".btn-refresh").click(function(){
		  $.ajax({
		     type:'GET',
		     url:'/refresh_captcha',
		     success:function(data){
		        $(".captcha span").html(data.captcha);
		     }
		  });
		});


		</script>
	</body>
</html>
