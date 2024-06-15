<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cetak Laporan</title>
	<style type="text/css">
	.body{
		font-family: 'Tahoma'
	}
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
<body onload="window.print()">


<table class="table border-table" id="example">
 	<thead>
    <tr>
      <th class="text-center" style="width: 80px;">No</th>                
      <th>Name</th>
      <th>Satuan</th>
      <th>Harga</th>                  
      <th>Keterangan</th>                               
    </tr>
  </thead>
  <tbody>
  @foreach ($dt_produk as $key => $row)               
    <tr>
      <td>{{$key + 1}}</td>                
      <td>{{ $row->name }}</td>                 
      <td>{{ $row->satuan }}</td>                  
      <td>{{ mata_uang_help($row->harga_harian) }}</td>                  
      <td>{{ $row->keterangan }}</td>                                   
    </tr>
      
    @endforeach
              

  </tbody>                
</table>

</body>
</html>