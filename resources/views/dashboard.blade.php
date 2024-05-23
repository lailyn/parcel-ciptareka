@extends('layout.template_dash')
@section('content')
	<main id="main-container">

				<!-- Hero -->
				<div class="bg-image overflow-hidden" style="background-image: url('media/photos/photo3@2x.jpg');">
					<div class="bg-primary-dark-op">
						<div class="content content-narrow content-full">
							<div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center mt-5 mb-2 text-center text-sm-left">
								<div class="flex-sm-fill">
									<h1 class="font-w600 text-white mb-0 invisible" data-toggle="appear">Dashboard {{ $subtitle }}</h1>
									<h2 class="h4 font-w400 text-white-75 mb-0 invisible" data-toggle="appear" data-timeout="250">Welcome Administrator</h2>
								</div>
								<div class="flex-sm-00-auto mt-3 mt-sm-0 ml-sm-3">
									<span class="d-inline-block invisible" data-toggle="appear" data-timeout="350">
										<a class="btn btn-primary px-4 py-2" data-toggle="click-ripple" href="{{ route('pengiriman.stt-create') }}">
											<i class="fa fa-plus mr-1"></i> New Transaction
										</a>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- END Hero -->

				<!-- Page Content -->
				<div class="content content-narrow">
					<!-- Stats -->
					<div class="row">
						<div class="col-6 col-md-3 col-lg-6 col-xl-3">
							<a class="block block-rounded block-link-pop border-left border-primary border-4x" href="javascript:void(0)">
								<div class="block-content block-content-full">
									<div class="font-size-sm font-w600 text-uppercase text-muted">Transactions</div>
									<div class="font-size-h2 font-w400 text-dark">{{ mata_uang_help(120580) }}</div>
								</div>
							</a>
						</div>
						<div class="col-6 col-md-3 col-lg-6 col-xl-3">
							<a class="block block-rounded block-link-pop border-left border-primary border-4x" href="javascript:void(0)">
								<div class="block-content block-content-full">
									<div class="font-size-sm font-w600 text-uppercase text-muted">Outlets</div>
									<div class="font-size-h2 font-w400 text-dark">150</div>
								</div>
							</a>
						</div>
						<div class="col-6 col-md-3 col-lg-6 col-xl-3">
							<a class="block block-rounded block-link-pop border-left border-primary border-4x" href="javascript:void(0)">
								<div class="block-content block-content-full">
									<div class="font-size-sm font-w600 text-uppercase text-muted">Pendapatan</div>
									<div class="font-size-h2 font-w400 text-dark">{{ mata_uang_help(3200029) }}</div>
								</div>
							</a>
						</div>
						<div class="col-6 col-md-3 col-lg-6 col-xl-3">
							<a class="block block-rounded block-link-pop border-left border-primary border-4x" href="javascript:void(0)">
								<div class="block-content block-content-full">
									<div class="font-size-sm font-w600 text-uppercase text-muted">Customers</div>
									<div class="font-size-h2 font-w400 text-dark">21.902</div>
								</div>
							</a>
						</div>
					</div>
					<!-- END Stats -->

					<!-- Dashboard Charts -->
					<div class="row">
						<div class="col-lg-12">
							<div class="block block-rounded block-mode-loading-oneui">
								<div class="block-header">
									<h3 class="block-title">Pendapatan in Rp</h3>
									<div class="block-options">
										<button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
											<i class="si si-refresh"></i>
										</button>
										<button type="button" class="btn-block-option">
											<i class="si si-settings"></i>
										</button>
									</div>
								</div>
								<div class="block-content p-0 bg-body-light text-center">
									<!-- Chart.js is initialized in js/pages/be_pages_dashboard.min.js which was auto compiled from _es6/pages/be_pages_dashboard.js) -->
									<!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
									<div class="pt-3" style="height: 360px;"><canvas class="js-chartjs-dashboard-earnings"></canvas></div>
								</div>
								<div class="block-content">
									<div class="row items-push text-center py-3">
										<div class="col-6 col-xl-3">
											<i class="fa fa-wallet fa-2x text-muted"></i>
											<div class="text-muted mt-3">{{ mata_uang_help(148000) }}</div>
										</div>
										<div class="col-6 col-xl-3">
											<i class="fa fa-angle-double-up fa-2x text-muted"></i>
											<div class="text-muted mt-3">+9% Earnings</div>
										</div>
										<div class="col-6 col-xl-3">
											<i class="fa fa-ticket-alt fa-2x text-muted"></i>
											<div class="text-muted mt-3">+20% Transactions</div>
										</div>
										<div class="col-6 col-xl-3">
											<i class="fa fa-users fa-2x text-muted"></i>
											<div class="text-muted mt-3">+46% Customers</div>
										</div>
									</div>
								</div>
							</div>
						</div>						
					</div>
					
				</div>
				<!-- END Page Content -->

			</main>
@endsection           
