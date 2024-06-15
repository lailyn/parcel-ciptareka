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

					<form method="POST" action="{{ route('lapProduk.filter') }}">
						@csrf
					  <div class="form-row">
					    <div class="form-group col-md-4">
					      <label for="inputEmail4">Nama Produk</label>
					      <input type="text" name="nama_produk" value="<?=($filter_1!='')?$filter_1:'';?>" placeholder="Nama Produk (optional)" class="form-control" id="inputEmail4">
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
									<th>Satuan</th>
									<th>Harga</th>									
									<th>Keterangan</th>																
								</tr>
							</thead>
							<tbody>
							@foreach ($dt_produk as $key => $row)							  
							  <tr>
							    <td>{{$key + 1}}</td>                
							    <td>{{ $row->name }}</td>                 
							    <td>{{ $row->satuan }}</td>                  
							    <td>{{ mata_uang_help($row->harga_harian) }}</td>                  
							    <td>{{ $row->keterangan }}</td>                  							    
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
