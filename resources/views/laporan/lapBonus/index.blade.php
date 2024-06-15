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

					<form method="POST" action="{{ route('lapBonus.filter') }}">
						@csrf
					  <div class="form-row">
					    <div class="form-group col-md-4">
					      <label for="inputEmail4">Partnership</label>
					      <select class="form-control" name="partnership_id">
					      	<option value="">- semua -</option>
					      	<?php 
					      	$dt = DB::table("partnership")->where("status",1)->get();
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
									<th>Partnership</th>									
									<th>Level</th>									
									<th>Total Setoran</th>																
									<th>THR</th>																
									<th>Tabungan</th>																
									<th>Total Bonus</th>																
								</tr>
							</thead>
							<tbody>
							<?php $no=1;$bonus=0; ?>
							@foreach ($dt_paket as $key => $row)							  
								<?php 
								$setoran = DB::table("setoranManajemen")->join("setoranManajemen_detail","setoranManajemen.code","=","setoranManajemen_detail.code")									
									->join("setoranPaket","setoranManajemen_detail.setoranPaket_id","=","setoranPaket.id")
									->where("setoranManajemen.approval_status",1)
									->sum("setoranPaket.nominal");

								$cariThr=0;$cariTabungan=0;$noThr=0;$noTabungan=0;$bonusThr=0;$bonusTabungan=0;
								$cekData = DB::table("setoranPaket")->join("member_paket","setoranPaket.member_paket_id","=","member_paket.id")
										->join("paket","member_paket.paket_id","=","paket.id")
										->join("member","member_paket.member_id","=","member.id")
										->where("setoranPaket.submit",1)->where("member.partnership_id",$row->partnership_id)
										->get(['paket.jenis_paket',"setoranPaket.nominal"]);
								foreach ($cekData as $key => $value) {
									if($value->jenis_paket=="paket"){										
										$cariThr+=$value->nominal;
										$noThr++;
										$pt = $row->persen_thr/100;
										$bonusThr+=$pt*$value->nominal;
									}elseif($value->jenis_paket=="tabungan"){
										$cariTabungan+=$value->nominal;
										$noTabungan++;
										$ps = $row->persen_parcel/100;
										$bonusTabungan+=$ps*$value->nominal;
									}									
								}
								$bonus = $bonusTabungan + $bonusThr;
								
								?>
							  <tr>
							    <td>{{ $no }}</td>                
							    <td>{{ $row->namaPartner }}</td>                 
							    <td>{{ $row->level }}</td>                 							    
							    <td>{{ mata_uang_help($setoran) }}</td>                  
							    <td>{{ mata_uang_help($cariThr) }} (<?=$noThr?> item)</td>                  							    
							    <td>{{ mata_uang_help($cariTabungan) }} (<?=$noTabungan?> item)</td>                  							    
							    <td>{{ mata_uang_help($bonus) }}</td>                  							    							                 							   
							  </tr>
							  <?php $no++; ?>
							  @endforeach
													

							</tbody>
						</table>
					</div>			
				<?php } ?>
			</div>
		</main>
	

@endsection


{{-- <script src="{{ asset('js/pages/be_tables_datatables.min.js') }}"></script> --}}
