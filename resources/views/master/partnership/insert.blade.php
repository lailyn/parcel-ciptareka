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
						<a class="btn btn-warning btn-sm float-right" href="{{ route('partnership.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									

					<?php 
					if($set=="insert"){ 
						$row = ""; ?>
						<form class="mb-5" action="{{ route('partnership.create') }}" method="POST" enctype="multipart/form-data">
					<?php
					}elseif($set=="edit"){
						$row = $partnership; ?>
						<form class="mb-5" action="{{ route('partnership.update',$row->id) }}" method="POST" enctype="multipart/form-data">
					<?php } ?>

						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Nama</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="name" value="<?=($row)?$row->name:old('name');?>" placeholder="Nama">
							</div>																			
							<label class="col-sm-2 col-form-label">Kode</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" name="kode" readonly value="<?=($row)?$row->code:'Auto';?>" placeholder="Kode">								
							</div>				
						</div>						
											
						<div class="form-group row">			
							<label class="col-sm-2 col-form-label">No KTP</label>
							<div class="col-sm-4">
								<input type="number" required class="form-control" name="no_ktp" value="<?=($row)?$row->no_ktp:old('no_ktp');?>" placeholder="No KTP">
							</div>													
							<label class="col-sm-2 col-form-label">No HP</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="no_hp" value="<?=($row)?$row->no_hp:old('no_hp');?>" placeholder="No HP">
							</div>
						</div>						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Alamat</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="alamat" value="<?=($row)?$row->alamat:old('alamat');?>" placeholder="Alamat">
							</div>
						</div>					
						<div class="form-group row">			
							<label class="col-sm-2 col-form-label">Kota</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="kota" value="<?=($row)?$row->kota:old('kota');?>" placeholder="Kota">
							</div>													
							<label class="col-sm-2 col-form-label">Kecamatan</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="kecamatan" value="<?=($row)?$row->kecamatan:old('kecamatan');?>" placeholder="Kecamatan">
							</div>
						</div>		
						<div class="form-group row">			
							<label class="col-sm-2 col-form-label">Kodepos</label>
							<div class="col-sm-4">
								<input type="number" class="form-control" name="kodepos" value="<?=($row)?$row->kodepos:old('kodepos');?>" placeholder="Kodepos">
							</div>													
							<label class="col-sm-2 col-form-label">Akun IG</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="akun_instagram" value="<?=($row)?$row->akun_instagram:old('akun_instagram');?>" placeholder="Akun Instagram">
							</div>													
						</div>
						<div class="form-group row">			
							<label class="col-sm-2 col-form-label">Akun FB</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="akun_fb" value="<?=($row)?$row->akun_fb:old('akun_fb');?>" placeholder="Akun FB">
							</div>													
							<label class="col-sm-2 col-form-label">Akun Tiktok</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="akun_tiktok" value="<?=($row)?$row->akun_tiktok:old('akun_tiktok');?>" placeholder="Akun Tiktok">
							</div>													
						</div>				
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Foto</label>
							<div class="col-sm-4">
								<input type="file" class="form-control" name="foto">
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


