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
						<a class="btn btn-warning btn-sm float-right" href="{{ route('setoranPaket.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									

					<?php 
					if($set=="insert"){ 
						$row = ""; ?>
						<form class="mb-5" action="{{ route('setoranPaket.create') }}" method="POST" enctype="multipart/form-data">
					<?php
					}elseif($set=="edit"){
						$row = $setoranPaket; 
						$cekMember = DB::table("member")->join("member_paket","member_paket.member_id","=","member.id")->where("member_paket.id",$row->member_paket_id)->get();
						$namaMember = ($cekMember->count()>0)?$cekMember->first()->name:'';

						$cekPaket = DB::table("paket")->join("member_paket","member_paket.paket_id","=","paket.id")->where("member_paket.id",$row->member_paket_id)->get();
						$namaPaket = ($cekPaket->count()>0)?$cekPaket->first()->name:'';
						$jenis_lama = ($cekPaket->count()>0)?$cekPaket->first()->jenis_lama:'';
						?>
						<form class="mb-5" action="{{ route('setoranPaket.update',$row->id) }}" method="POST" enctype="multipart/form-data">
					<?php } ?>

						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Member Paket</label>
							<div class="col-sm-5">
								<input type="hidden" id="member_paket_id" value="<?=($row)?$row->member_paket_id:'';?>" name="member_paket_id">
								<input type="text" class="form-control" id="namaMember" readonly value="<?=($row)?$namaMember." // ".$namaPaket:'Cari Member';?>" placeholder="Member Paket">																
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
							<label class="col-sm-2 col-form-label">Nominal</label>
							<div class="col-sm-4">
								<input type="number" readonly class="form-control" id="nominal" name="nominal" value="<?=($row)?$row->nominal:old('nominal');?>" placeholder="Nominal">
							</div>
							<label class="col-sm-2 col-form-label">Tgl Setor</label>
							<div class="col-sm-4">
								<input type="date" required class="form-control" name="tgl_setor" value="<?=($row)?$row->tgl_setor:date("Y-m-d");?>" placeholder="Tgl Setor">
							</div>																				
						</div>
						<div class="form-group row">			
							<label class="col-sm-2 col-form-label">Lama</label>
							<div class="col-sm-2">
								<input type="number" readonly class="form-control" id="lama" name="lama" value="<?=($row)?$row->lama:old('lama');?>" placeholder="Lama">
							</div>
							<div class="col-sm-2">
								<?=($row)?$jenis_lama:'';?>
							</div>
							<label class="col-sm-2 col-form-label">Tgl Pembayaran</label>
							<div class="col-sm-4">
								<input readonly type="text" class="form-control daterange" name="tgl_bayar" value="<?=($row)?$row->tgl_bayar:'';?>" placeholder="Tgl Pembayaran">
							</div>																				
						</div>

						<div class="form-group row">			
							<label class="col-sm-2 col-form-label">Penerima</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="penerima_setoran" value="<?=($row)?$row->penerima_setoran:old('penerima_setoran');?>" placeholder="Penerima">
							</div>													
							<label class="col-sm-2 col-form-label">Keterangan</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="keterangan" value="<?=($row)?$row->keterangan:old('keterangan');?>" placeholder="Keterangan">
							</div>						
	          </div>
						<div class="form-group row">
							<div class="col-sm-10 ml-auto">
								<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
								<button type="reset" class="btn btn-default">Reset</button>
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
												 <th>Nama Member</td>
												 <th>Paket</th>									 									 									 														 
												 <th>Nominal</th>									 														 
												 <th width="15%">#</th>
											 </tr>
										 </thead>	
										 <tbody>
										 <?php 
										 $memberList = DB::table("member")->join("member_paket","member.id","=","member_paket.member_id")
														->join("paket","member_paket.paket_id","=","paket.id")
														->orderBy("member.name","ASC")
														->orderBy("member_paket.member_id","ASC")
														->get(["paket.iuran","member.code","member_paket.id","member.name AS namaMember","paket.name AS namaPaket"]);
										 ?>
										 @foreach ($memberList as $key => $amb)												 													 	
											<tr>
											<td>{{$key + 1}}</td>                																										
											<td>{{ $amb->code }}</td>	                	                								
											<td>{{ $amb->namaMember }}</td>	                
											<td>{{ $amb->namaPaket }}</td>
			                <td>{{ mata_uang_help($amb->iuran) }}</td>	                													
											<td>														
												<button type="button" onclick="pilih('{{ $amb->id }}','{{ $amb->iuran }}','{{ $amb->namaMember }}','{{ $amb->namaPaket }}')" class="btn btn-danger text-white btn-sm">Pilih <i class="fa fa-check"></i></button>
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
function pilih(id, iuran, member, paket){	
	$('#studentModal').modal('hide');
	$("#member_paket_id").val(id);
	$("#namaMember").val(member+' // '+paket);		
	$("#nominal").val(iuran);  
}
</script>
@endsection

