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

		
		
		<?php if (session()->has('msg')) { ?>
		  {!! session()->get('msg') !!}                          
		<?php session()->forget('msg'); } ?>			      
		<div class="content">									
			<div class="block">
				
				<div class="block-content block-content-full">

					<form method="POST" action="{{ route('lapMitra.filter') }}">
						@csrf
					  <div class="form-row">
					    <div class="form-group col-md-4">
					      <label for="inputEmail4">Partnership / Member</label>
					      <select class="form-control" name="jenis">
					      	<option <?=($filter_1=='partnership')?'selected':'';?> value="partnership">Partnership</option>
					      	<option <?=($filter_1=='member')?'selected':'';?> value="member">Member</option>
					      	
					      </select>
					    </div>					
					  </div>    					   
				    <button type="submit" name="submit" value="filter" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>					    
				    <!-- <button type="submit" name="submit" value="pdfFormat" class="btn btn-warning"><i class="fa fa-download"></i> Download (*.pdf)</button>					    					   -->
					  
					</form>

				</div>
				<?php if($set=="filter" && $filter_1=="partnership"){ ?>
					<div class="block-content block-content-full">					
						<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
						<table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
							<thead>
								<tr>
									<th class="text-center" style="width: 80px;">ID</th>								
									<th>Kode</th>								
									<th>Partnership</th>								
									<th>No.HP</th>
									<th>No.KTP</th>																
									<th>Alamat</th>																								
									<th>Member</th>																									
								</tr>
							</thead>
							<tbody>
							
							@foreach ($dt_partnership as $key => $row)
								<?php
								if($row->status==1) $status = "<label class='badge badge-success'>aktif</label>";
									else $status = "<label class='badge badge-danger'>non-aktif</label>";
								$member = "";
								$cek = DB::table("member")->where("partnership_id",$row->id)->get(["member.name"]);
								foreach ($cek as $is => $value) {
									if($member!='') $member.=", ";
									$member.=$value->name;
								}
								?>
								<tr>
									<td>{{$key + 1}}</td>                							
									<td>{{ $row->code }}</td>							
									<td>{{ $row->name }}</td>							
									<td>{{ $row->no_hp }}</td>
									<td>{{ $row->no_ktp }}</td>
									<td>{{ $row->alamat }}</td>							
									<td>{{ $member }}</td>
							  </tr>
							    
							  @endforeach
													

							</tbody>
						</table>
					</div>	
				<?php }elseif($set=="filter" && $filter_1=="member"){ ?>
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
								</tr>
							</thead>
							<tbody>
							
							@foreach ($dt_partnership as $key => $row)
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
								</tr>
							    
							  @endforeach
													

							</tbody>
						</table>
					</div>			
						
				<?php } ?>
			</div>
		</main>
	

@endsection


{{-- <script src="{{ asset('js/pages/be_tables_datatables.min.js') }}"></script> --}}
