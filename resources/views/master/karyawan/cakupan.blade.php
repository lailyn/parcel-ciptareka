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
					
					<form class="mb-5" action="{{ route('karyawan.saveCakupan') }}" method="POST" enctype="multipart/form-data">								
						<input type="hidden" name="_token" value="{{ csrf_token() }}">																	
						<input type="hidden" name="karyawan_id" value="{{ $karyawan_id }}">																	
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Cakupan Kecamatan</label>
							<div class="col-sm-10">
								<select class="js-select2 form-control" name="kecamatan_id" required>									
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
				<div class="block-content block-content-full">					
					<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
					<table class="table table-responsive table-bordered table-striped table-vcenter js-dataTable-buttons">
						<thead>
							<tr>
								<th class="text-center" style="width: 80px;">ID</th>
								<th>Kecamatan</th>								
								<th style="width: 10%;">Action</th>
							</tr>
						</thead>
						<tbody>
						
						@foreach($cakupan AS $key => $dt)
							@php
							$kecamatan = cariDaerah_helper($dt->kecamatan_id,'kec');								
							@endphp
							<tr>
								<td>{{ $key+1 }}</td>
								<td>{{ $kecamatan }}</td>
								<td>
									<a onclick="return confirm('Anda yakin ingin hapus?')" class="btn btn-danger btn-sm" href="{{ route('karyawan.delCakupan',$dt->id) }}"><i class="fa fa-trash"></i> Hapus</a>
								</td>
							</tr>

						@endforeach
						</tbody>
					</table>
				</div>		
			</div>
		</div>
	</main>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript">
$('.js-select2').select2({
  placeholder: 'Select an item',
  ajax: {
    url: '{{ route("pengiriman.dataAjax") }}',
    dataType: 'json',
    delay: 250,
    processResults: function (data) {
      return {
        results:  $.map(data, function (item) {
          return {
            text: item.text,
            id: item.id
          }
        })
      };
    },
    cache: true
  }
});
</script>
@endsection


