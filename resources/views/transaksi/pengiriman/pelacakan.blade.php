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
			


			<div class="block">

				<?php if(session()->has('msg')) { ?>
	      {!! session()->get('msg') !!}                          
	    	<?php session()->forget('msg'); } ?>			      

				<div class="block-header block-header-default">
					Silakan Masukkan STT Number
				</div>
				<div class="block-content block-content-full">
					<form class="mb-5" action="{{ route('pengiriman.lacakPengiriman') }}" method="POST">					
						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group">
									
              <div class="input-group col-sm-8">
                <input type="text" required class="form-control" value="{{ $stt_number }}" name="stt_number" placeholder="STT Number">
                <div class="input-group-append">
                  <button type="submit" class="btn btn-danger"><i class="fa fa-search"></i> Lacak Sekarang!</button>
                </div>
              </div>
	            
            </div>
          </form>          
          <?php if(!empty($timelineStatus)){ ?>                  	

	        <div class="content content-boxed">
						<div class="row">
							<div class="col-md-6 col-xl-7">
								<!-- Updates -->
								<ul class="timeline timeline-alt py-0">
									@foreach($timelineStatus AS $key => $row)
									<li class="timeline-event">	
										<?php if($row->status!='paket_sampai_dipenerima'){ ?>								
										<div class="timeline-event-icon bg-default">										
											<i class="fa fa-truck"></i>
										</div>
										<?php }else{ ?>
										<div class="timeline-event-icon bg-success">										
											<i class="fa fa-check"></i>
										</div>
										<?php } ?>
										<div class="timeline-event-block block invisible" data-toggle="appear">
											<div class="block-header" style="margin-bottom: -20px;">
												<h3 class="block-title">{{ tgl_indo(substr($row->waktu,0,10)) }} // <small>{{ substr($row->waktu,11,8) }}</small></h3>																						
											</div>
											<div class="block-content">
												<p>
													<strong>{{ $row->status }}</strong> <br>											
													{{ $row->keterangan }}
												</p>
												<?php if($row->status=='paket_sampai_dipenerima'){ ?>								
													<a href="{{ asset('ima49es/delivery/'.$dt_delivery->gambar) }}"> Lihat Bukti Penyerahan </a>
												<?php } ?>
											</div>
										</div>
									</li>								
									@endforeach
									
								</ul>
								<!-- END Updates -->
							</div>						
							<div class="col-md-6 col-xl-5">
								<?php 								
								$cariTujuan = cariDaerah_helper($dts->id_kelurahan_penerima,'kec');								
								$cariAsal = cariDaerah_helper($dts->id_kelurahan_pengirim,'kec');								
								$tgl_indo = tgl_indo(substr($dts->created_at, 0,10));
								?>

								<h3>Pengirim</h3>
								<p>
									<strong><i>{{ $dts->nama_pengirim }}</i></strong><br>
									<strong>No Hp: </strong> {{ $dts->no_hp_pengirim }} <br>
									<strong>Alamat: </strong>{{ $dts->alamat_pengirim }} {{ $cariAsal }}
								</p>

								<hr>
								<h3>Penerima</h3>
								<p>
									<strong><i>{{ $dts->nama_penerima }}</i></strong><br>
									<strong>No Hp: </strong> {{ $dts->no_hp_penerima }} <br>
									<strong>Alamat: </strong>{{ $dts->alamat_penerima }} {{ $cariTujuan }}
								</p>
							</div>
						</div>
					</div>

					<?php } ?>

				</div>			
			</div>
	</main>
@endsection


{{-- <script src="{{ asset('js/pages/be_tables_datatables.min.js') }}"></script> --}}
