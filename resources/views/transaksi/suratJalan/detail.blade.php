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
		<div class="content">
			<?php if (session()->has('msg')) { ?>
				{!! session()->get('msg') !!}                          
			<?php session()->forget('msg'); } ?>			
			<div class="block">
				<div class="block-header block-header-default">
					<h3 class="block-title">
						<a class="btn btn-warning btn-sm float-right" href="{{ route('suratJalan.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									
					<div class="form-group row">
						<label class="col-sm-2 col-form-label" >No Surat Jalan</label>
						<div class="col-sm-2">
							<input type="text" class="form-control" name="name" placeholder="No. Surat Jalan">
						</div>				
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label" >Tanggal Surat Jalan</label>
						<div class="col-sm-2">
							<input type="text" class="form-control" name="name" placeholder="Tanggal Surat Jalan">
						</div>				
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label" >Nama Kurir</label>
						<div class="col-sm-2">
							<input type="text" class="form-control" name="name" placeholder="Nama Kurir">
						</div>							
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label" >Kendaraan</label>
						<div class="col-sm-2">
							<input type="text" class="form-control" name="name" placeholder="Kendaraan">
						</div>							
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label" >Cakupan Wilayah</label>
						<div class="col-sm-2">
							<input type="text" class="form-control" name="name" placeholder="Cakupan Wilayah">
						</div>							
					</div>
				</div>
				<div class="block-content block-content-full">
					<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
					<table class="table table-responsive table-bordered table-striped table-vcenter js-dataTable-buttons">
						<thead>
							<tr>
								<th class="text-center" style="width: 5%;">No</th>								
								<th>No Manifest</th>
								<th>No AWB</th>
								<th>Pengirim</th>
								<th>Penerima</th>
								<th>Jml Paket</th>								
								<th>Ongkir</th>								
								<th>Kecamatan</th>								
								<th>Kurir</th>								
								<th>Kendaraan</th>								
								<th>Status</th>
								<th>Keterangan</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>		
			</div>
	</main>
@endsection


{{-- <script src="{{ asset('js/pages/be_tables_datatables.min.js') }}"></script> --}}