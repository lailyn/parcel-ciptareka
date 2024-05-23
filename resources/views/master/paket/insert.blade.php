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
						<a class="btn btn-warning btn-sm float-right" href="{{ route('paket.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									

					<?php 
					if($set=="insert"){ 
						$row = ""; ?>
						<form class="mb-5" action="{{ route('paket.create') }}" method="POST">
					<?php
					}elseif($set=="edit"){
						$row = $paket; ?>
						<form class="mb-5" action="{{ route('paket.update',$row->id) }}" method="POST">
					<?php } ?>					
						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Nama Paket</label>
							<div class="col-sm-3">
								<input type="text" class="form-control" name="name" value="<?=($row)?$row->name:'';?>" placeholder="Nama Paket">
							</div>
							<label class="col-sm-1 col-form-label">Jenis</label>
							<div class="col-sm-2">
								<select class="form-control" id="jenis_paket" name="jenis_paket" required>
									<option <?=(!$row || $row->jenis_paket=="")?'selected':'';?> value="">- choose -</option>
									<option <?=($row&&$row->jenis_paket=="tabungan")?'selected':'';?> value="tabungan">Tabungan</option>									
									<option <?=($row&&$row->jenis_paket=="paket")?'selected':'';?> value="paket">Paket</option>									
								</select>
							</div>
							<label class="col-sm-1 col-form-label">Periode</label>
							<div class="col-sm-2">		                   	                
                <select class="form-control select2" id="periode_id" name="periode_id" required>
									<option value="">- choose -</option>
									@foreach ($periode as $key => $dt)
										@php
										if($row && $dt->id==$row->periode_id) $sel = 'selected';
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
									@endforeach
								</select>
              </div>
						</div>						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Iuran</label>
							<div class="col-sm-3">
								<input type="number" class="form-control" name="iuran" value="<?=($row)?$row->iuran:'';?>" placeholder="Iuran">
							</div>
							<label class="col-sm-2 col-form-label">Lama Iuran</label>
							<div class="col-sm-1">
								<input type="number" class="form-control" name="lama_iuran" value="<?=($row)?$row->lama_iuran:'';?>" placeholder="Lama Iuran">
							</div>							
							<div class="col-sm-2">
								<select class="form-control" id="jenis_lama" name="jenis_lama" required>
									<option <?=(!$row||$row->jenis_lama=="")?'selected':'';?> value="">- choose -</option>
									<option <?=($row&&$row->jenis_lama=="hari")?'selected':'';?> value="hari">Hari</option>									
									<option <?=($row&&$row->jenis_lama=="minggu")?'selected':'';?> value="minggu">Minggu</option>									
								</select>
							</div>
						</div>	
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Tgl Mulai</label>
							<div class="col-sm-2">
								<input type="date" class="form-control" required name="tgl_awal">
							</div>
							<label class="col-sm-1 col-form-label" >Tgl Akhir</label>
							<div class="col-sm-2">
								<input type="date" class="form-control" required name="tgl_akhir">
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


