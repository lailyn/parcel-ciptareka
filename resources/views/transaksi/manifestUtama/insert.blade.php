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
					<?php if($set!="generate"){ $rt = ""; ?>
						<form class="mb-5" action="{{ route('manifestUtama.generate') }}" enctype="multipart/form-data" method="POST">					
					<?php }else{ $rt = "readonly"; ?>
						<form class="mb-5" action="{{ route('manifestUtama.create') }}" enctype="multipart/form-data" method="POST">					
					<?php } ?>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">														
							<label class="col-sm-2 col-form-label text-right">No AWB</label>
							<div class="col-sm-3">
								<input type="text" readonly class="form-control" name="no_awb" placeholder="Auto">
							</div>								
							<label class="col-sm-1 text-right col-form-label" >Tgl</label>
							<div class="col-sm-2">
								<input type="date" readonly class="form-control" name="tgl_manifest" value="{{ $tgl_manifest }}" placeholder="Tanggal Manifest">
							</div>																																																																		
						</div>
						<div class="form-group row">
							<label class="col-sm-2 text-right col-form-label" >Port Asal</label>
							<div class="col-sm-4">		                   	              
                <select class="form-control select2" id="port_asal" name="port_asal" required>
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
                <select class="form-control select2" id="port_tujuan" name="port_tujuan" required>
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
								<select class="form-control" id="kendaraan_id" name="kendaraan_id" required>
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
								<select class="form-control" id="karyawan_id" name="karyawan_id" required>
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
								<input type="text" value="{{ $keterangan }}" name="keterangan" class="form-control" placeholder="Keterangan Lain-lain">
							</div>
						</div>
						<div class="form-group row">						
							<?php if($set!="generate"){ ?>
							<div class="col-sm-2"></div>
							<div class="col-sm-4">
								<button type="submit" name="submit" value="generate" class="btn btn-danger btn-sm"><i class="fa fa-filter"></i> Filter</button>
								<a href="{{ route('manifestUtama.insert') }}" class="btn btn-secondary btn-sm"> Reset</a>
							</div>
							<?php } ?>
						</div>					
				</div>
				<?php if($set=="generate"){ ?>
				<hr>
				<div class="block-content block-content-full">
					<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
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
						@foreach ($manifest as $key => $row)
							<?php 
							$jum = count($manifest);
							$no = $key+1;
							$status = "<span class='badge badge-info'>$row->status</span>";
							$konstanta = get_setting("konstanta");
							$cekTotal = DB::table("manifestLintas_detail")->where("code",$row->code)->count();							
							$cariTujuan = DB::table("cabang")->where("id",$row->port_tujuan);								
							$tujuan = ($cariTujuan->count())?$cariTujuan->first()->name:'';
							$cariAsal = DB::table("cabang")->where("id",$row->port_asal);								
							$asal = ($cariAsal->count())?$cariAsal->first()->name:'';
							?>
							<tr>
								<th class="text-center">{{ $key+1 }}</th>								
								<td>{{ $row->code }}</td>
								<td>{{ $asal }}</td>
								<td>{{ $tujuan }}</td>
								<td>{{ $cekTotal }} paket</td>								
								<td class="text-center">
									<div class="custom-control custom-checkbox custom-control-danger custom-control-lg mb-1">																			
										<input type="hidden" name="jum" value="<?=$jum;?>">											
										<input type="hidden" name="manifestLintas_id_<?=$no;?>" value="<?=$row->id;?>">											
                    <input type="checkbox" class="custom-control-input" id="pick_<?=$no?>" name="pick_<?=$no?>" value="1">
                    <label class="custom-control-label" for="pick_<?=$no?>">Check</label>
                  </div>
								</td>
							</tr>
						@endforeach													
						</tbody>
					</table>
					<div class="form-group row">											
						<div class="col-sm-2">
							<button type="submit" onclick="return confirm('Anda yakin ingin menyimpan semua data?')" name="submit" value="save" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Save All</button>						
						</div>
					</div>					
				</div>					
				<?php } ?>		
				</form>
			</div>
		</div>
	</main>

@endsection

