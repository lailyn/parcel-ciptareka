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
					$row = $setoranManajemen->first(); ?>
					<form class="mb-5" action="{{ route('setoranManajemen.saveApproval',$row->id) }}" method="POST" enctype="multipart/form-data">					

						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Partnership</label>
							<div class="col-sm-5">								
								<input type="text" class="form-control" id="name" readonly value="<?=($row)?$row->namaPartner:'Cari Partner';?>" placeholder="Partnership">																
							</div>																				
							<label class="col-sm-2 col-form-label">Kode</label>
							<div class="col-sm-2">	
								<input type="text" class="form-control" name="kode" readonly value="<?=($row)?$row->code:'Auto';?>" placeholder="Kode">								
							</div>				
						</div>						
											
						<div class="form-group row">										
							<label class="col-sm-2 col-form-label">Tgl Setor</label>
							<div class="col-sm-3">
								<input type="date" readonly class="form-control" name="tgl_setor" value="<?=($row)?$row->tgl_setor:date("Y-m-d");?>" placeholder="Tgl Setor">
							</div>																				
	          </div>
	          <div class="form-group row">										
							<label class="col-sm-2 col-form-label">Keterangan</label>
							<div class="col-sm-5">
								<input type="text" readonly class="form-control" name="keterangan" value="<?=($row)?$row->keterangan:old('keterangan');?>" placeholder="Keterangan">
							</div>						
							<label class="col-sm-2 col-form-label">Penerima</label>
							<div class="col-sm-3">
								<input type="text" readonly class="form-control" name="penerima_setoran" value="<?=($row)?$row->penerima_setoran:old('penerima_setoran');?>" placeholder="Penerima">
							</div>													
	          </div>
	          <hr>
	          <span id="tabelKan">
							<table class="table table-striped table-bordered" id="myTable">
							 	<thead>
								 	<tr>
									 	<th width="5%">No</th>									 
									 	<th>Member</th>		 	
									 	<th>Paket</th>		 	
									 	<th>Nominal</th>		 										 	
								 	</tr>
							 	</thead>	
								<tbody>		
								<?php 
								 $no=1;$gtotal=0;
								 $setoranPaket = DB::table("setoranManajemen_detail")->join("setoranPaket","setoranManajemen_detail.setoranPaket_id","=","setoranPaket.id")
										->join("member_paket","setoranPaket.member_paket_id","=","member_paket.id")
										->join("member","member_paket.member_id","=","member.id")
										->join("paket","member_paket.paket_id","=","paket.id")
										->where("setoranManajemen_detail.code",$row->code)->get(["setoranPaket.nominal","paket.name AS paketName","member.name AS memberName"]);
								 foreach ($setoranPaket as $key => $dt) {	 	
								 	$jum = $setoranPaket->count();
								 	echo "
								 	<tr>
								 		<td>$no</td>
								 		<td>$dt->memberName</td>	 		
								 		<td>$dt->paketName</td>	 		
								 		<td>".mata_uang_help($dt->nominal)."</td>	 										 		
								 	</tr>
								 	";
								 	$gtotal+=$dt->nominal;
								 	$no++;
								 }
								 ?>
								</tbody>
								<tfoot>
									<tr>
										<th colspan="3">Total</th>
										<th><?=mata_uang_help($gtotal)?></th>										
									</tr>
								</tfoot>
							</table>
						</span>

						<div class="form-group row">
							<div class="col-sm-10 ml-auto">
								<button onclick="return confirm('Anda yakin?')" type="submit" class="btn btn-success"><i class="fa fa-check"></i> Approve</button>								
							</div>
						</div>
						
					</form>

					

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

