<?php 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=laporanOutstandingMurid.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table class="table border-table" id="example">
  <thead>
   <tr>
     <th width="5%">No</th>                  
     <th>Invoice No</th>
     <th>Invoice Date</th>
     <th>Student ID</th>
     <th>Name</th>
     <th>Status</td>                   
     <th>Cabang</th>                                                         
   </tr>
 </thead> 
 <tbody>
 @foreach ($invoiceMurid as $key => $row)
  @php
  if($row->status==1){ 
    $status = "";               
    $edit = "d-none";
    $print = "";
  }elseif($row->status==2){ 
    $status = "lunas";                
    $edit = "d-none";
    $print = "";
  }else{ 
    $edit = "";$print="d-none";
    $status = "";
  }

  $cekCabang = DB::table('branch_center')->where('id',$row->cabang_id)->first();                    
  $nama_bc = $cekCabang->nama_bc;

  @endphp
  <tr>
    <td>{{$key + 1}}</td>                
    <td>{{ $row->kode }}</td>                 
    <td>{{ $row->tgl_invoice }}</td>                  
    <td>{{ $row->kode_siswa }}</td>                                                   
    <td>{{ $row->fullname }}</td>                                                   
    <td>{!! $status !!}</td>                                  
    <td>{{ $nama_bc }}</td>                                                             
  </tr>
    
  @endforeach
 


 </tbody>                 
</table>    
