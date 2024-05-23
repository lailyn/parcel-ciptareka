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
			No AWB: {{ $dt['manifest_code'] }} <br>
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
				<th>No AWB</th>
				<th>Port Asal</th>
				<th>Port Tujuan</th>															
				<th>Total Paket</th>																
				<th class="text-center">Status</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($manifest as $key => $row)
			<?php 
			$cariTujuan = DB::table("cabang")->where("id",$row->port_tujuan);								
			$tujuan = ($cariTujuan->count())?$cariTujuan->first()->name:'';
			$cariAsal = DB::table("cabang")->where("id",$row->port_asal);								
			$asal = ($cariAsal->count())?$cariAsal->first()->name:'';
			$cekTotal = DB::table("manifestUtama_detail")->where("code",$row->code)->count();									
			$status = "<span class='badge badge-info'>$row->status</span>";
			?>
			<tr>
				<th class="text-center">{{ $key+1 }}</th>								
				<td>{{ $row->code }}</td>
				<td>{{ $asal }}</td>
				<td>{{ $tujuan }}</td>
				<td>{{ $cekTotal }} paket</td>																
				<td>{!! $status !!}</td>																
			</tr>
		@endforeach													
		</tbody>
	</table>
</body>
</html>