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
								<input type="text" class="form-control" name="name" value="<?=($row)?$row->name:old('name');?>" placeholder="Nama">
							</div>																			
							<label class="col-sm-2 col-form-label">Kode</label>
							<div class="col-sm-2">
								<input type="text" class="form-control" name="kode" readonly value="<?=($row)?$row->code:'Auto';?>" placeholder="Kode">								
							</div>				
						</div>						
											
						<div class="form-group row">			
							<label class="col-sm-2 col-form-label">No KTP</label>
							<div class="col-sm-4">
								<input type="number" required class="form-control" name="no_ktp" value="<?=($row)?$row->no_ktp:old('no_ktp');?>" placeholder="No KTP">
							</div>													
							<label class="col-sm-2 col-form-label">No HP</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="no_hp" value="<?=($row)?$row->no_hp:old('no_hp');?>" placeholder="No HP">
							</div>
						</div>						
						<div class="form-group row">			
							<label class="col-sm-2 col-form-label">Tgl Lahir</label>
							<div class="col-sm-4">
								<input type="date" required class="form-control" name="tgl_lahir" value="<?=($row)?$row->tgl_lahir:old('tgl_lahir');?>" placeholder="Tgl Lahir">
							</div>													
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Alamat</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="alamat" value="<?=($row)?$row->alamat:old('alamat');?>" placeholder="Alamat">
							</div>
						</div>					
						<div class="form-group row">			
							<label class="col-sm-2 col-form-label">Kota</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="kota" value="<?=($row)?$row->kota:old('kota');?>" placeholder="Kota">
							</div>													
							<label class="col-sm-2 col-form-label">Kecamatan</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="kecamatan" value="<?=($row)?$row->kecamatan:old('kecamatan');?>" placeholder="Kecamatan">
							</div>
						</div>		
						<div class="form-group row">			
							<label class="col-sm-2 col-form-label">Kodepos</label>
							<div class="col-sm-4">
								<input type="number" class="form-control" name="kodepos" value="<?=($row)?$row->kodepos:old('kodepos');?>" placeholder="Kodepos">
							</div>													
							<label class="col-sm-2 col-form-label">Akun IG</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="akun_instagram" value="<?=($row)?$row->akun_instagram:old('akun_instagram');?>" placeholder="Akun Instagram">
							</div>													
						</div>
						<div class="form-group row">			
							<label class="col-sm-2 col-form-label">Akun FB</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="akun_fb" value="<?=($row)?$row->akun_fb:old('akun_fb');?>" placeholder="Akun FB">
							</div>													
							<label class="col-sm-2 col-form-label">Akun Tiktok</label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="akun_tiktok" value="<?=($row)?$row->akun_tiktok:old('akun_tiktok');?>" placeholder="Akun Tiktok">
							</div>													
						</div>				
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Foto</label>
							<div class="col-sm-4">
								<input type="file" class="form-control" name="foto">
							</div>
						</div>	
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">ID Partnership</label>
							<div class="col-sm-4">
								<select class="form-control select2" name="partnership_id">
									<option value="">- choose -</option>
									<?php 
									$db = DB::table("partnership")->orderBy("name","ASC")->get();
									foreach ($db as $key => $value) {
										echo "<option value='$value->id'>$value->name</option>";
									}
									?>
								</select>
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

					<hr>

					<?php if($set=="detail"){ ?>
					<div class="block-content block-content-full">									
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Produk</th>
									<th width="10%">Qty</th>
									<th width="5%">#</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$dt_detail = DB::table("paket_detail")->where("paket_detail.paket_id",$row->id)->join("produk","paket_detail.produk_id","=","produk.id")->get(['paket_detail.*','produk.name AS nama_produk']); 
								foreach ($dt_detail as $key => $value) {
									echo "
									<tr>
										<td>$value->nama_produk</td>
										<td>$value->jumlah</td>
										<td>"; ?>
											<a onclick="return confirm('Anda yakin?')" href="{{ route('paket.deleteDetail',[$value->id,$row->id]) }}" class='btn btn-sm btn-danger'><i class='fa fa-trash'></i> del</a>
										</td>
									</tr>
								<?php
								}
								?>
								<tr>
									<td>
										<form action="{{ route('paket.saveDetail',$row->id) }}" method="POST">										
										@csrf
										<select class="form-control select2" name="produk_id" required>
											<option value="">- choose -</option>
											<?php 
											$db = DB::table("produk")->orderBy("name","ASC")->get();
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
										<button type="submit" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> add</button>
									</td>
									</form>
								</tr>
							</tbody>
						</table>
					</div>
					<?php } ?>

				</div>			
			</div>
		</div>
	</main>
@endsection


