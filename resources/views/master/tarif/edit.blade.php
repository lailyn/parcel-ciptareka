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
				<div class="block-header block-header-default">
					<h3 class="block-title">
						<a class="btn btn-warning btn-sm float-right" href="{{ route('tarif.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									

					<?php 
					if($set=="insert"){ 
						$row = ""; ?>
						<form class="mb-5" action="{{ route('tarif.create') }}" method="POST">
					<?php
					}elseif($set=="edit"){
						$row = $tarif; ?>
						<form class="mb-5" action="{{ route('tarif.update',$row->id) }}" method="POST">
					<?php } ?>					
						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">													
							<label class="col-sm-2 col-form-label" >Jenis Tarif</label>
							<div class="col-sm-4">
								<select class="form-control" name="jenis_tarif_id" required>
									<option value="">- choose -</option>
									@foreach ($jenisTarif as $key => $dt)
										@php
										if($row && $dt->id==$row->jenis_tarif_id) $sel = 'selected';
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
									@endforeach
								</select>
							</div>
						</div>						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Asal</label>
							<div class="col-sm-10">
								<select class="js-select2 form-control" disabled name="asal" required>									
									<option>{{ $cariAsal = cariDaerah_helper($row->asal,'kec'); }}</option>
								</select>
							</div>
						</div>						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Tujuan</label>
							<div class="col-sm-10">
								<select class="js-select2 form-control" disabled name="tujuan" required>																		
									<option>{{ cariDaerah_helper($row->tujuan,'kec'); }}</option>
								</select>
							</div>
						</div>						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Cost</label>
							<div class="col-sm-3">
								<input type="text" data-type='currency' class="form-control" name="cost" value="<?=($row)?$row->cost:'';?>" placeholder="Cost">
							</div>						
							<label class="col-sm-1 col-form-label" >Cod</label>
							<div class="col-sm-2">
								<select class="form-control" name="cod">
									<option <?=($row&&$row->cod==1)?'selected':'';?> value="1">Ya</option>
									<option <?=($row&&$row->cod==0)?'selected':'';?> value="0">Tidak</option>
								</select>	
							</div>						
							<label class="col-sm-2 col-form-label" >Durasi (hari)</label>
							<div class="col-sm-2">
								<input type="number" class="form-control" name="durasi" value="<?=($row)?$row->durasi:'';?>" placeholder="Durasi">
							</div>						
						</div>
						<div class="form-group row">
							<div class="col-sm-10 ml-auto">
								<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
								<button type="reset" class="btn btn-default">Reset</button>
							</div>
						</div>
					</form>

				</div>			
			</div>
		</div>
	</main>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
$('.js-select2').select2({
  placeholder: 'Select an item',
  ajax: {
    url: '{{ route("pengiriman.dataAjax") }}',
    dataType: 'json',
    delay: 250,
    processResults: function (data) {
      return {
        results:  $.map(data, function (item) {
          return {
            text: item.text,
            id: item.id
          }
        })
      };
    },
    cache: true
  }
});
</script>
@endsection


