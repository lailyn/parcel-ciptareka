@extends('layout.template_dash')
@section('content')
<body onload="cekCakupan()">

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
						<a class="btn btn-warning btn-sm float-right" href="{{ route('manifestAntar.insert') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									
					<?php if($set!="generate"){ $rt = ""; ?>
						<form class="mb-5" action="{{ route('manifestAntar.generate') }}" enctype="multipart/form-data" method="POST">					
					<?php }else{ $rt = "readonly"; ?>
						<form class="mb-5" action="{{ route('manifestAntar.create') }}" enctype="multipart/form-data" method="POST">					
					<?php } ?>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">														
							<label class="col-sm-2 col-form-label text-right">No Manifest</label>
							<div class="col-sm-3">
								<input type="text" readonly class="form-control" name="no_manifest" placeholder="Auto">
							</div>								
							<label class="col-sm-1 text-right col-form-label" >Tgl</label>
							<div class="col-sm-2">
								<input type="date" readonly class="form-control" name="tgl_manifest" value="{{ $tgl_manifest }}" placeholder="Tanggal Manifest">
							</div>																																																																								
						</div>
						<div class="form-group row">																														
							<label class="col-sm-2 col-form-label text-right">Kurir</label>
							<div class="col-sm-2">
								<select class="form-control" id="karyawan_id" onchange="cekCakupan()" name="karyawan_id" required>
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
							<label class="col-sm-2 col-form-label text-right">Cakupan Wilayah</label>
							<div class="col-sm-6">								
								<input type="text" readonly class="form-control" value="{{ $cakupan }}" id="cakupan" name="cakupan" placeholder="Cakupan Wilayah">
							</div>							
						</div>
						<div class="form-group row">								
							<label class="col-sm-2 text-right col-form-label" >Kendaraan</label>
							<div class="col-sm-3">
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
							<label class="col-sm-1 text-right col-form-label" >Port Penerima</label>
							<div class="col-sm-4">		                   	              
                <select class="form-control select2" id="port_tujuan" name="port_tujuan" required>
									<option value="">- choose -</option>
									@foreach ($cabang as $key => $dt)
										@php
										if($port_tujuan!='' && $dt->id==$port_tujuan) $sel = 'selected';
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
								<a href="{{ route('manifestAntar.insert') }}" class="btn btn-secondary btn-sm"> Reset</a>
							</div>
							<?php } ?>
						</div>					
				</div>
				<?php if($set=="generate"){ ?>
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
							?>
							<tr>
								<th class="text-center">{{ $key+1 }}</th>								
								<td>{{ $row->stt_number }}</td>
								<td>{{ $row->nama_pengirim }}</td>
								<td>{{ $row->nama_penerima }}</td>
								<td>{{ $row->jenis_tarif }}</td>
								<td>{{ $row->isi_paket }}</td>
								<td>{{ $row->jumlah_paket }}</td>
								<td>{{ $row->berat }} gr / {{ floor(@($row->panjang * $row->lebar * $row->tinggi) / $konstanta) }} m<sup>3</sup></td>
								<td class="text-center">
									<div class="custom-control custom-checkbox custom-control-danger custom-control-lg mb-1">																			
										<input type="hidden" name="jum" value="<?=$jum;?>">											
										<input type="hidden" name="pengiriman_id_<?=$no;?>" value="<?=$row->id;?>">											
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

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript">
function cekCakupan(){	
	let karyawan_id = $("#karyawan_id").val(); 				
	$.ajax({
    url : "{{ route('manifestAntar.cekCakupan') }}",
    type:'POST',
    data: {
      _token: '{!! csrf_token() !!}',        
      karyawan_id: karyawan_id,        
    },      
    cache:false,   
    success:function(msg){        	    
      $("#cakupan").val(msg); 	              
    }
  })	
}
</script>
@endsection



