<table class="table table-striped table-bordered" id="myTable">
 	<thead>
	 	<tr>
		 	<th width="5%">No</th>									 
		 	<th>Member</th>		 	
		 	<th>Kurun Waktu</th>		 	
		 	<th>Jml.Kewajiban</th>		 	
		 	<th>Jml.Setoran</th>		 	
		 	<th>Selisih.Setoran</th>		 			 	
	 	</tr>
 	</thead>	
	<tbody>		
	<?php 	
	 $no=1;$gtotalWajib=0;$gtotalSetor=0;$gtotalSelisih=0;
	 foreach ($rekonsiliasi as $key => $dt) {	 		 	
	 	$tot=0;$setor=0;
	 	$cariKewajiban = DB::table("member_paket")->join("paket","member_paket.paket_id","=","paket.id")
	 		->where("paket.periode_id",$periode)
	 		->where("member_paket.member_id",$dt->ids)
	 		->get(['paket.iuran','paket.tgl_awal']);
	 	foreach ($cariKewajiban as $key => $value) {
	 		// $selisih = cariSelisih($value->tgl_mulai,$tgl_rekonsiliasi);
	 		$tot+=$value->iuran;	 			 	
	 	}

	 	$cariSetor = DB::table("setoranPaket")->join("member_paket","setoranPaket.member_paket_id","=","member_paket.id")
	 		->join("paket","member_paket.paket_id","=","paket.id")
	 		->where("paket.periode_id",$periode)
	 		->where("member_paket.member_id",$dt->ids)
	 		->get(['setoranPaket.nominal']);
	 	foreach ($cariSetor as $key => $value) {
	 		$setor+=$value->nominal;
	 	}

	 	$jmlKewajiban=$tot * $selisih;
	 	$jmlSetoran=$setor;

	 	$selisihKurang = $jmlKewajiban - $jmlSetoran; 
	 	
	 	echo "
	 	<tr>
	 		<td>$no</td>
	 		<td>$dt->namaMember</td>	 		
	 		<td>$selisih hari</td>	 		
	 		<td>".mata_uang_help($jmlKewajiban)."</td>	 			 		
	 		<td>".mata_uang_help($jmlSetoran)."</td>	 			 		
	 		<td>".mata_uang_help($selisihKurang)."</td>	 			 			 		
	 	</tr>
	 	";
	 	$gtotalWajib+=$jmlKewajiban;
	 	$gtotalSetor+=$jmlSetoran;
	 	$gtotalSelisih+=$selisihKurang;
	 	$no++;
	 }
	 ?>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="3">Total</th>
			<th><?=mata_uang_help($gtotalWajib)?></th>
			<th><?=mata_uang_help($gtotalSetor)?></th>
			<th><?=mata_uang_help($gtotalSelisih)?></th>			
		</tr>
	</tfoot>
</table>