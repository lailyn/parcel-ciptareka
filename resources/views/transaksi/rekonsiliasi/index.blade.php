@extends('layout.template_dash')
@section('content')
	<main id="main-container">
		<div class="bg-body-light">
			<div class="content content-full">
				<div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
					<h1 class="flex-sm-fill h3 my-2">
						{{ $title }} 
						<small class="d-block d-sm-inline-block mt-2 mt-sm-0 font-size-base font-w400 text-muted">{{ $subtitle }}</small>
					</h1>
				</div>
			</div>
		</div>
		<!-- END Hero -->
		<?php if (session()->has('msg')) { ?>
		  {!! session()->get('msg') !!}                          
		<?php session()->forget('msg'); } ?>			      
		<div class="content">			

		<!-- Page Content -->
			<div class="block">
				<div class="block-header block-header-default">
					<h3 class="block-title">
						<a class="btn btn-danger btn-sm float-right" href="{{ route('rekonsiliasi.insert') }}"> <i class="fa fa-plus"></i> Generate Rekon Member</a>
						<a class="btn btn-warning btn-sm float-right mr-2" href="{{ route('rekonsiliasi.insert') }}"> <i class="fa fa-plus"></i> Generate Rekon Partnership</a>
					</h3>
				</div>
				<div class="block-content block-content-full">					
					<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
					<table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
						<thead>
							<tr>
								<th class="text-center" style="width: 80px;">ID</th>								
								<th>Kode</th>																
								<th>Periode</th>								
								<th>Tgl.Mulai</th>
								<th>Tgl.Selesai</th>
								<th>Status</th>																
								<th>Keterangan</th>																																
								<th width="5%">#</th>																																
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>			
			</div>
	</main>
@endsection

