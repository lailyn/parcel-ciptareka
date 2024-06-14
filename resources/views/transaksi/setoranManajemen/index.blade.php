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
						<a class="btn btn-danger btn-sm float-right" href="{{ route('setoranManajemen.insert') }}"> <i class="fa fa-plus"></i> Add New</a>
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
								<th>Tgl.Setor</th>
								<th>Member</th>								
								<th>Total</th>																
								<th>Penerima</th>																								
								<th>Keterangan</th>																																
								<th width="5%">#</th>																																
							</tr>
						</thead>
						<tbody>
						<?php $gtotal=0; ?>
						@foreach ($setoranManajemen as $key => $row)							
							<?php 
							if($row->approval_status==1){
								$status = 'd-none';
								$label = "<label class='badge badge-success'>approved</label>";
							}else{
								$status = '';$label = "";
							}

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
								<td>{{ $row->code }} {!! $label !!}</td>															
								<td>{{ $row->namaPartner }}</td>							
								<td>{{ $row->tgl_setor }}</td>
								<td>{{ $member }}</td>
								<td>{{ mata_uang_help($total) }}</td>
								<td>{{ $row->penerima_setoran }}</td>														
								<td>{{ $row->keterangan }}</td>																												
								<td>
									<div class="dropdown">
										<button class="btn btn-circle btn-sm btn-warning" type="button"
											id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
											aria-expanded="false"> Action <i class="fas fa-chevron-down"></i>
										</button>
										<div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
											<a class="dropdown-item" href="{{ route('setoranManajemen.detail', $row->id) }}">Detail</a>                         																		
											<a class="dropdown-item <?=$status?>" href="{{ route('setoranManajemen.approval', $row->id) }}">Approval</a>                         																		
											<a class="dropdown-item <?=$status?>" href="{{ route('setoranManajemen.delete', $row->id) }}" onclick="return confirm('Yakin?')">Delete</a> 																		
										</div>
									</div>
								</td>
							</td>
							</tr>
							
							<?php $gtotal+=$total; ?>
							@endforeach

						</tbody>
						<tfoot>
							<tr>
								<th colspan="5">TOTAL</th>
								<th><?=mata_uang_help($gtotal)?></th>
								<th colspan="5"></th>
							</tr>
						</tfoot>
					</table>
				</div>			
			</div>
	</main>
@endsection

