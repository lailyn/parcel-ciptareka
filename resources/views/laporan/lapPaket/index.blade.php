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

					<form method="POST" action="{{ route('lapPaket.filter') }}">
						@csrf
					  <div class="form-row">
					    <div class="form-group col-md-4">
					      <label for="inputEmail4">Nama Paket</label>
					      <select class="form-control" name="paket_id">
					      	<option value="">- semua -</option>
					      	<?php 
					      	$dt = DB::table("paket")->where("status",1)->get();
					      	foreach ($dt as $key => $value) {
					      		if($filter_1!=''&&$filter_1==$value->id) $rw = 'selected';
					      			else $rw = '';
					      		echo "<option $rw value='$value->id'>$value->name</option>";
					      	}
					      	?>
					      </select>
					    </div>					
					  </div>    					   
				    <button type="submit" name="submit" value="filter" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>					    
				    <!-- <button type="submit" name="submit" value="pdfFormat" class="btn btn-warning"><i class="fa fa-download"></i> Download (*.pdf)</button>					    					   -->
					  
					</form>

				</div>
				<?php if($set=="filter"){ ?>
					<div class="block-content block-content-full">					
						<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
						<table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
							<thead>
								<tr>
									<th class="text-center" style="width: 80px;">No</th>								
									<th>Name</th>
									<th>Detail Produk</th>
									<th>Partnership</th>									
									<th>Member</th>																
								</tr>
							</thead>
							<tbody>
							@foreach ($dt_paket as $key => $row)							  
								<?php 
								$cariPartner = DB::table("member_paket")->join("member","member.id","=","member_paket.member_id")									
									->groupBy("member.partnership_id")->get();

								$cariMember = DB::table("member_paket")->join("member","member.id","=","member_paket.member_id")									
									->groupBy("member.id")->get();

								$detail = "";
								$cariDetail = DB::table("paket_detail")->join("produk","paket_detail.produk_id","=","produk.id")
									->where("paket_detail.paket_id",$row->id)->get(['produk.name AS produkName','paket_detail.*']);
								foreach($cariDetail AS $rt){
									if($detail!='') $detail.=", ";
									$detail.=$rt->produkName;
								}
								?>
							  <tr>
							    <td>{{$key + 1}}</td>                
							    <td>{{ $row->name }}</td>                 
							    <td>{{ $detail }}</td>                  
							    <td>{{ $cariPartner->count() }} orang</td>                  
							    <td>{{ $cariMember->count() }} orang</td>                  							    
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
