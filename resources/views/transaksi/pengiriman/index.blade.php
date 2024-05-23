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
		<div class="content">
			<div class="row">
				<div class="col-6 col-md-3 col-lg-6 col-xl-3">
					<a class="block block-rounded block-link-pop border-left border-danger border-4x" href="javascript:void(0)">
						<div class="block-content block-content-full">
							<div class="font-size-sm font-w600 text-uppercase text-muted">Total Pengiriman</div>
							<div class="font-size-h2 font-w400 text-dark">{{ mata_uang_help($total) }}</div>
						</div>
					</a>
				</div>
				<div class="col-6 col-md-3 col-lg-6 col-xl-3">
					<a class="block block-rounded block-link-pop border-left border-primary border-4x" href="javascript:void(0)">
						<div class="block-content block-content-full">
							<div class="font-size-sm font-w600 text-uppercase text-muted">Manifest Jemput</div>
							<div class="font-size-h2 font-w400 text-dark">{{ mata_uang_help($jemput) }}</div>
						</div>
					</a>
				</div>
				<div class="col-6 col-md-3 col-lg-6 col-xl-3">
					<a class="block block-rounded block-link-pop border-left border-warning border-4x" href="javascript:void(0)">
						<div class="block-content block-content-full">
							<div class="font-size-sm font-w600 text-uppercase text-muted">Manifest Lintas</div>
							<div class="font-size-h2 font-w400 text-dark">{{ mata_uang_help($lintas) }}</div>
						</div>
					</a>
				</div>
				<div class="col-6 col-md-3 col-lg-6 col-xl-3">
					<a class="block block-rounded block-link-pop border-left border-success border-4x" href="javascript:void(0)">
						<div class="block-content block-content-full">
							<div class="font-size-sm font-w600 text-uppercase text-muted">Manifest Antar</div>
							<div class="font-size-h2 font-w400 text-dark">{{ mata_uang_help($antar) }}</div>
						</div>
					</a>
				</div>
			</div>
			<?php if (session()->has('msg')) { ?>
				{!! session()->get('msg') !!}                          
			<?php session()->forget('msg'); } ?>			
			<div class="block">
				<div class="block-header block-header-default">
					<h3 class="block-title">					
						<?php 
						if($set=="view"){
						?>	
							<a class="btn btn-warning btn-sm ml-2 float-right" href="{{ route('pengiriman.history') }}"> <i class="fa fa-history"></i> Riwayat</a>							
						<?php }else{ ?>
							<a class="btn btn-warning btn-sm ml-2 float-right" href="{{ route('pengiriman.stt-list') }}"> <i class="fa fa-chevron-left"></i> Back</a>							
						<?php } ?>
					</h3>
				</div>
				<div class="block-content block-content-full">
					<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
					<table class="table table-responsive table-bordered table-striped table-vcenter js-dataTable-buttons">
						<thead>
							<tr>
								<th class="text-center" style="width: 5%;">No</th>								
								<th>Detail Transaksi</th>
								<th>STT Number</th>
								<th>Pengirim</th>
								<th>Penerima</th>
								<th>Ongkir</th>
								<th>Paket</th>																
								<th>Status</th>								
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						
						@foreach ($pengiriman as $key => $row)
							<?php
							$status_bayar = "<span class='badge badge-success'>Pembayaran: $row->status_bayar</span>";
							$status = "<span class='badge badge-info'>Status: $row->status</span>";
							$jenis_tarif = "<span class='badge badge-primary'>$row->jenis_tarif</span>";
							$cariTujuan = cariDaerah_helper($row->id_kelurahan_penerima,'kec');								
							$cariAsal = cariDaerah_helper($row->id_kelurahan_pengirim,'kec');								
							$tgl_indo = tgl_indo(substr($row->created_at, 0,10));
							$jadwal_pickup = (!is_null($row->jadwal_pickup))?"<span class='badge badge-warning'>Pickup: $row->jadwal_pickup</span>":"";
							?>
						  <tr>
								<td>{{$key + 1}}</td>                
								<td>
									{!! $jenis_tarif !!} <br>
									<strong>{{ $row->code }}</strong> <br>
									<small>{{ $tgl_indo }} {{ substr($row->created_at, 11,9) }}</small>
								</td>
								<td>
									{!! QrCode::size(100)->generate($row->stt_number) !!}
									<strong>{{ $row->stt_number }}</strong> <br>									
								</td>
								<td>
									<strong>{{ $row->nama_pengirim }}</strong> <br>
									<small>{{ $row->no_hp_pengirim }} //</small>
									<small>{{ $row->alamat_pengirim }} ,</small>
									<small>{{ $cariAsal }}</small>
								</td>																
								<td>
									<strong>{{ $row->nama_penerima }}</strong> <br>
									<small>{{ $row->no_hp_penerima }} //</small>
									<small>{{ $row->alamat_penerima }} ,</small>
									<small>{{ $cariAsal }}</small>
								</td>																
								<td><strong>Rp{{ mata_uang_help($row->total_tagihan) }}</strong></td>															
								<td>{{ $row->isi_paket }}</td>															
								<td>
									{!! $status !!} <br>
									{!! $status_bayar !!} <br>
									{!! $jadwal_pickup !!}
								</td>																
								<td>
								  <div class="dropdown">
										<button class="btn btn-circle btn-sm btn-warning" type="button"
											id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
											aria-expanded="false"> Action <i class="fas fa-chevron-down"></i>
										</button>
										<div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" href="{{ route('pengiriman.bayar', $row->id) }}">Bayar</a> 
											<a class="dropdown-item" href="{{ route('pengiriman.cetakSTT', $row->id) }}">Cetak STT</a> 
											<a class="dropdown-item" href="{{ route('pengiriman.edit', $row->id) }}">Edit</a> 
											<a class="dropdown-item" href="{{ route('pengiriman.delete', $row->id) }}" onclick="return confirm('Yakin?')">Delete</a> 
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
