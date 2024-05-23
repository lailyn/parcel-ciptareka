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
						<!-- <a class="btn btn-danger btn-sm float-right" href="{{ route('pengiriman.insert') }}"> <i class="fa fa-plus"></i> Add Item</a> -->
					</h3>
				</div>				
				<div class="block-content block-content-full">													
					<form class="mb-5" action="{{ route('warehouse.generate') }}" enctype="multipart/form-data" method="POST">									
					<input type="hidden" name="_token" value="{{ csrf_token() }}">						
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">No Plat Kendaraan</label>
						<div class="col-sm-2">
							<input type="text" value="{{ $no_plat }}" class="form-control" name="no_plat" required placeholder="Masukkan No Plat">
						</div>						
						<div class="col-sm-1">
							<button class="btn btn-warning" type="submit"><i class="fa fa-search"></i> Cari</button>
						</div>				
					</div>
				</div>
				<?php if($set=="generate"){ ?>
				<hr>
				<div class="block-content block-content-full">
					<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
					<table class="table table-bordered table-responsive table-striped table-vcenter js-dataTable-buttons">
						<thead>
							<tr>
								<th class="text-center" style="width: 5%;">No</th>								
								<th>No Manifest</th>
								<th>Total Paket</th>
								<th>Port Asal</th>
								<th>Port Tujuan</th>
								<th>Estimasi Durasi</th>								
								<th>Kendaraan</th>								
								<th>Supir</th>								
								<th>Tgl Manifest</th>																								
								<th>Status</th>																																
								<th>#</th>																								
							</tr>
						</thead>
						<tbody>
						@foreach ($manifest as $key => $row)
							<?php 
							$status = "<span class='badge badge-info'>$row->status</span>";
							$port_asal = DB::table("cabang")->where("id",$row->port_asal)->first()->name;
							$port_tujuan = DB::table("cabang")->where("id",$row->port_tujuan)->first()->name;
							?>
							<tr>
								<th class="text-center">{{ $key+1 }}</th>
								<td>{{ $row->code }}</td>
								<td>{{ $row->total_paket }} item</td>
								<td>{{ $port_asal }}</td>
								<td>{{ $port_tujuan }}</td>
								<td>{{ $row->estimasi }} hari</td>
								<td>{{ $row->kendaraan }} // {{ $row->no_plat }}</td>
								<td>{{ $row->karyawan }}</td>								
								<td>{{ $row->tgl_manifest }}</td>								
								<td>{!! $status !!}</td>
								<td>
									<a href="{{ route('warehouse.proses',$row->id) }}" class="btn btn-sm btn-warning">Proses</a>
								</td>
							</tr>
						@endforeach	
							
	
						</tbody>
					</table>

				</div>	

				<?php } ?>		
			</div>
	</main>
@endsection


{{-- <script src="{{ asset('js/pages/be_tables_datatables.min.js') }}"></script> --}}
