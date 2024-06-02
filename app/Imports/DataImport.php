<?php

namespace App\Imports;

use App\Models\MemberModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Helper;

class DataImport implements ToModel, WithHeadingRow
{
	public function model(array $row)
	{
		// Menyesuaikan kolom-kolom Excel dengan kolom-kolom database						
		// dd($row['name']); die();
		return new MemberModel([			
			'name' => $row['name'],
			'no_ktp' => $row['no_ktp'],			
			'tgl_lahir' => $row['tgl_lahir'],
			'alamat' => $row['alamat'],
			'no_hp' => str_replace("-", "", $row['no_hp']),
			'kodepos' => $row['kodepos'],                        									
			'created_at' => Carbon::now()->toDateTimeString()			
		]);
	}
}