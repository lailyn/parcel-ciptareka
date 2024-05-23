@extends('layout.template_dash')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
						<a class="btn btn-danger btn-sm float-right" href="{{ route('kelurahan.insert') }}"> <i class="fa fa-plus"></i> Add Item</a>
					</h3>
				</div>
				<div class="block-content block-content-full">
					
					<table class="table table-bordered table-striped yajra-datatable">
						<thead>
							<tr>
								<th class="text-center" style="width: 80px;">No</th>
								<th>Kelurahan</th>
								<th>Kecamatan</th>
								<th style="width: 15%;">Action</th>
							</tr>
						</thead>
						<tbody>								
						</tbody>
					</table>
				</div>			
			</div>
	</main>
@endsection
@include('layout.footer')
<script type="text/javascript">
  $(function () {
	
	var table = $('.yajra-datatable').DataTable({
		processing: true,
		serverSide: true,
		ajax: "{{ route('kelurahan.list') }}",
		columns: [
			{data: 'DT_RowIndex', name: 'DT_RowIndex'},
			{data: 'village', name: 'village'},
			{data: 'subdistrict', name: 'subdistrict'},                        
			{
				data: 'action', 
				name: 'action', 
				orderable: true, 
				searchable: true
			},
		]
	});
	
  });
</script>
