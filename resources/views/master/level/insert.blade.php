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
						<a class="btn btn-warning btn-sm float-right" href="{{ route('level.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									

					<?php 
					if($set=="insert"){ 
						$row = ""; ?>
						<form class="mb-5" action="{{ route('level.create') }}" method="POST">
					<?php
					}elseif($set=="edit"){
						$row = $level; ?>
						<form class="mb-5" action="{{ route('level.update',$row->id) }}" method="POST">
					<?php } ?>					
						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Level</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="name" value="<?=($row)?$row->name:'';?>" placeholder="Level">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Persen Parcel</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="persen_parcel" value="<?=($row)?$row->persen_parcel:'';?>" placeholder="Persen Parcel">
							</div>						
							<label class="col-sm-2 col-form-label">Persen THR</label>
							<div class="col-sm-4">
								<input type="text" required class="form-control" name="persen_thr" value="<?=($row)?$row->persen_thr:'';?>" placeholder="Persen THR">
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


