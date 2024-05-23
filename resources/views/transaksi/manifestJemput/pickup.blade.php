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
						<a class="btn btn-warning btn-sm float-right" href="{{ route('manifestJemput.list') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									
					<?php $row = $manifest; ?>					
					<form class="mb-5" action="{{ route('manifestJemput.updatePickup',$row->id) }}" enctype="multipart/form-data" method="POST">										
						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">														
							<label class="col-sm-2 col-form-label text-right">No Manifest</label>
							<div class="col-sm-2">
								<input type="text" readonly value="<?=$row->code?>" class="form-control" name="no_manifest" placeholder="Auto">
							</div>								
							<label class="col-sm-1 text-right col-form-label" >Tgl</label>
							<div class="col-sm-2">
								<input type="date" readonly class="form-control" name="tgl_manifest" value="{{ $tgl_manifest }}" placeholder="Tanggal Manifest">
							</div>																										
							<label class="col-sm-1 col-form-label text-right">Outlet</label>
							<div class="col-sm-4">
								<select disabled class="form-control" id="outlet_id" name="outlet_id" required>
									<option value="">- choose -</option>
									@foreach ($outlet as $key => $dt)										
										@php
										if($outlet_id!='' && $outlet_id==$dt->id) $sel = 'selected';
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
					<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
					<table class="table table-responsive table-bordered table-striped table-vcenter">
						<thead>
							<tr>
								<th class="text-center" style="width: 5%;">No</th>																
								<th>STT Number</th>
								<th>Pengirim</th>
								<th>Penerima</th>															
								<th>Jenis Ongkir</th>								
								<th>Paket</th>								
								<th>Jml</th>								
								<th>Dimensi</th>																
								<th>#</th>
							</tr>
						</thead>
						<tbody>
						@foreach ($manifestDetail as $key => $row)
							<?php 
							$jum = count($manifestDetail);
							$no = $key+1;
							$status = "<span class='badge badge-info'>$row->sta</span>";
							$konstanta = get_setting("konstanta");
							?>
							<tr>
								<th class="text-center">{{ $key+1 }}</th>
								<td>									
									<strong>{{ $row->stt_number }}</strong>
								</td>
								<td>{{ $row->nama_pengirim }}</td>
								<td>{{ $row->nama_penerima }}</td>
								<td>{{ $row->jenis_tarif }}</td>
								<td>{{ $row->isi_paket }}</td>
								<td>{{ $row->jumlah_paket }}</td>
								<td>{{ $row->berat }} gr / {{ floor(@($row->panjang * $row->lebar * $row->tinggi) / $konstanta) }} m<sup>3</sup></td>								
								<td>
									<div class="custom-control custom-checkbox custom-control-danger custom-control-lg mb-1">																			
										<input type="hidden" name="jum" value="<?=$jum;?>">											
										<input type="hidden" name="manifestJemput_detail_id_<?=$no;?>" value="<?=$row->ids;?>">											
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
					
				</form>
			</div>
		</div>
	</main>

@endsection

