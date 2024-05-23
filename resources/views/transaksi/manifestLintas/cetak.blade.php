<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cetak Manifest</title>
</head>
<?php 
$dt = $manifest->first(); 
$cariPortAsal = DB::table("cabang")->where("id",$dt['port_asal'])->first()->name;
$cariPortTujuan = DB::table("cabang")->where("id",$dt['port_tujuan'])->first()->name;
?>
<body>	
<center>
	<b>
		CETAK MANIFEST
	</b>
</center>	
<hr>
	<table>
		<tr>
			<td>
			No Manifest Lintas: {{ $dt['manifest_code'] }} <br>
			Tgl Manifest : {{ $dt['tgl_manifest'] }} <br>
			Port Asal : {{ $cariPortAsal }}<br>
			Port Tujuan : {{ $cariPortTujuan }}<br>
			</td>
			<td>
			Kendaraan: {{ $dt['kendaraan'] }} <br>
			Driver : {{ $dt['karyawan'] }} <br>
			Total Paket : {{ $manifest->count() }} item<br>
			</td>
		</tr>
	</table>
	<table border="1" width="100%">
		<thead>
			<tr>
				<th class="text-center" style="width: 5%;">No</th>												
				<th>STT Number</th>
				<th>Pengirim</th>
				<th>Penerima</th>															
				<th>Jenis Ongkir</th>								
				<th>Paket</th>								
				<th>Jml</th>								
				<th>Dimensi</th>																				
			</tr>
		</thead>
		<tbody>
		@foreach ($manifest as $key => $row)
			<?php 			
			$no = $key+1;
			$status = "<span class='badge badge-info'>$row->status</span>";
			$konstanta = get_setting("konstanta");
			?>
			<tr>
				<th class="text-center">{{ $key+1 }}</th>				
				<td>{{ $row->stt_number }}</td>
				<td>{{ $row->nama_pengirim }}</td>
				<td>{{ $row->nama_penerima }}</td>
				<td>{{ $row->jenis_tarif }}</td>
				<td>{{ $row->isi_paket }}</td>
				<td>{{ $row->jumlah_paket }}</td>				
				<td>
					{{ $row->berat }} gr / {{ floor(($row->panjang * $row->lebar * $row->tinggi) / $konstanta) }} m<sup>3</sup>
				</td>											
			</tr>
		@endforeach													
		</tbody>
	</table>
</body>
</html>