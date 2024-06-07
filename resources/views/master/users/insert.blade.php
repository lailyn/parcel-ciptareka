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
						<a class="btn btn-warning btn-sm float-right" href="{{ route('users.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									

					<?php 
					if($set=="insert"){ 
						$row = ""; ?>
						<form class="mb-5" action="{{ route('users.create') }}" method="POST">
					<?php
					}elseif($set=="edit"){
						$row = $user; ?>
						<form class="mb-5" action="{{ route('users.update',$row->id) }}" method="POST">
					<?php } ?>					
						<input type="hidden" name="_token" value="{{ csrf_token() }}">		
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Nama</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="name" value="<?=($row)?$row->name:'';?>" placeholder="Nama">
							</div>
							<label class="col-sm-2 col-form-label">User Type</label>
							<div class="col-sm-4">
								<select class="js-select2 form-control" name="id_user_type" required>
									<option value="">- choose -</option>
									@foreach ($userType as $key => $dt)
										@php
										if($row && $dt->id==$row->id_user_type) $sel = 'selected';
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
									@endforeach
								</select>
							</div>							
						</div>					
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Email/No HP</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="email" value="<?=($row)?$row->email:'';?>" placeholder="Email">
							</div>
							<label class="col-sm-2 col-form-label">Password</label>
							<div class="col-sm-4">
								<input type="password" class="form-control" name="password" value="" placeholder="<?=($row)?'Kosongkan jika tidak diubah':'Password';?>">
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


