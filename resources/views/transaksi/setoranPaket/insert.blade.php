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

			<form class="mb-5" action="{{ route('setoranPaket.create') }}" method="POST" enctype="multipart/form-data">					
			<div class="block">
				<div class="block-header block-header-default">
					<h3 class="block-title">
						<a class="btn btn-warning btn-sm float-right" href="{{ route('setoranPaket.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
						<button type="submit" onclick="return confirm('anda yakin?')" class="btn btn-primary btn-sm float-right mr-2"><i class="fa fa-forward"></i> Proses Bayar</button>
					</h3>
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
									 <th>Nominal</th>									 														 
									 <th width="10%">#</th>
								 </tr>
							 </thead>	
							 <tbody>
							 <?php 
							 $memberList = DB::table("member")->join("member_paket","member.id","=","member_paket.member_id")
											->join("paket","member_paket.paket_id","=","paket.id")
											->orderBy("member.name","ASC")
											->orderBy("member_paket.member_id","ASC")
											->get(["paket.iuran","member.code","member_paket.id","member.name AS namaMember","paket.name AS namaPaket"]);
							 $jum = $memberList->count();
							 $no=1;
							 ?>
							 <input type='hidden' name='jum' value='<?=$jum?>'>
							 @foreach ($memberList as $key => $amb)												 													 	
							 	
								<tr>
									<td>{{$key + 1}}</td>                																										
									<td>{{ $amb->code }}</td>	                	                								
									<td>{{ $amb->namaMember }}</td>	                
									<td>{{ $amb->namaPaket }}</td>
	                <td>{{ mata_uang_help($amb->iuran) }}</td>	                													
									<td>																							
							 			<label class='form-check-label'>
	                    <input class='data-check' type='checkbox'
	                      name='chk_<?=$no?>' value='{{ $amb->id }}'> check
	                  </label>     
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
function pilih(id, iuran, member, paket){	
	$('#studentModal').modal('hide');
	$("#member_paket_id").val(id);
	$("#namaMember").val(member+' // '+paket);		
	$("#nominal").val(iuran);  
}
</script>
@endsection

