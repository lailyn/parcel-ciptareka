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
						<a class="btn btn-danger btn-sm float-right" href="{{ route('suratJalan.insert') }}"> <i class="fa fa-plus"></i> Add Item</a>
					</h3>
				</div>				
				<div class="block-content block-content-full">
					<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
					<table class="table table-responsive table-bordered table-striped table-vcenter js-dataTable-buttons">
						<thead>
							<tr>
								<th class="text-center" style="width: 5%;">No</th>								
								<th>No Surat Jalan</th>
								<th>Tgl SJ</th>
								<th>No Manifest</th>
								<th>No AWB</th>
								<th>Pengirim</th>
								<th>Penerima</th>							
								<th>Jml Paket</th>								
								<th>Ongkir</th>								
								<th>Kecamatan</th>								
								<th>Kurir</th>																
								<th>Status</th>
								<th>#</th>
							</tr>
						</thead>
						<tbody>
						@foreach ($suratJalan as $key => $row)
							<?php 
							$status = "<span class='badge badge-info'>$row->status</span>";
							$cariTujuan = cariDaerah_helper($row->id_kelurahan_penerima,'kec');
							?>
							<tr>
								<th class="text-center">{{ $key+1 }}</th>
								<td>{{ $row->code }}</td>
								<td>{{ $row->tgl_surat }}</td>
								<td>{{ $row->manifest_code }}</td>
								<td>{{ $row->awb_number }}</td>
								<td>{{ $row->nama_pengirim }}</td>
								<td>{{ $row->nama_penerima }}</td>
								<td>{{ $row->jumlah_paket }}</td>
								<td>{{ mata_uang_help($row->total_tagihan) }}</td>
								<td>{{ $cariTujuan }}</td>
								<td>{{ $row->kurir }}</td>														
								<td>{!! $status !!}</td>
								<td></td>
							</tr>
						@endforeach													
						</tbody>
					</table>
				</div>			
			</div>
	</main>
@endsection


{{-- <script src="{{ asset('js/pages/be_tables_datatables.min.js') }}"></script> --}}
