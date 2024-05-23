@extends('layout.template_dash')
@section('content')
<style type="text/css">
.pointer {cursor: pointer;}
</style>
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
						<a class="btn btn-danger btn-sm float-right" href="{{ route('manifestUtama.insert') }}"> <i class="fa fa-plus"></i> Buat Manifest Baru</a>
					</h3>
				</div>				
				<div class="block-content block-content-full">
					<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
					<table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
						<thead>
							<tr>
								<th class="text-center" style="width: 5%;">No</th>								
								<th>No AWB</th>
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
							$ers = "d-none";$er = "";$open = "d-none";
							if($row->status=='locked'){
								$er = "d-none";
								$ers = "";
								$open = "";
							}

							$cariPortAsal = DB::table("cabang")->where("id",$row->port_asal)->first()->name;
							$cariPortTujuan = DB::table("cabang")->where("id",$row->port_tujuan)->first()->name;
							$count = DB::table("manifestUtama_detail")->where("code",$row->code)->count();
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
											<a class="dropdown-item" href="{{ route('manifestUtama.cetak', $row->id) }}">Cetak Manifest</a> 																					
											<a class="dropdown-item" href="{{ route('manifestUtama.detail', $row->id) }}">Detail</a> 																										
											<a class="dropdown-item {{ $er }}" href="{{ route('manifestUtama.edit', $row->id) }}">Edit</a> 																																			
											<a class="dropdown-item {{ $ers }}" href="{{ route('manifestUtama.riwayat', $row->id) }}">Cek Riwayat</a> 																			
											<a class="dropdown-item pointer {{ $open }}" data-toggle="modal" data-target="#openModal">Open & Edit</a>
											<a class="dropdown-item {{ $er }}" onclick="return confirm('Are you sure...?')" href="{{ route('manifestUtama.lock', $row->id) }}">Lock</a> 																					
										</div>
									</div>

									<div class="modal fade" id="openModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									  <div class="modal-dialog modal-lg" role="document">
									    <div class="modal-content">
									      <div class="modal-header">
									        <h5 class="modal-title" id="exampleModalLabel">Pilih Port Singgah</h5>
									        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
									          <span aria-hidden="true">&times;</span>
									        </button>
									      </div>
									      <form class="mb-5" action="{{ route('manifestUtama.open',$row->id) }}" enctype="multipart/form-data" method="get">										
										      <div class="modal-body">										        															
							                <select class="form-control select2" id="port_singgah" name="port_singgah" required>
																<option value="">- choose -</option>
																@foreach ($cabang as $key => $dt)																		
																	<option value="{{ $dt->id }}">{{ $dt->name }} </option>
																@endforeach
															</select>								            								           									        
										      </div>
										      <div class="modal-body">
										      	<button type="submit" class="btn btn-primary">Lanjutkan</button>
										      </div>					      
									      </form>
									    </div>
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
