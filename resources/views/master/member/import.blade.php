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
						<a class="btn btn-warning btn-sm float-right ml-2" href="{{ route('member.download') }}"> <i class="fa fa-download"></i> Download Template</a>
						<a class="btn btn-warning btn-sm float-right" href="{{ route('member.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									
					
					<form class="mb-5" action="{{ route('member.importPost') }}" method="POST" enctype="multipart/form-data">				
						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >File Import</label>
							<div class="col-sm-6">
								<input type="file" required class="form-control" name="fileImport" placeholder="File">
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


