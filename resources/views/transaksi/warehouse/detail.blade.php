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
		<!-- END Hero -->
		<div class="content">
			<?php if (session()->has('msg')) { ?>
				{!! session()->get('msg') !!}                          
			<?php session()->forget('msg'); } ?>			
			<div class="block">			
				<div class="block-header block-header-default">
					<h3 class="block-title">
						<a class="btn btn-warning btn-sm float-right" href="{{ route('warehouse.insert') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>
				<div class="block-content block-content-full">														
					<?php $dt = $manifest->first(); ?>					
					<form class="mb-5" action="{{ route('warehouse.create') }}" enctype="multipart/form-data" method="POST">										
						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">														
							<label class="col-sm-2 col-form-label text-right">No Manifest</label>
							<div class="col-sm-2">
								<input readonly type="text" readonly class="form-control" name="no_manifest" placeholder="Auto" value="{{ $dt->code }}">
							</div>								
							<label class="col-sm-2 text-right col-form-label" >Tgl Manifest</label>
							<div class="col-sm-2">
								<input readonly type="date" class="form-control" name="tgl_manifest" value="{{ $dt->tgl_manifest }}" placeholder="Tanggal Manifest">
							</div>																			
							<label class="col-sm-2 text-right col-form-label" >Estimasi (hari)</label>
							<div class="col-sm-1">
								<input readonly type="number" name="estimasi" class="form-control" value="{{ $dt->estimasi }}">	
							</div>	
						</div>
						<div class="form-group row">																														
							<label class="col-sm-2 col-form-label text-right">Port Asal</label>
							<div class="col-sm-3">
								<?php  
								$cekPortAsal = DB::table("cabang")->where("id",$dt->port_asal);
								$portAsal = ($cekPortAsal->count()>0)?$cekPortAsal->first()->name:'';
								$cekPortTujuan = DB::table("cabang")->where("id",$dt->port_tujuan);
								$portTujuan = ($cekPortTujuan->count()>0)?$cekPortTujuan->first()->name:'';
								?>
								<input type="hidden" name="port_id"  value="{{ $dt->port_tujuan }}">									
								<input readonly type="text" name="asal" class="form-control" value="{{ $portAsal }}">									
							</div>													
							<label class="col-sm-2 col-form-label text-right">Port Tujuan</label>
							<div class="col-sm-3">
								<input readonly type="text" name="tujuan" class="form-control" value="{{ $portTujuan }}">									
							</div>																		
						</div>
						<div class="form-group row">								
							<label class="col-sm-2 text-right col-form-label" >Kendaraan</label>
							<div class="col-sm-3">
								<input readonly type="text" name="kendaraan" class="form-control" value="{{ $dt->kendaraan }} // {{ $dt->no_plat }}">									
							</div>											
							<label class="col-sm-2 text-right col-form-label" >Driver</label>
							<div class="col-sm-3">
								<input readonly type="text" name="driver" class="form-control" value="{{ $dt->karyawan }}">									
							</div>							
						</div>
						<div class="form-group row">								
							<label class="col-sm-2 text-right col-form-label" >Keterangan</label>
							<div class="col-sm-10">
								<input type="text" readonly value="{{ $dt->keterangan }}" name="keterangan" class="form-control" placeholder="Keterangan Lain-lain">
							</div>
						</div>					
				</div>				
				<hr>
				<div class="block-content block-content-full">
					<!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
					<table class="table table-responsive table-bordered table-striped table-vcenter">
						<thead>
							<tr>
								<th class="text-center" style="width: 5%;">No</th>								
								<th>No Transaksi</th>
								<th>No AWB</th>
								<th>Pengirim</th>
								<th>Penerima</th>																							
								<th>Paket</th>								
								<th>Jml</th>																
								<th>Kondisi</th>																
								<th class="text-center">#</th>
							</tr>
						</thead>
						<tbody>
						@foreach ($manifestDataDetail as $key => $row)
							<?php 
							$jum = count($manifestDataDetail);
							$no = $key+1;
							$status = "<span class='badge badge-info'>$row->status</span>";
							$jenis_tarif = "<span class='badge badge-primary'>$row->jenis_tarif</span>";
							$cariTujuan = cariDaerah_helper($row->id_kelurahan_penerima,'kec');								
							$cariAsal = cariDaerah_helper($row->id_kelurahan_pengirim,'kec');								
							?>
							<tr>
								<th class="text-center">{{ $key+1 }}</th>
								<td>{{ $row->kode_tr }}</td>
								<td>{{ $row->awb_number }}</td>								
								<td>
									{{ $row->nama_pengirim }} <br>
									<small>{{ $row->no_hp_pengirim }} //</small>
									<small>{{ $row->alamat_pengirim }} ,</small>
									<small>{{ $cariAsal }}</small>
								</td>
								<td>
									{{ $row->nama_penerima }} <br>
									<small>{{ $row->no_hp_penerima }} //</small>
									<small>{{ $row->alamat_penerima }} ,</small>
									<small>{{ $cariAsal }}</small>
								</td>								
								<td>{{ $row->isi_paket }}</td>
								<td>{{ $row->jumlah_paket }}</td>								
								<td>
									<select name="kondisi_<?=$no?>" class="form-control">
										<option>baik</option>
										<option>rusak</option>
										<option>hilang</option>
									</select>
								</td>
								<td class="text-center">
									<div class="custom-control custom-checkbox custom-control-info custom-control-lg mb-1">																			
										<input type="hidden" name="jum" value="<?=$jum;?>">											
										<input type="hidden" name="id_manifest" value="<?=$row->id_manifest;?>">											
										<input type="hidden" name="id_manifest_detail_<?=$no;?>" value="<?=$row->id_manifest_detail;?>">											
										<input type="hidden" name="pengiriman_id_<?=$no;?>" value="<?=$row->id;?>">											
                    <input type="checkbox" class="custom-control-input" id="pick_<?=$no?>" name="pick_<?=$no?>" value="1">
                    <label class="custom-control-label" for="pick_<?=$no?>">Check</label>
                  </div>
								</td>
							</tr>
						@endforeach													
						</tbody>
					</table>
					<div class="form-group row">											
						<div class="col-sm-2">
							<button type="submit" onclick="return confirm('Anda yakin ingin menyimpan semua data?')" name="submit" value="save" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Save All</button>						
						</div>
					</div>					
				</div>									
				</form>
			</div>
			</div>
	</main>
@endsection


{{-- <script src="{{ asset('js/pages/be_tables_datatables.min.js') }}"></script> --}}
