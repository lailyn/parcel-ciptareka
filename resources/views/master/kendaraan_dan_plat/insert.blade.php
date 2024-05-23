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
						<a class="btn btn-warning btn-sm float-right" href="{{ route('kendaraan_dan_plat.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									

					<?php 
					if($set=="insert"){ 
						$row = ""; ?>
						<form class="mb-5" action="{{ route('kendaraan_dan_plat.create') }}" method="POST">
					<?php
					}elseif($set=="edit"){
						$row = $kendaraan_dan_plat; ?>
						<form class="mb-5" action="{{ route('kendaraan_dan_plat.update',$row->id) }}" method="POST">
					<?php } ?>					
						<input type="hidden" name="_token" value="{{ csrf_token() }}">	
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Nama</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="name" value="<?=($row)?$row->name:'';?>" placeholder="Nama">
							</div>
						</div>							
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Jenis Kendaraan</label>
							<div class="col-sm-4">
								<select class="js-select2 form-control" name="jenis_kendaraan_id" required>
									<option value="">- choose -</option>
									@foreach ($jenisKendaraan as $key => $dt)
										@php
										if($row && $dt->id==$row->jenis_kendaraan_id) $sel = 'selected';
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
									@endforeach
								</select>
							</div>
							<label class="col-sm-2 col-form-label">No Plat</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="no_plat" value="<?=($row)?$row->no_plat:'';?>" placeholder="No Plat">
							</div>
						</div>						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Deskripsi</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="deskripsi" value="<?=($row)?$row->deskripsi:'';?>" placeholder="Deskripsi">
							</div>
						</div>					
						<div class="form-group row">
							<div class="col-sm-2"></div>
							<div class="col-sm-4">
								<div class="custom-control custom-switch mb-1">
	                <input type="checkbox" class="custom-control-input" id="status" value='1' name="status" <?=($row&&$row->status==1)?'checked':'';?>>
	                <label class="custom-control-label" for="status">Aktif</label>
	            	</div>					
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


