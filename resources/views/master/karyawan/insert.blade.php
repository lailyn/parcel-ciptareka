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
						<a class="btn btn-warning btn-sm float-right" href="{{ route('karyawan.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									

					<?php 
					if($set=="insert"){ 
						$row = ""; ?>
						<form class="mb-5" action="{{ route('karyawan.create') }}" method="POST" enctype="multipart/form-data">
					<?php
					}elseif($set=="edit"){
						$row = $karyawan; ?>
						<form class="mb-5" action="{{ route('karyawan.update',$row->id) }}" method="POST" enctype="multipart/form-data">
					<?php } ?>					
						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Nama</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" name="name" value="<?=($row)?$row->name:old('name');?>" placeholder="Nama">
							</div>
							<label class="col-sm-1 col-form-label">Port</label>
							<div class="col-sm-3">
								<select class="js-select2 form-control" name="cabang_id" required>
									<option value="">- choose -</option>
									@foreach ($cabang as $key => $dt)
										@php
										$cabang_id = ($row)?$row->cabang_id:old('cabang_id');
										if($cabang_id==$dt->id) $sel = 'selected';
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
									@endforeach
								</select>							
							</div>
						</div>						
						<div class="form-group row">							
							<label class="col-sm-2 col-form-label">Jabatan</label>
							<div class="col-sm-4">
								<select class="js-select2 form-control" name="jabatan_id" required>
									<option value="">- choose -</option>
									@foreach ($jabatanKaryawan as $key => $dt)
										@php
										$jabatan_id = ($row)?$row->jabatan_id:old('jabatan_id');
										if($jabatan_id==$dt->id) $sel = 'selected';										
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
									@endforeach
								</select>							
							</div>
							<label class="col-sm-2 col-form-label">Departemen</label>
							<div class="col-sm-4">
								<select class="js-select2 form-control" name="departemen_id" required>
									<option value="">- choose -</option>
									@foreach ($departemen as $key => $dt)
										@php
										$departemen_id = ($row)?$row->departemen_id:old('departemen_id');
										if($departemen_id==$dt->id) $sel = 'selected';																				
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
									@endforeach
								</select>
							</div>
						</div>						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Jenis Kelamin</label>
							<div class="col-sm-2">
								<select class="form-control" name="jk">
									<option value="">- choose -</option>
									<option <?=($row&&$row->jk==1)?'selected':'';?> value="1">Laki-laki</option>
									<option <?=($row&&$row->jk==2)?'selected':'';?> value="2">Perempuan</option>
								</select>
							</div>
							<label class="col-sm-1 col-form-label">Agama</label>
							<div class="col-sm-1">
								<select class="form-control" name="agama">
									<option value="">- choose -</option>
									<option <?=($row&&$row->agama=='Islam')?'selected':'';?>>Islam</option>
									<option <?=($row&&$row->agama=='Katolik')?'selected':'';?>>Katolik</option>
									<option <?=($row&&$row->agama=='Protestan')?'selected':'';?>>Protestan</option>
									<option <?=($row&&$row->agama=='Hindu')?'selected':'';?>>Hindu</option>
									<option <?=($row&&$row->agama=='Buddha')?'selected':'';?>>Buddha</option>
									<option <?=($row&&$row->agama=='Kong Hu Chu')?'selected':'';?>>Kong Hu Chu</option>
								</select>
							</div>
							<label class="col-sm-2 col-form-label">NIP</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="nip" value="<?=($row)?$row->nip:old('nip');?>" placeholder="NIP">
							</div>
						</div>					
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Email</label>
							<div class="col-sm-4">
								<input type="email" required class="form-control" name="email" value="<?=($row)?$row->email:old('email');?>" placeholder="Email">
							</div>
							<label class="col-sm-2 col-form-label">Outlet</label>
							<div class="col-sm-4">
								<select class="js-select2 form-control" name="outlet_id">
									<option value="">- choose -</option>
									@foreach ($outlet as $key => $dt)
										@php
										$outlet_id = ($row)?$row->outlet_id:old('outlet_id');
										if($outlet_id==$dt->id) $sel = 'selected';																														
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">No KTP</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="no_ktp" value="<?=($row)?$row->no_ktp:old('no_ktp');?>" placeholder="No KTP">
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


