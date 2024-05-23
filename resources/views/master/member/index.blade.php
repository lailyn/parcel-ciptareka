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
		<?php if (session()->has('msg')) { ?>
		  {!! session()->get('msg') !!}                          
		<?php session()->forget('msg'); } ?>			      
		<div class="content">			

		<!-- Page Content -->
			<div class="block">
				<div class="block-header block-header-default">
					<h3 class="block-title">
						<a class="btn btn-danger btn-sm float-right" href="{{ route('member.insert') }}"> <i class="fa fa-plus"></i> Add Item</a>
					</h3>
				</div>
				<div class="block-content block-content-full">					
					<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
					<table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
						<thead>
							<tr>
								<th class="text-center" style="width: 80px;">ID</th>								
								<th>Kode</th>								
								<th>Nama Member</th>								
								<th>No.HP</th>
								<th>No.KTP</th>																
								<th>Alamat</th>																
								<th>Kecamatan</th>																
								<th>Kota</th>																
								<th>Kodepos</th>																
								<th>Sosial Media</th>																
								<th style="width: 10%;">Action</th>
							</tr>
						</thead>
						<tbody>
						
						@foreach ($member as $key => $row)
							@php
							if($row->status==1) $status = "<label class='badge badge-success'>aktif</label>";
								else $status = "<label class='badge badge-danger'>non-aktif</label>";
							
							@endphp
							<tr>
							<td>{{$key + 1}}</td>                							
							<td>{{ $row->code }}</td>							
							<td>{{ $row->name }} {!! $status !!}</td>							
							<td>{{ $row->no_hp }}</td>
							<td>{{ $row->no_ktp }}</td>
							<td>{{ $row->alamat }}</td>							
							<td>{{ $row->kecamatan }}</td>														
							<td>{{ $row->kota }}</td>														
							<td>{{ $row->kodepos }}</td>														
							<td>IG:{{ $row->akun_instagram }} // FB:{{ $row->akun_fb }} // Tiktok:{{ $row->akun_tiktok }}</td>														
							<td>
								<div class="dropdown">
								<button class="btn btn-circle btn-sm btn-warning" type="button"
									id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
									aria-expanded="false"> Action <i class="fas fa-chevron-down"></i>
								</button>
								<div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="{{ route('member.edit', $row->id) }}">Edit</a>                         
									<a class="dropdown-item" href="{{ route('member.delete', $row->id) }}" onclick="return confirm('Yakin?')">Delete</a> 
									<a class="dropdown-item" href="{{ route('member.pilihPaket', $row->id) }}">Pilih Paket</a> 									
									<a onclick="return confirm('Anda Yakin?')" class="dropdown-item" href="{{ route('member.jadiPartner', $row->id) }}">Jadikan Partnership</a> 									
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
