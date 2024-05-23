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

		<!-- Page Content -->
			<div class="block">
				<div class="block-header block-header-default">
					<h3 class="block-title">
						<a class="btn btn-danger btn-sm float-right" href="{{ route('karyawan.insert') }}"> <i class="fa fa-plus"></i> Add Item</a>
					</h3>
				</div>
				<div class="block-content block-content-full">					
					<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
					<table class="table table-responsive table-bordered table-striped table-vcenter js-dataTable-buttons">
						<thead>
							<tr>
								<th class="text-center" style="width: 80px;">ID</th>
								<th>NIP </th>
								<th>Name</th>
								<th>Jabatan</th>
								<th>Departemen</th>																
								<th>Port</th>
								<th>Outlet</th>								
								<th>No.HP</th>
								<th>Email</th>								
								<th>Cakupan Wilayah</th>																
								<th style="width: 10%;">Action</th>
							</tr>
						</thead>
						<tbody>
						
						@foreach ($karyawan as $key => $row)
							@php
							if($row->status==1) $status = "<label class='badge badge-success'>aktif</label>";
								else $status = "<label class='badge badge-danger'>non-aktif</label>";

							$cakupan = DB::table("karyawan_cakupan")->where("karyawan_id",$row->id)->get(); 
							@endphp
							<tr>
							<td>{{$key + 1}}</td>                
							<td>{{ $row->nip }} {!! $status !!}</td>
							<td>{{ $row->name }}</td>
							<td>{{ $row->jabatan }}</td>
							<td>{{ $row->departemen }}</td>                
							<td>{{ $row->cabang }}</td>
							<td>{{ $row->outlet }}</td>
							<td>{{ $row->no_hp }}</td>
							<td>{{ $row->email }}</td>							
							<td>
								@foreach($cakupan AS $rt)
									@php
													$kecamatan = cariDaerah_helper($rt->kecamatan_id,'kec');								
													@endphp
									{{ $kecamatan }},
								@endforeach
							</td>                							
							<td>
								<div class="dropdown">
								<button class="btn btn-circle btn-sm btn-warning" type="button"
									id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
									aria-expanded="false"> Action <i class="fas fa-chevron-down"></i>
								</button>
								<div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="{{ route('karyawan.edit', $row->id) }}">Edit</a>                         
									<a class="dropdown-item" href="{{ route('karyawan.delete', $row->id) }}" onclick="return confirm('Yakin?')">Delete</a> 
									<a class="dropdown-item" href="{{ route('karyawan.akun', $row->id) }}">Generate Akun</a> 
									<a class="dropdown-item" href="{{ route('karyawan.cakupan', $row->id) }}">Cakupan Wilayah</a> 
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
