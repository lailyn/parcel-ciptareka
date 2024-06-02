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
			
				<div class="row">
					<div class="col-6 col-md-4 col-lg-6 col-xl-4">
						<a class="block block-rounded block-link-pop border-left border-primary border-4x" href="javascript:void(0)">
							<div class="block-content block-content-full">
								<div class="font-size-sm font-w600 text-uppercase text-muted">Partnership</div>
								<div class="font-size-h2 font-w400 text-dark">
									<?php 
									$id_user_type = session()->get('id_user_type');
									if($id_user_type==1){
										echo DB::table("partnership")->get()->count();
									}else{
										echo $id_karyawan = session()->get('username');										
									}
									?>
								</div>
							</div>
						</a>
					</div>
					<div class="col-6 col-md-4 col-lg-6 col-xl-4">
						<a class="block block-rounded block-link-pop border-left border-primary border-4x" href="javascript:void(0)">
							<div class="block-content block-content-full">
								<div class="font-size-sm font-w600 text-uppercase text-muted">Member</div>
								<div class="font-size-h2 font-w400 text-dark">
									<?php 
									$id_user_type = session()->get('id_user_type');
									if($id_user_type==1){
										echo DB::table("member")->get()->count();
									}else{
										echo $id_karyawan = session()->get('username');										
									}
									?>
								</div>
							</div>
						</a>
					</div>
					<div class="col-6 col-md-4 col-lg-6 col-xl-4">
						<a class="block block-rounded block-link-pop border-left border-primary border-4x" href="javascript:void(0)">
							<div class="block-content block-content-full">
								<div class="font-size-sm font-w600 text-uppercase text-muted">Paket</div>
								<div class="font-size-h2 font-w400 text-dark">
									<?php 
									$id_user_type = session()->get('id_user_type');
									if($id_user_type==1){
										echo DB::table("paket")->get()->count();
									}else{
										echo $id_karyawan = session()->get('username');										
									}
									?>
								</div>
							</div>
						</a>
					</div>
				</div>
			
		<!-- Page Content -->
			<div class="block">
				<div class="block-header block-header-default">
					<h3 class="block-title">
						<a class="btn btn-danger btn-sm float-right" href="{{ route('member.insert') }}"> <i class="fa fa-plus"></i> Add Member</a>
						<a class="btn btn-success btn-sm float-right mr-2" href="{{ route('member.import') }}"> <i class="fa fa-upload"></i> Import Excel</a>
					</h3>
				</div>
				<div class="block-content block-content-full">					
					<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
					<table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
						<thead>
							<tr>
								<th class="text-center" style="width: 80px;">ID</th>								
								<th>Kode</th>								
								<th>Partnership</th>								
								<th>Member</th>								
								<th>Paket yg Diambil</th>
								<th>No.HP</th>																								
								<th>Alamat</th>																
								<th>No.KTP</th>																								
								<th style="width: 10%;">Action</th>
							</tr>
						</thead>
						<tbody>
						
						@foreach ($member as $key => $row)
							<?php
							if($row->status==1) $status = "<label class='badge badge-success'>aktif</label>";
								else $status = "<label class='badge badge-danger'>non-aktif</label>";
							$paket = "";
							$cek = DB::table("member_paket")->join("paket","member_paket.paket_id","=","paket.id")
									->where("member_id",$row->id)->get(["paket.name AS namaPaket"]);
							foreach ($cek as $key => $value) {
								if($paket!='') $paket.=", ";
								$paket.=$value->namaPaket;
							}
							?>
							<tr>
							<td>{{$key + 1}}</td>                							
							<td>{{ $row->code }}</td>							
							<td>{{ $row->partner }} </td>							
							<td>{{ $row->name }} {!! $status !!}</td>
							<td>{{ $paket }}</td>
							<td>{{ $row->no_hp }}</td>
							<td>{{ $row->alamat }}</td>							
							<td>{{ $row->no_ktp }}</td>																					
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
