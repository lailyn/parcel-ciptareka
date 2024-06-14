<table class="table table-striped table-bordered" id="myTable">
 	<thead>
	 	<tr>
		 	<th width="5%">No</th>									 
		 	<th>Member</th>		 	
		 	<th>Nominal</th>		 	
		 	<th width="10%">#</th>
	 	</tr>
 	</thead>	
	<tbody>		
	<?php 
	 $no=1;$gtotal=0;
	 foreach ($setoranPaket as $key => $dt) {	 	
	 	$jum = $setoranPaket->count();
	 	echo "
	 	<tr>
	 		<td>$no</td>
	 		<td>$dt->namaMember</td>	 		
	 		<td>".mata_uang_help($dt->totalNominal)."</td>	 		
	 		<td>
	 			<input type='hidden' name='jum' value='$jum'>	 			
	 			<input type='hidden' name='setoranPaket_id_$no' value='$dt->ids'>	 			
	 			<label class='form-check-label'>
          <input class='data-check' type='checkbox'
            name='chk_$no' value='1'> check
        </label>
	 		</td>
	 	</tr>
	 	";
	 	$gtotal+=$dt->totalNominal;
	 	$no++;
	 }
	 ?>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="2">Total</th>
			<th><?=mata_uang_help($gtotal)?></th>
			<th colspan="2"></th>
		</tr>
	</tfoot>
</table>