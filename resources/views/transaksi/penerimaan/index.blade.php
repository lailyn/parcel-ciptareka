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
						<a class="btn btn-warning btn-sm float-right ml-2" href="{{ route('penerimaan.index') }}"> <i class="fa fa-history"></i> History</a>
						<a class="btn btn-danger btn-sm float-right" href="{{ route('penerimaan.insert') }}"> <i class="fa fa-plus"></i> Penerimaan Baru</a>
					</h3>
				</div>				
				<div class="block-content block-content-full">					
					<table class="table table-responsive table-bordered table-striped table-vcenter js-dataTable-buttons">
						<thead>
							<tr>
								<th class="text-center" style="width: 5%;">No</th>								
								<th>No Transaksi</th>
								<th>No AWB</th>
								<th>Pengirim</th>
								<th>Penerima</th>															
								<th>Jenis Ongkir</th>								
								<th>Jml Paket</th>								
								<th>Waktu Penerimaan</th>								
								<th>Outlet</th>								
								<th>Port</th>								
								<th>Kendaraan</th>								
								<th>Driver Pickup</th>								
								<th>Status</th>
							</tr>
						</thead>
						<tbody>											
						@foreach ($pengiriman as $key => $row)
							<?php 
							$status = "<span class='badge badge-info'>$row->status</span>";
							$cekPortAsal = DB::table("cabang")->where("id",$row->port_id);
							$portAsal = ($cekPortAsal->count()>0)?$cekPortAsal->first()->name:'';
							$cekPortTujuan = DB::table("cabang")->where("id",$row->port_tujuan);
							$portTujuan = ($cekPortTujuan->count()>0)?$cekPortTujuan->first()->name:'';
							?>
							<tr>
								<th class="text-center">{{ $key+1 }}</th>
								<td>{{ $row->kode_tr }}</td>
								<td>{{ $row->awb_number }}</td>
								<td>{{ $row->nama_pengirim }}</td>
								<td>{{ $row->nama_penerima }}</td>
								<td>{{ $row->jenis_tarif }}</td>
								<td>{{ $row->jumlah_paket }}</td>
								<td>{{ $row->tgl_penerimaan }}</td>
								<td>{{ $row->outlet }}</td>
								<td>{{ $portAsal }} - {{ $portTujuan }}</td>
								<td>{{ $row->kendaraan }}</td>
								<td>{{ $row->karyawan }}</td>							
								<td>{!! $status !!}</td>
							</tr>
						@endforeach													
						</tbody>
					</table>
				</div>					
			</div>
		</div>
	</main>


@endsection

