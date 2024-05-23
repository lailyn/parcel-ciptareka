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
		
			<div class="block">
				<div class="block-header block-header-default">
					<h3 class="block-title">
						<a class="btn btn-warning btn-sm float-right" href="{{ route('kecamatan.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									

					<?php 
					if($set=="insert"){ 
						$row = ""; ?>
						<form class="mb-5" action="{{ route('kecamatan.create') }}" method="POST">
					<?php
					}elseif($set=="edit"){
						$row = $kecamatan; ?>
						<form class="mb-5" action="{{ route('kecamatan.update',$row->id_subdistrict) }}" method="POST">
					<?php } ?>					
						<input type="hidden" name="_token" value="{{ csrf_token() }}">										
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >ID Kecamatan</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="id_subdistrict" value="<?=($row)?$row->id_subdistrict:'';?>" placeholder="ID Kecamatan">
							</div>						
							<label class="col-sm-2 col-form-label" >Kecamatan</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="subdistrict" value="<?=($row)?$row->subdistrict:'';?>" placeholder="Kecamatan">
							</div>
						</div>						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Kab/Kota</label>
							<div class="col-sm-4">
								<select class="js-select2 form-control" name="id_cities" required>
									<option value="">- choose -</option>
									@foreach ($cities as $key => $dt)
										@php
										if($row && $dt->id_cities==$row->id_cities) $sel = 'selected';
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id_cities }}">{{ $dt->cities }} </option>
									@endforeach
								</select>
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
@endsection


