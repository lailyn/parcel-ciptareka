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
						<a class="btn btn-warning btn-sm float-right" href="{{ route('member.index') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">									

					<?php 
					if($set=="insert"){ 
						$row="";$dis="";$vis=""; ?>
						<form class="mb-5" action="{{ route('member.create') }}" method="POST" enctype="multipart/form-data">
					<?php
					}elseif($set=="edit"){
						$row = $member;$dis="";$vis=""; ?>
						<form class="mb-5" action="{{ route('member.update',$row->id) }}" method="POST" enctype="multipart/form-data">
					<?php
					}elseif($set=="detail"){
						$row = $member;
						$vis="d-none";$dis="readonly";
					} ?>										
						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Nama</label>
							<div class="col-sm-6">
								<input <?=$dis?> type="text" class="form-control" name="name" value="<?=($row)?$row->name:old('name');?>" placeholder="Nama">
							</div>																			
							<label class="col-sm-2 col-form-label">Kode</label>
							<div class="col-sm-2">
								<input <?=$dis?> type="text" class="form-control" name="kode" readonly value="<?=($row)?$row->code:'Auto';?>" placeholder="Kode">								
							</div>				
						</div>						
											
						<div class="form-group row">			
							<label class="col-sm-2 col-form-label">No KTP</label>
							<div class="col-sm-4">
								<input <?=$dis?> type="number" required class="form-control" name="no_ktp" value="<?=($row)?$row->no_ktp:old('no_ktp');?>" placeholder="No KTP">
							</div>													
							<label class="col-sm-2 col-form-label">No HP</label>
							<div class="col-sm-4">
								<input <?=$dis?> type="text" class="form-control" name="no_hp" value="<?=($row)?$row->no_hp:old('no_hp');?>" placeholder="No HP">
							</div>
						</div>						
						
					</form>

				</div>

				<hr>

				<div class="block-content block-content-full">									
					<table class="table table-bordered">
						<thead>
							<tr>
								<th width="30%">Paket</th>
								<th width="10%">Jumlah</th>
								<th>Metode Bayar</th>
								<th>Periode Bayar</th>
								<th>Keterangan</th>
								<th width="10%">Status</th>
								<th width="5%">#</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$dt_detail = DB::table("member_paket")
									->join("member","member_paket.member_id","=",'member.id')
									->join("paket","member_paket.paket_id","=",'paket.id')
									->where("member_paket.member_id",$row->id)
									->get(['paket.name AS paket','member_paket.*']); 
							foreach ($dt_detail as $key => $value) {
								if($value->status==1) $status = "<label class='badge badge-success'>aktif</label>";
									else $status = "<label class='badge badge-danger'>non-aktif</label>";
								echo "
								<tr>
									<td>$value->paket</td>
									<td>$value->jumlah</td>
									<td>$value->metode_pembayaran</td>
									<td>$value->periode_bayar</td>
									<td>$value->keterangan</td>
									<td>$status</td>										
									<td>"; ?>
										<a onclick="return confirm('Anda yakin?')" href="{{ route('member.deleteDetail',[$value->id,$row->id]) }}" class='btn btn-sm btn-danger'><i class='fa fa-trash'></i> del</a>
									</td>
								</tr>
							<?php
							}
							?>
							<tr>
								<td>
									<form action="{{ route('member.saveDetail',$row->id) }}" method="POST">										
									@csrf
									<select class="form-control select2" name="paket_id" required>
										<option value="">- choose -</option>
										<?php 
										$db = DB::table("paket")->where("status",1)->orderBy("name","ASC")->get();
										foreach ($db as $key => $value) {
											echo "<option value='$value->id'>$value->name</option>";
										}
										?>
									</select>
								</td>
								<td>
									<input type="number" value="1" class="form-control" name="jumlah">
								</td>
								<td>
									<select class="form-control" name="metode_pembayaran">
										<option value="cash">cash</option>
										<option value="transfer">transfer</option>
									</select>
								</td>
								<td>
									<select class="form-control" name="periode_bayar">
										<option value="harian">Harian</option>
										<option value="mingguan">Mingguan</option>
										<option value="bulanan">Bulanan</option>
									</select>
								</td>
								<td>
									<input type="text" class="form-control" name="keterangan">
								</td>
								<td>
									<select class="form-control" name="status">
										<option value="1">aktif</option>
										<option value="0">non-aktif</option>
									</select>
								</td>
								<td>
									<button type="submit" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> add</button>
								</td>
								</form>
							</tr>
						</tbody>
					</table>
				</div>

							
			</div>
		</div>
	</main>
@endsection


