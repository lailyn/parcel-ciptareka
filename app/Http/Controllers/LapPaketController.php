<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PaketModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;
use PDF;

class LapPaketController extends Controller
{
	
	var $set        = "lapPaket";
	var $title      = "Laporan Paket";
	var $subtitle   = "Laporan Paket";
	var $folder     = "laporan/lapPaket";

	public function index(){
		$lapPaket = "";
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";
		$data['filter_1']="";
		return view($this->folder.'/index',$data)->with('lapPaket', $lapPaket);
	}
	public function filter(Request $request){		
		$data['filter_1'] = "";
		$where="1=1";
		if(isset($request->paket_id)){ 
			$where.= " AND paket.id = '$request->paket_id'";
			$data['filter_1'] = $request->paket_id;			
		}
		
		$file = "index";
		$fileXls = "xlsx";
		$filePdf = "pdf";
		$data['dt_paket'] = PaketModel::whereRaw($where)
			->orderBy('paket.name', 'ASC')
			->get();							
						
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "filter";
		$data['filter']    = "";		
		$submit  = $request->submit;
		if($submit=="filter"){
			$link = view($this->folder."/".$file,$data);
		}elseif($submit=="xlsxFormat"){
			$link = view($this->folder.'/'.$fileXls,$data);
		}elseif($submit=="pdfFormat"){			
			$link = view($this->folder.'/'.$filePdf,$data);
		}

		return $link;
		
		
		
	}
}

