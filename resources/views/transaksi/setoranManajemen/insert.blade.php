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
		
		<div class="content">	
			<?php if (session()->has('msg')) { ?>
	      {!! session()->get('msg') !!}                          
	    <?php session()->forget('msg'); } ?>			      
			
			{!! cekError($errors) !!}    

			<div class="block">
				<div class="block-header block-header-default">
					<h3 class="block-title">
						<a class="btn btn-warning btn-sm float-right" href="{{ route('setoranManajemen.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									

					<?php 
					if($set=="insert"){ 
						$row = ""; ?>
						<form class="mb-5" action="{{ route('setoranManajemen.create') }}" method="POST" enctype="multipart/form-data">
					<?php
					}elseif($set=="edit"){
						$row = $setoranManajemen; ?>
						<form class="mb-5" action="{{ route('setoranManajemen.update',$row->id) }}" method="POST" enctype="multipart/form-data">
					<?php } ?>

						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Partnership</label>
							<div class="col-sm-5">
								<input type="hidden" id="partnership_id" value="<?=($row)?$row->partnership_id:'';?>" name="partnership_id">
								<input type="text" class="form-control" id="name" readonly value="<?=($row)?$row->name:'Cari Partner';?>" placeholder="Member Paket">																
							</div>													
							<div class="col-sm-1">
								<button data-toggle="modal" data-target="#studentModal" class="btn btn-warning" type="button"><i class="fa fa-search"></i> Cari</button>
							</div>						
							<label class="col-sm-2 col-form-label">Kode</label>
							<div class="col-sm-2">	
								<input type="text" class="form-control" name="kode" readonly value="<?=($row)?$row->code:'Auto';?>" placeholder="Kode">								
							</div>				
						</div>						
											
						<div class="form-group row">										
							<label class="col-sm-2 col-form-label">Tgl Setor</label>
							<div class="col-sm-3">
								<input type="date" required class="form-control" name="tgl_setor" value="<?=($row)?$row->tgl_setor:date("Y-m-d");?>" placeholder="Tgl Setor">
							</div>																				
	          </div>
	          <div class="form-group row">										
							<label class="col-sm-2 col-form-label">Keterangan</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" name="keterangan" value="<?=($row)?$row->keterangan:old('keterangan');?>" placeholder="Keterangan">
							</div>						
							<label class="col-sm-2 col-form-label">Penerima</label>
							<div class="col-sm-3">
								<input type="text" class="form-control" name="penerima_setoran" value="<?=($row)?$row->penerima_setoran:old('penerima_setoran');?>" placeholder="Penerima">
							</div>													
	          </div>
	          <hr>
	          <span id="tabelKan">
							<div id="tabeldata" class="table-responsive"></div>
						</span>
						<div class="form-group row">
							<div class="col-sm-10 ml-auto">
								<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
								<button type="button" onclick="window.location.reload()" class="btn btn-default">Reset</button>
							</div>
						</div>
					</form>

					<div class="modal fade" id="studentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					  <div class="modal-dialog modal-lg" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="exampleModalLabel">Cari Data Member</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					        <div class="table-responsive">
					        	<table class="table table-striped table-bordered js-dataTable-buttons">
										 <thead>
											 <tr>
												 <th width="5%">No</th>									 														 														 
												 <th>Kode</th>
												 <th>Nama Partnership</td>												 						 									 									 														 												 						 													
												 <th width="15%">#</th>
											 </tr>
										 </thead>	
										 <tbody>
										 <?php 
										 $memberList = DB::table("partnership")
														->orderBy("partnership.name","ASC")
														->get();
										 ?>
										 @foreach ($memberList as $key => $amb)												 													 	
											<tr>
											<td>{{$key + 1}}</td>                																										
											<td>{{ $amb->code }}</td>	                	                								
											<td>{{ $amb->name }}</td>	                											
											<td>														
												<button type="button" onclick="pilih('{{ $amb->id }}','{{ $amb->name }}')" class="btn btn-danger text-white btn-sm">Pilih <i class="fa fa-check"></i></button>
											</td>
											</tr>
												
											@endforeach
										 


										 </tbody>							 
										</table>
					        </div>
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>							        
					      </div>
					    </div>
					  </div>
					</div>

				</div>			
			</div>
		</div>
	</main>

<script type="text/javascript">
function pilih(id, name){	
	$('#studentModal').modal('hide');
	$("#partnership_id").val(id);
	$("#name").val(name);	
	tampilData();	
}

function tampilData(){
	let partnership_id = $("#partnership_id").val();		
	$.ajax({
    url : "{{ route('setoranManajemen.tampilData') }}",
    type:'POST',
    data: {
      _token: '{!! csrf_token() !!}',        
      partnership_id: partnership_id, 
      kode: '', 
      mode: 'insert',               
    },
    success: function(data) {
      $('#tabeldata').html(data);
    }
  });
}

</script>
@endsection

