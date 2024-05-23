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
						<a class="btn btn-danger btn-sm float-right" href="{{ route('delivery.insert') }}"> <i class="fa fa-plus"></i> Add Delivery</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									
					<div class="form-group row">
						<label class="col-sm-2 col-form-label" >No Surat Jalan</label>
						<div class="col-sm-3">
							<input type="text" autocomplete="off" class="form-control" name="no_surat" placeholder="No. Surat Jalan">
						</div>																	
						<label class="col-sm-2 col-form-label" >Tgl Surat Jalan</label>
						<div class="col-sm-2">
							<input type="date" class="form-control" name="tgl_surat" placeholder="Tanggal Surat Jalan">
						</div>												
					</div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label" >Nama Kurir</label>
						<div class="col-sm-3">
							<select class="form-control" id="karyawan_id" onchange="cekCakupan()" name="karyawan_id" required>
								<option value="">- choose -</option>								
								@foreach ($karyawan as $key => $dt)																												
									<option value="{{ $dt->id }}">{{ $dt->name }} </option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-7">
							<button type="submit" class="btn btn-primary">Filter</button>
							<button type="reset" class="btn btn-warning">Reset</button>
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
								<th>Isi Paket</th>																
								<th>Jml</th>																
								<th>Kecamatan</th>								
								<th>Kurir</th>																																
								<th>Keterangan</th>													
							</tr>
						</thead>
						<tbody>							
						@foreach ($delivery as $key => $row)
							<?php 							
							$no = $key+1;
							$status = "<span class='badge badge-info'>$row->sta</span>";
							$kecamatan = cariDaerah_helper($row->id_kelurahan_penerima,'kec');                                                                            
							?>							
							<tr>
								<th class="text-center">{{ $key+1 }}</th>
								<td>{{ $row->code }} <br> {!! $status !!}</td>
								<td>{{ $row->awb_number }}</td>
								<td>{{ $row->nama_pengirim }}</td>
								<td>{{ $row->nama_penerima }}</td>						
								<td>{{ $row->isi_paket }}</td>		
								<td>{{ $row->jumlah_paket }}</td>										
								<td>{{ $kecamatan }}</td>																
								<td>{{ $row->kurir }}</td>																
								<td>
									@php if(!is_null($row->diterima_at)){ @endphp
										<span class='badge badge-success'>Diterima Oleh:</span> {{ $row->penerima }} // {{ $row->diterima_at }} <br>
									@php } @endphp									
									@php if(!is_null($row->ditunda_at)){ @endphp
										<span class='badge badge-danger'>Ditunda Karena:</span> {{ $row->alasan }} // {{ $row->ditunda_at }} <br>
									@php } @endphp									
								</td>																																														
							</tr>
						@endforeach	
						</tbody>
					</table>
				</div>			
			</div>
	</main>
@endsection

