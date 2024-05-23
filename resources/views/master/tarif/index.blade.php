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
						<a class="btn btn-warning btn-sm float-right ml-2" href="{{ route('tarif.history') }}"> <i class="fa fa-history"></i> Riwayat</a>
						<a class="btn btn-danger btn-sm float-right" href="{{ route('tarif.insert') }}"> <i class="fa fa-plus"></i> Add Item</a>						
					</h3>
				</div>
				<div class="block-content block-content-full">
					<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
					<table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
						<thead>
							<tr>
								<th class="text-center" style="width: 5%;">No</th>								
								<th>Jenis Tarif</th>
								<th>Asal</th>
								<th>Tujuan</th>
								<th>Durasi</th>
								<th>Cost</th>								
								<th>Waktu</th>								
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						
							@foreach ($tarif as $key => $row)

								@php																
								if($row->sta==1) $status = "<label class='badge badge-success'>aktif</label>";
									else $status = "<label class='badge badge-danger'>non-aktif</label>";
								
								$cariAsal = cariDaerah_helper($row->asal,'kec');								
								$cariTujuan = cariDaerah_helper($row->tujuan,'kec');								
								@endphp
							  <tr>
									<td>{{$key + 1}}</td>                									
									<td>{{ $row->jenis_tarif }} {!! $status !!}</td>
									<td>{{ $cariAsal }}</td>
									<td>{{ $cariTujuan }}</td>									
									<td>{{ $row->durasi }} hari</td>
									<td>{{ mata_uang_help($row->cost) }}</td>									
									<td>{{ tgl_indo($row->tgl_mulai) }} - {{ tgl_indo($row->tgl_akhir) }}</td>
									<td>
									  <div class="dropdown">
											<button class="btn btn-circle btn-sm btn-warning" type="button"
												id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
												aria-expanded="false"> Action <i class="fas fa-chevron-down"></i>
											</button>
											<div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
												<!-- <a class="dropdown-item" href="{{ route('tarif.edit', $row->ids) }}">Edit</a>  -->
												<a class="dropdown-item" href="{{ route('tarif.delete', $row->ids) }}" onclick="return confirm('Yakin?')">Delete</a> 
												<a class="dropdown-item" href="{{ route('tarif.nonaktif', $row->ids) }}" onclick="return confirm('Yakin?')">Set Non Aktif</a> 
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