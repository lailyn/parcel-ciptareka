<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cetak STT</title>
	<style type="text/css">
	.border-table {
	  border: 1px solid #ccc; /* Add a solid border to the table */
	  border-collapse: collapse; /* Combine cell borders into a single border */
	}

	.border-table th, .border-table td {
	  border: 1px solid #ccc; /* Add a solid border to the table cells */
	  padding: 8px; /* Add some padding for better readability */
	 }
	</style>
</head>
<body>
<?php $row = $pengiriman; ?>
<table class="border-table" width="100%">
	<tr align="center">
		<td>
			<img width="100px" src="{{ asset('ima49es/'.get_setting('logo')) }}">  <br>
		 <strong> {{ get_setting('app') }} </strong>
		</td>
		<td colspan="3">			
				<strong>{{ $row->code }}</strong>
		</td>
	</tr>		
	<tr>
		<td colspan="2">
			<strong>Pengirim: {{ $row->nama_pengirim }} </strong> ({{ $row->no_hp_pengirim }}) <br>
			Alamat: {{ $row->alamat_pengirim }} <br>
			{{ cariDaerah_helper($row->id_kelurahan_pengirim,'kec') }}								
			
		</td>
		<td width="50%" colspan="2">
			<strong>Penerima: {{ $row->nama_penerima }} </strong> ({{ $row->no_hp_penerima }}) <br>
			Alamat: {{ $row->alamat_penerima }} <br>
			{{ cariDaerah_helper($row->id_kelurahan_penerima,'kec') }}															
		</td>
	</tr>	
	<tr valign="top">
		@php $konstanta = get_setting("konstanta") @endphp
		<td>
			<strong> Berat // Dimensi <br> </strong>
			{{ $row->berat }} gr / {{ floor(($row->panjang * $row->lebar * $row->tinggi) / $konstanta) }} m<sup>3</sup>
		</td>				
		<td>
				<strong> Isi Paket <br> </strong>
				{{ $row->isi_paket }} @ {{ $row->jumlah_paket }}
		</td>		
		<td>
			<strong> Total Biaya <br> </strong>
			{{ mata_uang_help($row->total_tagihan) }}
		</td>		
		<td rowspan="2" width="20%">			
			{!! QrCode::size(200)->generate($row->stt_number) !!}
		</td>							
	</tr>
	<tr>
		<td colspan="3">
			<strong>Keterangan: </strong>
			{{ $row->keterangan }}
		</td>
	</tr>
	

</table>

</body>
</html>