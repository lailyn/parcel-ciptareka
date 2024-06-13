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

			<form class="mb-5" action="{{ route('setoranPaket.createAll') }}" method="POST" enctype="multipart/form-data">					
			<div class="block">
				<div class="block-header block-header-default">
					<h3 class="block-title">
						<a class="btn btn-warning btn-sm float-right" href="{{ route('setoranPaket.insert') }}"> <i class="fa fa-chevron-left"></i> Back</a>
						<button type="submit" onclick="return confirm('anda yakin?')" class="btn btn-success btn-sm float-right mr-2"><i class="fa fa-save"></i> Simpan Semua</button>
					</h3>
				</div>
				<div class="block-header">
					
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Penerima *</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" required placeholder="Nama Penerima" name="penerima">
							</div>
							<label class="col-sm-2 col-form-label" >Keterangan</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" placeholder="Keterangan" name="keterangan">
							</div>
						</div>						
	        
				</div>
				<div class="block-content block-content-full">									
					@csrf
					
					

						
		        <div class="table-responsive">
		        	<table class="table table-striped table-bordered">
							 <thead>
								 <tr>
									 <th width="5%">No</th>									 														 														 
									 <th>Kode</th>
									 <th>Nama Member</td>
									 <th>Paket</th>									 									 									 														 									 
									 <th>Tgl Setoran</th>									 														 
									 <th>Tgl Pembayaran</th>									 														 
									 <th width="10%">Lama</th>									 														 
									 <th>Nominal</th>									 														 									 
								 </tr>
							 </thead>	
							 <tbody>
							 <?php 							 
							 $jum = $setoranPaket_tmp->count();
							 $no=1;
							 ?>
							 <input type='hidden' name='jum' value='<?=$jum?>'>
							 @foreach ($setoranPaket_tmp as $key => $amb)												 													 	
							 	<?php $keys = $key+1; ?>
								<tr>
									<td>{{ $keys }}</td>                																										
									<td>{{ $amb->code }}</td>	                	                								
									<td>{{ $amb->namaMember }}</td>	                
									<td>{{ $amb->namaPaket }}</td>
	                <td>
	                	<input type="date" onclick="cekTanggal('<?=$no?>')" id="tgl_setor_<?=$no?>" class="form-control" name="tgl_setor_<?=$keys?>" value="<?=date('Y-m-d')?>">
	                </td>	                													
	                <td>
	                	<input type="text" id="tgl_bayar_<?=$no?>" class="form-control daterange" name="tgl_bayar_<?=$keys?>" value="<?=date('Y-m-d')?>">
	                </td>	                													
	                <td>
	                	<input type="text" id="lama_<?=$no?>" readonly class="form-control" name="lama_<?=$keys?>" value="1 <?=$amb->jenis_lama?>">
	                	<input type="hidden" id="lamaAsli_<?=$no?>" name="lamaAsli_<?=$keys?>" value="1">
	                	<input type="hidden" name="member_paket_id_<?=$keys?>" value="<?=$amb->id?>">
	                </td>	                													
	                <td>
	                	<input type="number" id="nominal_<?=$no?>" readonly class="form-control" name="nominal_<?=$keys?>" value="<?=$amb->iuran?>">
	                </td>	                																						
								</tr>
								<?php $no++; ?>
								@endforeach
							 


							 </tbody>							 
							</table>
		        </div>

					</form>
					      

				</div>			
			</div>
		</div>
	</main>

<script type="text/javascript">
function cekTanggal(no){		
	let tgl_setor = $("#tgl_setor_"+no).val(id);
	alert(tgl_setor);
}
</script>
@endsection

