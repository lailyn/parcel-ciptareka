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
						$row="";$dis="";$vis=""; 						
						?>
						<form class="mb-5" action="{{ route('paket.create') }}" method="POST">
					<?php
					}elseif($set=="edit"){
						$row = $paket;$dis="";$vis="";
						?>
						<form class="mb-5" action="{{ route('paket.update',$row->id) }}" method="POST">
					<?php
					}elseif($set=="detail"){
						$row = $paket;
						$vis="d-none";$dis="readonly";
					} ?>					
						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">
							<label class="col-sm-2 col-form-label">Nama Paket</label>
							<div class="col-sm-3">
								<input <?=$dis?> type="text" class="form-control" name="name" value="<?=($row)?$row->name:'';?>" placeholder="Nama Paket">
							</div>
							<label class="col-sm-1 col-form-label">Jenis</label>
							<div class="col-sm-2">
								<select <?=$dis?> class="form-control" id="jenis_paket" name="jenis_paket" required>
									<option <?=(!$row || $row->jenis_paket=="")?'selected':'';?> value="">- choose -</option>
									<option <?=($row&&$row->jenis_paket=="tabungan")?'selected':'';?> value="tabungan">Tabungan</option>									
									<option <?=($row&&$row->jenis_paket=="paket")?'selected':'';?> value="paket">Paket</option>									
								</select>
							</div>
							<label class="col-sm-1 col-form-label">Periode</label>
							<div class="col-sm-2">		                   	                
                <select <?=$dis?> class="form-control select2" id="periode_id" name="periode_id" required>
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
								<input <?=$dis?> type="number" class="form-control" name="iuran" value="<?=($row)?$row->iuran:'';?>" placeholder="Iuran">
							</div>
							<label class="col-sm-2 col-form-label">Lama Iuran</label>
							<div class="col-sm-1">
								<input <?=$dis?> type="number" class="form-control" name="lama_iuran" value="<?=($row)?$row->lama_iuran:'';?>" placeholder="Lama Iuran">
							</div>							
							<div class="col-sm-2">
								<select <?=$dis?> class="form-control" id="jenis_lama" name="jenis_lama" required>
									<option <?=(!$row||$row->jenis_lama=="")?'selected':'';?> value="">- choose -</option>
									<option <?=($row&&$row->jenis_lama=="hari")?'selected':'';?> value="hari">Hari</option>									
									<option <?=($row&&$row->jenis_lama=="minggu")?'selected':'';?> value="minggu">Minggu</option>									
								</select>
							</div>
						</div>	
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Deskripsi</label>
							<div class="col-sm-10">
								<input <?=$dis?> type="text" class="form-control" value="<?=($row)?$row->deskripsi:'';?>" name="deskripsi">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-sm-2 col-form-label" >Tgl Mulai</label>
							<div class="col-sm-2">
								<input <?=$dis?> type="date" class="form-control" required value="<?=($row)?$row->tgl_awal:'';?>" name="tgl_awal">
							</div>
							<label class="col-sm-1 col-form-label" >Tgl Akhir</label>
							<div class="col-sm-2">
								<input <?=$dis?> type="date" class="form-control" required value="<?=($row)?$row->tgl_akhir:'';?>" name="tgl_akhir">
							</div>
						</div>			
						<div class="form-group row">
							<div class="col-sm-2"></div>
							<div class="col-sm-4 <?=$vis?>">
								<div class="custom-control custom-switch mb-1">
	                <input type="checkbox" class="custom-control-input" id="status" value='1' name="status" <?=($row&&$row->status==1)?'checked':'';?>>
	                <label class="custom-control-label" for="status">Aktif</label>
	            	</div>					
	            </div>
	          </div>		
						<div class="form-group row <?=$vis?>">
							<div class="col-sm-10 ml-auto">
								<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
								<button type="reset" class="btn btn-default">Reset</button>
							</div>
						</div>
					</form>
					<hr>

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


				</div>			
			</div>
		</div>
	</main>
@endsection


