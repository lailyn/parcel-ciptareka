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
						<a class="btn btn-danger btn-sm float-right" href="{{ route('setoranPaket.insert') }}"> <i class="fa fa-plus"></i> Add New</a>
					</h3>
				</div>
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
								<th style="width: 10%;">Action</th>
							</tr>
						</thead>
						<tbody>
						<?php $gtotal=0; ?>
						@foreach ($setoranPaket as $key => $row)							
							<?php 
							if($row->submit==1){
								$status = 'd-none';
								$label = "<label class='badge badge-success'>submitted</label>";
							}else{
								$status = '';$label = "";
							}
							?>
							<tr>
							<td>{{$key + 1}}</td>                							
							<td>{{ $row->code }} {!! $label !!}</td>							
							<td>{{ $row->namaMember }}</td>							
							<td>{{ $row->namaPaket }}</td>							
							<td>{{ $row->tgl_setor }}</td>
							<td>{{ mata_uang_help($row->nominal) }}</td>
							<td>{{ $row->penerima_setoran }}</td>							
							<td>{{ $row->keterangan }}</td>																					
							<td>
								<div class="dropdown">
								<button class="btn btn-circle btn-sm btn-warning" type="button"
									id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
									aria-expanded="false"> Action <i class="fas fa-chevron-down"></i>
								</button>
								<div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item <?=$status?>" href="{{ route('setoranPaket.edit', $row->id) }}">Edit</a>                         																		
									<a class="dropdown-item <?=$status?>" href="{{ route('setoranPaket.delete', $row->id) }}" onclick="return confirm('Yakin?')">Delete</a> 																		
								</div>
							</div>
							</td>
							</tr>
							<?php $gtotal+=$row->nominal; ?>
							@endforeach

						</tbody>
						<tfoot>
							<tr>
								<th colspan="5">TOTAL</th>
								<th><?=mata_uang_help($gtotal)?></th>
								<th colspan="3"></th>
							</tr>
						</tfoot>
					</table>
				</div>			
			</div>
	</main>
@endsection

