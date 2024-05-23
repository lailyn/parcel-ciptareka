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
						<a class="btn btn-warning btn-sm float-right" href="{{ route('setting.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									

					
						@php
						$row = $setting;
						@endphp
						<form class="mb-5" action="{{ route('setting.update') }}" enctype="multipart/form-data" method="POST">											
							<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						@foreach ($setting as $key => $row)																												
							<input type="hidden" name="id_<?=$key;?>" value="<?=$row->id;?>">											
							<div class="form-group row">
								<label class="col-sm-2 col-form-label" >{{ ucfirst($row->name) }}</label>
								<?php if($row->tipe!='file'){ ?>
								<div class="col-sm-10">																			
									<input type="{{ $row->tipe }}" class="form-control" name="name_<?=$row->name;?>" value="{{ $row->value }}" placeholder="{{ ucfirst($row->name) }}">									
								</div>
								<?php 
								}else{ 									
								?>
								<div class="col-sm-8">																			
									<input type="{{ $row->tipe }}" class="form-control" name="name_<?=$row->name;?>" value="{{ $row->value }}" placeholder="{{ ucfirst($row->name) }}">									
								</div>
								<div class="col-sm-2">																			
									<img width="30px" src="{{ asset('ima49es/'.get_setting($row->name)) }}">  
								</div>
								<?php } ?>
							</div>													
						@endforeach
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


