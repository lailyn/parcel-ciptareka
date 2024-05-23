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
		<!-- END Hero -->
		<?php if (session()->has('msg')) { ?>
			{!! session()->get('msg') !!}                          
		<?php session()->forget('msg'); } ?>
		<!-- Page Content -->
			<div class="block">
				<div class="block-header block-header-default">
					<h3 class="block-title">
						<a class="btn btn-danger btn-sm float-right" href="{{ route('kendaraan_dan_plat.insert') }}"> <i class="fa fa-plus"></i> Add Item</a>
					</h3>
				</div>
				<div class="block-content block-content-full">
					<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
					<table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
						<thead>
							<tr>
								<th class="text-center" style="width: 80px;">ID</th>
								<th>Name</th>
								<th>Jenis Kendaraan</th>
								<th>Deskripsi</th>
								<th>No.Plat</th>
								<th class="d-none d-sm-table-cell" style="width: 10%;">Status</th>
								<th style="width: 10%;">Action</th>
							</tr>
						</thead>
						<tbody>
						
							@foreach ($kendaraan_dan_plat as $key => $row)
								@php
								if($row->status==1) $status = "<label class='badge badge-success'>aktif</label>";
									else $status = "<label class='badge badge-danger'>non-aktif</label>";
								@endphp
							  <tr>
									<td>{{$key + 1}}</td>                
									<td>{{ $row->name }}</td>
									<td>{{ $row->jenis_kendaraan }}</td>
									<td>{{ $row->deskripsi }}</td>
									<td>{{ $row->no_plat }}</td>
									<td>{!! $status !!}</td>
									<td>
									  <div class="dropdown">
										<button class="btn btn-circle btn-sm btn-warning" type="button"
											id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
											aria-expanded="false"> Action <i class="fas fa-chevron-down"></i>
										</button>
										<div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" href="{{ route('kendaraan_dan_plat.edit', $row->id) }}">Edit</a> 
											<a class="dropdown-item" href="{{ route('kendaraan_dan_plat.delete', $row->id) }}" onclick="return confirm('Yakin?')">Delete</a> 
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
