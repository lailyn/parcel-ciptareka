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
						<a class="btn btn-warning btn-sm float-right" href="{{ route('rekonsiliasi.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									

					<?php 
					if($set=="insert"){ 
						$row = ""; ?>
						<form class="mb-5" action="{{ route('rekonsiliasi.create') }}" method="POST" enctype="multipart/form-data">
					<?php
					}elseif($set=="edit"){
						$row = $setoranManajemen; ?>
						<form class="mb-5" action="{{ route('rekonsiliasi.update',$row->id) }}" method="POST" enctype="multipart/form-data">
					<?php } ?>

						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">							
							<label class="col-sm-2 col-form-label">Kode</label>
							<div class="col-sm-2">	
								<input type="text" id="kode" class="form-control" name="kode" readonly value="<?=($row)?$row->code:'Auto';?>" placeholder="Kode">								
							</div>																	
							<label class="col-sm-2 col-form-label">Tgl Mulai</label>
							<div class="col-sm-2">
								<input type="date" id="tgl_mulai" required class="form-control" name="tgl_mulai" value="<?=($row)?$row->tgl_mulai:date("Y-m-d");?>" placeholder="Tgl Mulai">
							</div>																				
							<label class="col-sm-2 col-form-label">Tgl Selesai</label>
							<div class="col-sm-2">
								<input type="date" id="tgl_selesai" required class="form-control" name="tgl_selesai" value="<?=($row)?$row->tgl_selesai:date("Y-m-d");?>" placeholder="Tgl Selesai">
							</div>																				
	          </div>
	          <div class="form-group row">										
							<label class="col-sm-2 col-form-label">Keterangan</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="keterangan" value="<?=($row)?$row->keterangan:old('keterangan');?>" placeholder="Keterangan">
							</div>							
							<label class="col-sm-2 col-form-label">Periode</label>
							<div class="col-sm-2">
								<select readonly class="form-control select2" id="periode" name="periode" required>
									<option value="">- choose -</option>
									@foreach ($periode as $key => $dt)
										@php									
										if(!$row && $dt->status==1){
											$sel = "selected";
										}elseif($row && $dt->id==$row->periode){ 
											$sel = 'selected';
										}else{ 
											$sel = "";
										}
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
									@endforeach
								</select>
							</div>																										
	          </div>
	          <div class="form-group row">
							<div class="col-sm-10 ml-auto">
								<button type="button" onclick="generate()" class="btn btn-warning"><i class="fa fa-compass"></i> Generate</button>								
								<button type="reset" class="btn btn-default"> Reset</button>								
							</div>
						</div>
	          <hr>
	          <div id="tabelKan">
							<div id="tabeldata" class="table-responsive"></div>
						
							<!-- <div class="form-group row">
								<div class="col-sm-10 ml-auto">
									<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
									<button type="button" onclick="window.location.reload()" class="btn btn-default">Reset</button>
								</div>
							</div> -->
						</div>
					</form>					

				</div>			
			</div>
		</div>
	</main>

<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function() {  
  var element = document.getElementById("tabelKan");
  if (element) element.style.display = 'none';  
});
document.addEventListener('DOMContentLoaded', function () {
  const select = document.getElementById('periode');
  const readonlyValue = select.value;

  select.addEventListener('change', function () {
      if (select.value !== readonlyValue) {
          select.value = readonlyValue;
      }
  });
});

function generate(){	
	let periode = $("#periode").val();
	let tgl_mulai = $("#tgl_mulai").val();
	let tgl_selesai = $("#tgl_selesai").val();		
	
	$.ajax({
    url : "{{ route('rekonsiliasi.tampilData') }}",
    type:'POST',
    data: {
      _token: '{!! csrf_token() !!}',        
      periode: periode, 
      tgl_mulai: tgl_mulai, 
      tgl_selesai: tgl_selesai, 
      mode: 'insert',               
    },
    success: function(data) {
      $('#tabelKan').show();
      $('#tabeldata').html(data);
    }
  });
}

</script>
@endsection

