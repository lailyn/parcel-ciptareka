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

					<form method="POST" action="{{ route('lapSetoran.filter') }}">
						@csrf
					  <div class="form-row">
					    <div class="form-group col-md-4">
					      <label for="inputEmail4">Pilih Laporan</label>
					      <select class="form-control" name="jenis">
					      	<option <?=($filter_1=='setoran_member')?'selected':'';?> value="setoran_member">Setoran Member</option>
					      	<option <?=($filter_1=='setoran_manajemen')?'selected':'';?> value="setoran_manajemen">Setoran Manajemen</option>
					      	<option <?=($filter_1=='pengembalian_dana')?'selected':'';?> value="pengembalian_dana">Pengembalian Setoran</option>					      						      	
					      </select>
					    </div>
					    <div class="form-group col-md-2">
					      <label for="inputEmail4">Tgl Awal</label>
					      <input type="date" class="form-control" name="tgl_awal" value="<?=($filter_2!="")?$filter_2:date("Y-m")."-01";?>">
					    </div>					
					    <div class="form-group col-md-2">
					      <label for="inputEmail4">Tgl Akhir</label>
					      <input type="date" class="form-control" name="tgl_akhir" value="<?=($filter_3!="")?$filter_3:date("Y-m-d");?>">
					    </div>					
					  </div>    					   
				    <button type="submit" name="submit" value="filter" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>					    
				    <!-- <button type="submit" name="submit" value="pdfFormat" class="btn btn-warning"><i class="fa fa-download"></i> Download (*.pdf)</button>					    					   -->
					  
					</form>

				</div>
				<?php if($set=="filter" && $filter_1=="setoran_member"){ ?>
					<div class="block-content block-content-full">					
						<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
						<table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
							<thead>
								<tr>
									<th class="text-center" style="width: 80px;">ID</th>								
									<th>Kode</th>								
									<th>Member</th>								
									<th>Paket</th>								
									<th>Tgl.Setor</th>
									<th>Nominal</th>																
									<th>Penerima</th>																								
									<th>Keterangan</th>																									
								</tr>
							</thead>
							<tbody>
							
							<?php $gtotal=0; ?>
								@foreach ($dt_setoran as $key => $row)																
								<tr>
									<td>{{$key + 1}}</td>                							
									<td>{{ $row->code }}</td>							
									<td>{{ $row->namaMember }}</td>							
									<td>{{ $row->namaPaket }}</td>							
									<td>{{ $row->tgl_setor }}</td>
									<td>{{ mata_uang_help($row->nominal) }}</td>
									<td>{{ $row->penerima_setoran }}</td>							
									<td>{{ $row->keterangan }}</td>																					
							  </tr>
							  <?php $gtotal+=$row->nominal; ?>
							    
							  @endforeach
								
								<tfoot>
									<tr>
										<td colspan="5">TOTAL</td>
										<td><?=mata_uang_help($gtotal)?></td>
										<td colspan="2"></td>
									</tr>
								</tfoot>

							</tbody>
						</table>
					</div>	
				<?php }elseif($set=="filter" && $filter_1=="setoran_manajemen"){ ?>
					<div class="block-content block-content-full">					
						<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
						<table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
							<thead>
								<tr>
									<th class="text-center" style="width: 80px;">ID</th>								
									<th>Kode</th>																
									<th>Partnership</th>								
									<th>Tgl.Setor</th>
									<th>Member</th>								
									<th>Total</th>																
									<th>Penerima</th>																								
									<th>Keterangan</th>																																																																							
								</tr>
							</thead>
							<tbody>
							<?php $gtotal=0; ?>
							@foreach ($dt_setoran as $key => $row)							
								<?php 								

								$member = "";$total = 0;
								$cekData = DB::table("setoranManajemen_detail")->join("setoranPaket","setoranManajemen_detail.setoranPaket_id","=","setoranPaket.id")
										->join("member_paket","setoranPaket.member_paket_id","=","member_paket.id")
										->join("member","member_paket.member_id","=","member.id")
										->join("paket","member_paket.paket_id","=","paket.id")
										->where("setoranManajemen_detail.code",$row->code)->get(["setoranPaket.nominal","paket.name AS paketName","member.name AS memberName"]);
								foreach($cekData AS $rt){
									if($member!='') $member.=", ";
									$member.=$rt->memberName." (".$rt->paketName.")";
									$total+=$rt->nominal;
								}
								?>
								<tr>
									<td>{{$key + 1}}</td>                							
									<td>{{ $row->code }}</td>															
									<td>{{ $row->namaPartner }}</td>							
									<td>{{ $row->tgl_setor }}</td>
									<td>{{ $member }}</td>
									<td>{{ mata_uang_help($total) }}</td>
									<td>{{ $row->penerima_setoran }}</td>														
									<td>{{ $row->keterangan }}</td>																												
								</tr>

								<?php $gtotal+=$total; ?>
							    
							  @endforeach
													

							</tbody>
							<tfoot>
								<tr>
									<td colspan="5">TOTAL</td>
									<td><?=mata_uang_help($gtotal)?></td>
									<td colspan="2"></td>
								</tr>
							</tfoot>
						</table>
					</div>

				<?php }elseif($set=="filter" && $filter_1=="pengembalian_dana"){ ?>
					<div class="block-content block-content-full">					
						<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
						<table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
							<thead>
								<tr>
									<th class="text-center" style="width: 80px;">ID</th>								
									<th>Kode</th>								
									<th>Member</th>								
									<th>Paket</th>								
									<th>Tgl.Pengembalian</th>
									<th>Nominal</th>																
									<th>Penerima</th>																
									<th>% Manajemen</th>																
									<th>% Partner</th>																								
									<th>Keterangan</th>																																	
								</tr>
							</thead>
							<tbody>
							<?php $gtotal=0; ?>
							@foreach ($dt_setoran as $key => $row)															
								<tr>
									<td>{{$key + 1}}</td>                							
									<td>{{ $row->code }}</td>							
									<td>{{ $row->namaMember }}</td>							
									<td>{{ $row->namaPaket }}</td>							
									<td>{{ $row->tgl_pengembalian }}</td>
									<td>{{ mata_uang_help($row->nominal) }}</td>
									<td>{{ $row->penerima_pengembalian }}</td>							
									<td>{{ $row->presentase_manajemen }}</td>							
									<td>{{ $row->presentase_partner }}</td>							
									<td>{{ $row->keterangan }}</td>																																					
								</tr>
								
								<?php $gtotal+=$row->nominal; ?>
								@endforeach

							</tbody>
							<tfoot>
								<tr>
									<th colspan="5">TOTAL</th>
									<th><?=mata_uang_help($gtotal)?></th>
									<th colspan="4"></th>
								</tr>
							</tfoot>
						</table>
					</div>			
						
				<?php } ?>
			</div>
		</main>
	

@endsection


{{-- <script src="{{ asset('js/pages/be_tables_datatables.min.js') }}"></script> --}}
