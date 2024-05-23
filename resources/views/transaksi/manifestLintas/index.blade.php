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
						<a class="btn btn-danger btn-sm float-right" href="{{ route('manifestLintas.insert') }}"> <i class="fa fa-plus"></i> Buat Manifest Baru</a>
					</h3>
				</div>				
				<div class="block-content block-content-full">
					<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
					<table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
						<thead>
							<tr>
								<th class="text-center" style="width: 5%;">No</th>								
								<th>No Manifest Lintas</th>
								<th>Total Paket</th>
								<th>Port Asal</th>																
								<th>Port Tujuan</th>																
								<th>Kendaraan</th>								
								<th>Supir</th>								
								<th>Status</th>																								
								<th>#</th>																								
							</tr>
						</thead>
						<tbody>
						@foreach ($manifest as $key => $row)
							<?php 
							if($row->status=='baru'){															
								$status = "<span class='badge badge-info'>$row->status</span>";							
							}else{
								$status = "<span class='badge badge-success'>$row->status</span>";							
							}
							$ers = "d-none";$er = "";
							if($row->status=='locked'){
								$er = "d-none";
								$ers = "";
							}

							$cariPortAsal = DB::table("cabang")->where("id",$row->port_asal)->first()->name;
							$cariPortTujuan = DB::table("cabang")->where("id",$row->port_tujuan)->first()->name;
							$count = DB::table("manifestLintas_detail")->where("code",$row->code)->count();
							?>
							<tr>
								<th class="text-center">{{ $key+1 }}</th>
								<td>{{ $row->code }}</td>
								<td>{{ $count }} item</td>
								<td>{{ $cariPortAsal }}</td>								
								<td>{{ $cariPortTujuan }}</td>								
								<td>{{ $row->kendaraan }} // {{ $row->no_plat }}</td>
								<td>{{ $row->karyawan }}</td>								
								<td>{!! $status !!}</td>
								<td>
								  <div class="dropdown">
										<button class="btn btn-circle btn-sm btn-warning" type="button"
											id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
											aria-expanded="false"> Action <i class="fas fa-chevron-down"></i>
										</button>
										<div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">											
											<a class="dropdown-item" href="{{ route('manifestLintas.cetak', $row->id) }}">Cetak Manifest</a> 																					
											<a class="dropdown-item" href="{{ route('manifestLintas.detail', $row->id) }}">Detail</a> 																					
											<a class="dropdown-item {{ $ers }}" href="{{ route('manifestLintas.pickup', $row->id) }}">Ceklis Pickup</a> 																					
											<a class="dropdown-item {{ $ers }}" href="{{ route('manifestLintas.penerimaan', $row->id) }}">Ceklis Penerimaan</a> 																					
											<a class="dropdown-item {{ $er }}" href="{{ route('manifestLintas.edit', $row->id) }}">Edit</a> 																					
											<a class="dropdown-item {{ $er }}" onclick="return confirm('Are you sure...?')" href="{{ route('manifestLintas.lock', $row->id) }}">Lock</a> 																					
										</div>
									</div>
								</td>
							</tr>
						@endforeach	
							
	
						</tbody>
					</table>
				</div>			
			</div>
	</main>
@endsection


{{-- <script src="{{ asset('js/pages/be_tables_datatables.min.js') }}"></script> --}}
