@extends('layout.template_dash')
@section('content')


<body>	
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
			

		<?php if(session()->has('msg')) { ?>
      {!! session()->get('msg') !!}                          
    <?php session()->forget('msg'); } ?>			      

		<div class="content">
			<div class="block">
				<div class="block-header block-header-default">
					<h3 class="block-title">
						<a class="btn btn-warning btn-sm float-right" href="{{ route('pengiriman.stt-list') }}"> <i class="fa fa-chevron-left"></i> Back</a>
					</h3>
				</div>				

				<div class="block-content block-content-full">									

					<?php 
					if($set=="insert"){ 
						$row = ""; ?>
						<form class="mb-5" action="{{ route('pengiriman.create') }}" method="POST">
					<?php
					}elseif($set=="edit"){
						$row = $pengiriman; ?>
						<form class="mb-5" action="{{ route('pengiriman.update',$row->id) }}" method="POST">
					<?php } ?>					
						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">
							<div class="col-sm-2">												
                <label>Tanggal</label>
                <input type="date" readonly class="form-control" name="tgl_input" value="<?=($row)?$row->tgl_input:date('Y-m-d');?>" placeholder="Tanggal">
              </div>
              <div class="col-sm-2 d-none">		                   	
                <label>No Transaksi</label>
                <input type="text" readonly class="form-control" name="code" value="<?=($row)?$row->code:'Auto';?>" placeholder="No Transaksi">								
              </div>
              <div class="col-sm-2">		                   	
                <label>STT Number</label>
                <input type="text" readonly class="form-control" name="awb_number" value="<?=($row)?$row->stt_number:'Auto';?>" placeholder="STT Number">								
              </div>
              <div class="col-sm-2">		                   	
                <label>Outlet</label>
                <select class="form-control" id="outlet_id" name="outlet_id" required>
									<option value="">- choose -</option>
									@foreach ($outlet as $key => $dt)
										@php
										if($row && $dt->id==$row->outlet_id) $sel = 'selected';
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
									@endforeach
								</select>
              </div>
              <div class="col-sm-2">		                   	
                <label>Port Asal</label>
                <select class="form-control select2" id="port_asal" name="port_asal" required>
									<option value="">- choose -</option>
									@foreach ($cabang as $key => $dt)
										@php
										if($row && $dt->id==$row->port_asal) $sel = 'selected';
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
									@endforeach
								</select>
              </div>
              <div class="col-sm-2">		                   	
                <label>Port Tujuan</label>
                <select class="form-control select2" id="port_tujuan" name="port_tujuan" required>
									<option value="">- choose -</option>
									@foreach ($cabang as $key => $dt)
										@php
										if($row && $dt->id==$row->port_tujuan) $sel = 'selected';
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
									@endforeach
								</select>
              </div>
            </div>											
					</div>
				</div>				

				<div class="row" data-masonry='{"percentPosition": true }'>
					<div class="col-sm-12 col-md-6">				
						<div class="block">
							<div class="block-content">
								<h2 class="content-heading border-bottom mb-4 pb-2">
									Detail Pengirim 
									<a class="btn btn-outline-warning btn-sm float-right" data-toggle="click-ripple"><i class="fa fa-search"></i> Cari Pengirim</a> 
								</h2>
	              <div class="row push">							
									<div class="block-content">
										<div class="form-group row">
											<div class="col-sm-6">												
	                      <label>Nama</label>
	                      <input type="text" class="form-control" name="nama_pengirim" value="<?=($row)?$row->nama_pengirim:'';?>" placeholder="Nama Lengkap">		                    
		                  </div>
		                  <div class="col-sm-6">		                   	
	                      <label>No HP</label>
	                      <input type="number" min="0" class="form-control" name="no_hp_pengirim" value="<?=($row)?$row->no_hp_pengirim:'';?>" placeholder="No HP">		                    
		                  </div>
		                </div>
		                <?php if($set=="edit"){ ?>
			                
	                  <?php }else{ ?>
	                    <div class="form-group">
	                      <label>Kecamatan</label>
	                      <select onchange="cekOngkir()" class="form-control js-select2" id="id_kecamatan_pengirim" name="id_kecamatan_pengirim"></select>                    	
	                    </div>
	                  <?php } ?>
                    <div class="form-group">
                      <label>Alamat Lengkap</label>
                      <textarea class="form-control" name="alamat_pengirim"><?=($row)?$row->alamat_pengirim:'';?></textarea>
                    </div>
										<!-- <div class="form-group">
											<div class="custom-control custom-checkbox custom-control-inline">
		                    <input type="checkbox" class="custom-control-input" checked id="simpanPengirim" name="simpanPengirim">
		                    <label class="custom-control-label" for="simpanPengirim">Simpan sebagai Data Pengirim</label>
		                  </div>                  									
										</div>																																		 -->

									</div>
								</div>
							</div>
						</div>						
					</div>

					<div class="col-sm-12 col-md-6">				
						<div class="block">							
							<div class="block-content">
								<h2 class="content-heading border-bottom mb-4 pb-2">Detail Paket</h2>
	              <div class="row push">							
									<div class="block-content">
										<div class="form-group row">
											<div class="col-sm-6">
												<div class="form-group">
		                      <label>Jenis Paket</label>
		                      <select class="form-control" id="jenis_paket_id" name="jenis_paket_id" required>
														<option value="">- choose -</option>
														@foreach ($jenisPaket as $key => $dt)
															@php
															if($row && $dt->id==$row->jenis_paket_id) $sel = 'selected';
																else $sel = '';
															@endphp
															<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
														@endforeach
													</select>
		                    </div>
		                  </div>
		                  <div class="col-sm-6">
		                   	<div class="form-group">
		                      <label>Isi Paket</label>
		                      <input type="text" class="form-control" name="isi_paket" value="<?=($row)?$row->isi_paket:'';?>" placeholder="Isi Paket">
		                    </div>
		                  </div>
		                </div>		                
		                <div class="form-group row">
											<div class="col-sm-6">												
	                      <label>Nilai Paket</label>
	                      <input type="text" class="form-control" name="nilai_paket" value="<?=($row)?$row->nilai_paket:'';?>" placeholder="Nilai Paket">										
		                  </div>
		                  <div class="col-sm-6">		                   	
	                      <label>Jumlah Paket</label>
												<input type="number" class="form-control" name="jumlah_paket" id="jumlah_paket" value="<?=($row)?$row->jumlah_paket:1;?>" placeholder="Jumlah Paket">										
		                  </div>
		                </div>
		                <div class="form-group row">
											<div class="col-sm-3">
												<div class="form-group">
													<label for="block-form1-username">Berat (gr)</label>
													<input type="number" class="form-control" value="<?=($row)?$row->berat:1000;?>" name="berat" id="berat" placeholder="Berat">
												</div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label for="block-form1-username">Panjang (cm)</label>
													<input type="number" class="form-control" value="<?=($row)?$row->panjang:10;?>" name="panjang" id="panjang" placeholder="Panjang">
												</div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label for="block-form1-username">Lebar (cm)</label>
													<input type="number" class="form-control" name="lebar" value="<?=($row)?$row->lebar:10;?>" id="lebar" placeholder="Lebar">
												</div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label for="block-form1-username">Tinggi (cm)</label>
													<input type="number" class="form-control" name="tinggi" value="<?=($row)?$row->tinggi:10;?>" id="tinggi" placeholder="Tinggi">
												</div>
											</div>											
										</div>
										
									</div>
								</div>	
							</div>
						</div>						
					</div>
					
					<div class="col-sm-12 col-md-6">				
						<div class="block">							
							<div class="block-content">
								<h2 class="content-heading border-bottom mb-4 pb-2">
									Detail Penerima 
									<a class="btn btn-outline-warning btn-sm float-right" data-toggle="click-ripple"><i class="fa fa-search"></i> Cari Penerima</a> 
								</h2>
	              <div class="row push">							
									<div class="block-content">
										<div class="form-group row">
											<div class="col-sm-6">
												<div class="form-group">
		                      <label>Nama</label>
		                      <input type="text" class="form-control" name="nama_penerima" value="<?=($row)?$row->nama_penerima:'';?>" placeholder="Nama Lengkap">
		                    </div>
		                  </div>
		                  <div class="col-sm-6">
		                   	<div class="form-group">
		                      <label>No HP</label>
		                      <input type="number" min="0" class="form-control" name="no_hp_penerima" value="<?=($row)?$row->no_hp_penerima:'';?>" placeholder="No HP">
		                    </div>
		                  </div>
		                </div>
		                <div class="form-group">
                      <label>Kecamatan</label>
                      <select onchange="cekOngkir()" name="id_kecamatan_penerima" id="id_kecamatan_penerima" class="js-select2 form-control">		                  	
		                 	</select>
                    </div>
                    <div class="form-group">
                      <label>Alamat Lengkap</label>
                      <textarea class="form-control" name="alamat_penerima"><?=($row)?$row->alamat_penerima:'';?></textarea>
                    </div>
										<!-- <div class="form-group">
											<div class="custom-control custom-checkbox custom-control-inline">
		                    <input type="checkbox" class="custom-control-input" checked id="simpanPenerima" name="simpanPenerima">
		                    <label class="custom-control-label" for="simpanPenerima">Simpan sebagai Data Penerima</label>
		                  </div>                  									
										</div>																																		 -->
									</div>
								</div>	
							</div>
						</div>						
					</div>		

					<div class="col-sm-12 col-md-6">
						<div class="block">				
							
							<div class="block-content">
								<h2 class="content-heading border-bottom mb-4 pb-2">Estimasi Biaya</h2>																							
								<div class="row push">							
									<div class="block-content">		
										<div class="form-group row">
											<div class="col-sm-6">												
	                      <label>Jenis Kirim</label>
	                      <select onchange="cekOngkir()" class="form-control" name="jenis_tarif_id" id="jenis_tarif_id" required>
													<option value="">- choose -</option>
													@foreach ($jenisTarif as $key => $dt)
														@php
														if($row && $dt->id==$row->jenis_tarif_id) $sel = 'selected';
															else $sel = '';
														@endphp
														<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
													@endforeach
												</select>		                    
		                  </div>
		                  <div class="col-sm-6">		                   	
	                      <label>Ongkir</label>
												<input type="text" readonly class="form-control" id="ongkir" name="ongkir" value="<?=($row)?$row->ongkir:0;?>" placeholder="Ongkir">		                      
		                  </div>
		                </div>
		                <div class="form-group row">
											<div class="col-sm-6">												
	                      <label>Biaya Asuransi</label>
	                      <div class="form-group row">		                      
		                      <div class="col-sm-4">												
		                      	<input type="text" readonly class="form-control" id="b_asuransi" name="b_asuransi" value="0" placeholder="Biaya Asuransi">											                    
		                      </div>
		                      <div class="col-sm-8">												
		                      	<input type="text" readonly class="form-control" id="biaya_asuransi" name="biaya_asuransi" value="<?=($row)?$row->biaya_asuransi:0;?>" placeholder="Biaya Asuransi">											                    
		                  		</div>
		                  	</div>
		                  </div>
		                  <div class="col-sm-6">		                   	
	                      <label>Total Tagihan</label>
												<input type="text" readonly class="form-control" id="total_tagihan" name="total_tagihan" value="<?=($row)?$row->total_tagihan:0;?>" placeholder="Total Tagihan">																						
		                  </div>
		                </div>
		              </div>
		            </div>
		            
								<h2 class="content-heading border-bottom mb-4 pb-2">Pembayaran & Asuransi</h2>
	              <div class="row push">							
									<div class="block-content">							
										<div class="form-group">
		                  <label class="d-block">Metode Pembayaran</label>
		                  <div class="custom-control custom-radio custom-control-inline custom-control-lg">
                        <input type="radio" class="custom-control-input" id="cod" name="metode_bayar" checked>
                        <label class="custom-control-label" for="cod">COD</label>
                      </div>
                      <div class="custom-control custom-radio custom-control-inline custom-control-lg">
                        <input type="radio" class="custom-control-input" id="noncod" name="metode_bayar">
                        <label class="custom-control-label" for="noncod">Non COD</label>
                      </div>		                                
		              	</div>		              		            
										<div class="form-group">
											<div class="custom-control custom-checkbox custom-control-inline custom-control-lg">
		                    <input type="checkbox" class="custom-control-input" id="asuransi" name="asuransi">
		                    <label class="custom-control-label" for="asuransi">Centang jika ingin Asuransikan paket</label>
		                  </div>                  									
										</div>
								
		                <hr>
										<div class="form-group">
											<div class="float-left">
												<button type="button" onclick="cekOngkir()" class="btn btn-warning"><i class="fa fa-refresh"></i> Cek Ongkir</button>
											</div>
											<div class="float-right">
												<button type="submit" onclick="return confirm('Pastikan semua data sudah terisi, lanjutkan?')" class="btn btn-primary"><i class="fa fa-save"></i> Simpan dan Proses Pengiriman</button>
												<button type="reset" class="btn btn-default">Reset</button>
											</div>
										</div>											
									</div>

								</div>
							</div>
						</div>						
					</div>						

											

				</div>
			</form>
		</div>
	</main>



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
$('#asuransi').on('click',function(){
  if(this.checked){
  	let total_tagihan = $("#total_tagihan").val(); 
  	$.ajax({
      url : "{{ route('pengiriman.cekAsuransi') }}",
      type:'POST',
      data: {
        _token: '{!! csrf_token() !!}',        
        total_tagihan: total_tagihan,
      },      
      cache:false,   
      success:function(msg){  
      	data = msg.split("|");                	            
      	$("#b_asuransi").val(data[0]+"%");
      	$("#biaya_asuransi").val(data[1]);
      	cekOngkir();
      }
    })  
  }else{
    $("#biaya_asuransi").val('0');
    $("#b_asuransi").val('0');
    cekOngkir();
  }  
});
function cekOngkir(){
	let biaya_asuransi = $("#biaya_asuransi").val(); 	
	let jumlah_paket = $("#jumlah_paket").val(); 	
	let berat = $("#berat").val(); 	
	let tinggi = $("#tinggi").val(); 	
	let panjang = $("#panjang").val(); 	
	let lebar = $("#lebar").val(); 	
	let asal = $("#id_kecamatan_pengirim").val(); 	
	let tujuan = $("#id_kecamatan_penerima").val(); 
	let jenis_tarif_id = $("#jenis_tarif_id").val(); 	
	if(asal!=''&&tujuan!=''&&jenis_tarif_id!=''&&jumlah_paket!=''&&berat!=''&&tinggi!=''&&panjang!=''&&lebar!=''){		
		$.ajax({
      url : "{{ route('pengiriman.cekOngkir') }}",
      type:'POST',
      data: {
        _token: '{!! csrf_token() !!}',
        jumlah_paket: jumlah_paket,
        panjang: panjang,
        lebar: lebar,
        tinggi: tinggi,
        berat: berat,
        asal: asal,
        tujuan: tujuan,
        biaya_asuransi: biaya_asuransi,
        jenis_tarif_id: jenis_tarif_id,
      },      
      cache:false,   
      success:function(msg){    
      	data = msg.split("|");        
      	if(data[0]=='exist'){
	        $("#ongkir").val(data[1]);                	        
	        $("#total_tagihan").val(data[2]); 	        
	      }else{
	      	alert(data[0]);
	      	$("#ongkir").val(0);                	        
	        $("#total_tagihan").val(0);                	      	
	      }
      }
    })
	}else{
		return false;
	}
}
</script>
@endsection	