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
					$row = $pengiriman; ?>
					<form class="mb-5" action="{{ route('pengiriman.updateBayar',$row->id) }}" method="POST">					
						<input type="hidden" name="_token" value="{{ csrf_token() }}">						
						<div class="form-group row">
							<div class="col-sm-4">												
                <label>Tanggal</label>
                <input type="date" readonly class="form-control" name="tgl_input" value="<?=($row)?$row->tgl_input:date('Y-m-d');?>" placeholder="Tanggal">
              </div>
              <div class="col-sm-4">		                   	
                <label>No Transaksi</label>
                <input type="text" readonly class="form-control" name="code" value="<?=($row)?$row->code:'Auto';?>" placeholder="No Transaksi">								
              </div>
              <div class="col-sm-4">		                   	
                <label>STT Number</label>
                <input type="text" readonly class="form-control" name="stt_number" value="<?=($row)?$row->stt_number:'Auto';?>" placeholder="STT Number">								
              </div>
            </div>
            <div class="form-group row">
							<div class="col-sm-4">												
                <label>Total Tagihan</label>
                <input type="text" readonly class="form-control" name="total_tagihan" value="<?=($row)?mata_uang_help($row->total_tagihan):0;?>" placeholder="Total Tagihan">
              </div>
              <div class="col-sm-4">		                   	
                <label>Metode Bayar</label>
                <select required class="form-control" name="metode_bayar_id">
                	<option value="">- choose -</option>
                	@foreach ($metodeBayar as $key => $dt)
										@php
										if($row && $dt->id==$row->metode_bayar_id) $sel = 'selected';
											else $sel = '';
										@endphp
										<option <?=$sel?> value="{{ $dt->id }}">{{ $dt->name }} </option>
									@endforeach
                </select>
              </div>                          
              <div class="col-sm-2">		                   	
                <label>Status Bayar</label>
                <select required class="form-control" name="status_bayar">                	
                	<option <?=($row&&$row->status_bayar=='baru')?'selected':'';?> value="1">Belum Bayar</option>                	
                	<option <?=($row&&$row->status_bayar=='lunas')?'selected':'';?> value="2">Lunas</option>                	
                </select>
              </div>              
            </div>			
            <div class="form-group">							
							<div class="float-left">
								<button type="submit" onclick="return confirm('Anda yakin?')" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Pembayaran</button>
								<button type="reset" class="btn btn-default">Reset</button>
							</div>
						</div>								
					</div>
				</div>				

				
			</form>
		</div>
	</main>




@endsection	