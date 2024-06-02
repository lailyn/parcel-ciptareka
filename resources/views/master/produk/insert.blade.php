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

    {!! cekError($errors) !!}    

		<div class="content">
			<div class="block">
				<div class="block-header block-header-default">
					<h3 class="block-title">
						<a class="btn btn-warning btn-sm float-right" href="{{ route('produk.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									

					<?php 
					if($set=="insert"){ 
						$row = ""; ?>
						<form class="mb-5" action="{{ route('produk.create') }}" method="POST" enctype="multipart/form-data">
					<?php
					}elseif($set=="edit"){
						$row = $produk; ?>
						<form class="mb-5" action="{{ route('produk.update',$row->id) }}" method="POST" enctype="multipart/form-data">
					<?php } ?>					
						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Nama Barang</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="name" value="<?=($row)?$row->name:'';?>" placeholder="Nama Barang">
							</div>
							<label class="col-sm-2 col-form-label">Harga</label>
							<div class="col-sm-4">
								<input type="number" class="form-control" name="harga_harian" value="<?=($row)?$row->harga_harian:'';?>" placeholder="Harga Harian">								
							</div>
						</div>						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Keterangan</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="keterangan" value="<?=($row)?$row->keterangan:'';?>" placeholder="Keterangan">
							</div>
						</div>				
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Satuan</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="satuan" value="<?=($row)?$row->satuan:'';?>" placeholder="Satuan">
							</div>
						</div>				
						<!-- <div class="form-group row">
							<label class="col-sm-2 col-form-label" >Tgl Berlaku</label>
							<div class="col-sm-2">
								<input type="date" class="form-control" name="tgl_berlaku">
							</div>
							<div class="col-sm-2">
								<input type="date" class="form-control" name="tgl_berakhir">
							</div>
						</div>									 -->
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


