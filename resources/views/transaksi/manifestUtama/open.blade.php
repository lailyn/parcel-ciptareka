@extends('layout.template_dash')
@section('content')
<body>
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
						<a class="btn btn-warning btn-sm float-right" href="{{ route('manifestUtama.insert') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									
					<?php $row = $manifest; ?>					
					<form class="mb-5" action="{{ route('manifestUtama.update',$row->id) }}" enctype="multipart/form-data" method="POST">										
						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">														
							<label class="col-sm-2 col-form-label text-right">No Manifest</label>
							<div class="col-sm-3">
								<input type="text" readonly value="<?=$row->code?>" class="form-control" name="no_manifest" placeholder="Auto">
							</div>								
							<label class="col-sm-1 text-right col-form-label" >Tgl</label>
							<div class="col-sm-2">
								<input type="date" readonly class="form-control" name="tgl_manifest" value="{{ $tgl_manifest }}" placeholder="Tanggal Manifest">
							</div>																																																																				
						</div>						
						<div class="form-group row">
							<label class="col-sm-2 text-right col-form-label" >Port Singgah</label>
							<div class="col-sm-4">		                   	              
                <select disabled class="form-control select2" id="port_singgah" name="port_singgah" required>
									<option value="">- choose -</option>
									@foreach ($cabang as $key => $dt)
										@php
										if($port_singgah!='' && $dt->id==$port_singgah) $sel = 'selected';
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
									@endforeach
								</select>
              </div>
            </div>
						<div class="form-group row">
							<label class="col-sm-2 text-right col-form-label" >Port Asal</label>
							<div class="col-sm-4">		                   	              
                <select disabled class="form-control select2" id="port_asal" name="port_asal" required>
									<option value="">- choose -</option>
									@foreach ($cabang as $key => $dt)
										@php
										if($port_asal!='' && $dt->id==$port_asal) $sel = 'selected';
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
									@endforeach
								</select>
              </div>
              <label class="col-sm-2 text-right col-form-label" >Port Tujuan</label>
              <div class="col-sm-4">		                   	                
                <select disabled class="form-control select2" id="port_tujuan" name="port_tujuan" required>
									<option value="">- choose -</option>
									@foreach ($cabang as $key => $dt)
										@php
										if($port_tujuan && $dt->id==$port_tujuan) $sel = 'selected';
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
									@endforeach
								</select>
              </div>
						</div>
						<div class="form-group row">								
							<label class="col-sm-2 text-right col-form-label" >Kendaraan</label>
							<div class="col-sm-4">
								<select disabled class="form-control" id="kendaraan_id" name="kendaraan_id" required>
									<option value="">- choose -</option>
									@foreach ($kendaraan as $key => $dt)										
										@php
										if($kendaraan_id!='' && $kendaraan_id==$dt->id) $sel = 'selected';
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} // {{ $dt->no_plat }}</option>
									@endforeach
								</select>
							</div>											
							<label class="col-sm-2 text-right col-form-label" >Driver</label>
							<div class="col-sm-4">
								<select disabled class="form-control" id="karyawan_id" name="karyawan_id" required>
									<option value="">- choose -</option>
									@foreach ($karyawan as $key => $dt)										
										@php
										if($karyawan_id!='' && $karyawan_id==$dt->id) $sel = 'selected';
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
									@endforeach
								</select>
							</div>							
						</div>
						<div class="form-group row">								
							<label class="col-sm-2 text-right col-form-label" >Keterangan</label>
							<div class="col-sm-10">
								<input readonly type="text" value="{{ $keterangan }}" name="keterangan" class="form-control" placeholder="Keterangan Lain-lain">
							</div>
						</div>						
				</div>
				
				<hr>
				<div class="block-content block-content-full">
					<div class="form-group row">											
						<div class="col-sm-2">
							<button type="button" data-toggle="modal" data-target="#sttModal" value="save" class="btn btn-danger btn-sm"><i class="fa fa-plus"></i> Add Manifest</button>						
						</div>
					</div>	


					<div class="modal fade" id="sttModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog modal-lg" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLabel">Pilih Manifest</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					        <table class="table table-bordered table-striped table-vcenter">
										<thead>
											<tr>
												<th class="text-center" style="width: 5%;">No</th>																
												<th>No Manifest Lintas</th>
												<th>Port Asal</th>
												<th>Port Tujuan</th>															
												<th>Total Paket</th>																
												<th class="text-center">#</th>
											</tr>
										</thead>
										<tbody>
					        	@foreach ($manifestMentah as $key => $isi)
										<?php 										
										$no = $key+1;										
										$status = "<span class='badge badge-info'>$isi->status</span>";
										$konstanta = get_setting("konstanta");
										$total = DB::table("manifestLintas_detail")->where("code",$isi->code)->count();
										// $total = ($cekTotal->count())?$cekTotal->first()->count():0;

										$cariTujuan = DB::table("cabang")->where("id",$isi->port_tujuan);								
										$tujuan = ($cariTujuan->count())?$cariTujuan->first()->name:'';
										$cariAsal = DB::table("cabang")->where("id",$isi->port_asal);								
										$asal = ($cariAsal->count())?$cariAsal->first()->name:'';
										?>
										<tr>
											<th class="text-center">{{ $key+1 }}</th>								
											<td>{{ $isi->code }}</td>
											<td>{{ $asal }}</td>
											<td>{{ $tujuan }}</td>
											<td>{{ $total }} paket</td>
											<td class="text-center">
												<a href="{{ route('manifestUtama.addManifest',[$isi->id,$row->code,$port_singgah]) }}" class="btn btn-success btn-sm">
													pilih
												</a>
											</td>
										</tr>
									@endforeach
					        	</tbody>
					        </table>
					      </div>					      
					    </div>
					  </div>
					</div>				
					<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
					<table class="table table-bordered table-striped table-vcenter">
						<thead>
							<tr>
								<th class="text-center" style="width: 5%;">No</th>																
								<th>No Manifest Lintas</th>
								<th>Port Asal</th>
								<th>Port Tujuan</th>															
								<th>Total Paket</th>																
								<th class="text-center">Status</th>							
								<th class="text-center">#</th>
							</tr>
						</thead>
						<tbody>
						@foreach ($manifestDetail as $key => $dt)
							<?php 
							$cariTujuan = DB::table("cabang")->where("id",$dt->port_tujuan);								
							$tujuan = ($cariTujuan->count())?$cariTujuan->first()->name:'';
							$cariAsal = DB::table("cabang")->where("id",$dt->port_asal);								
							$asal = ($cariAsal->count())?$cariAsal->first()->name:'';
							$cekTotal = DB::table("manifestLintas_detail")->where("code",$dt->manifest_code)->count();														
							if($dt->status_manifest=="dropped"){
								$er = "d-none";
								$status = "<span class='badge badge-info'>$dt->status_manifest at $dt->warehouse_at</span>";
							}else{
								$er = "";
								$status = "<span class='badge badge-info'>$dt->status_manifest</span>";
							}
							?>
							<tr>
								<th class="text-center">{{ $key+1 }}</th>								
								<td>{{ $dt->manifest_code }}</td>
								<td>{{ $asal }}</td>
								<td>{{ $tujuan }}</td>
								<td>{{ $cekTotal }} paket</td>																
								<td>{!! $status !!}</td>
								<td class="text-center">
									<a href="{{ route('manifestUtama.dropManifest', [$dt->ids,$port_singgah]) }}" class="btn btn-success btn-sm {{ $er }}" onclick="return confirm('Are you sure...?')">Drop Manifest</a>
								</td>
							</tr>
						@endforeach													
						</tbody>
					</table>					
				</div>					
					
				</form>
			</div>
		</div>
	</main>

@endsection

