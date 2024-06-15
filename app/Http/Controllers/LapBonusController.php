<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PartnershipModel;
use App\Models\SetoranManajemenModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;
use PDF;

class LapBonusController extends Controller
{
	
	var $set        = "lapBonus";
	var $title      = "Laporan Bonus";
	var $subtitle   = "Laporan Bonus";
	var $folder     = "laporan/lapBonus";

	public function index(){
		$lapBonus = "";
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";
		$data['filter_1']="";
		return view($this->folder.'/index',$data)->with('lapBonus', $lapBonus);
	}
	public function filter(Request $request){		
		$data['filter_1'] = "";
		$where="1=1";
		if(isset($request->partnership_id)){ 
			$where.= " AND setoranManajemen.partnership_id = '$request->partnership_id'";
			$data['filter_1'] = $request->partnership_id;			
		}
		
		$file = "index";
		$fileXls = "xlsx";
		$filePdf = "pdf";
		$data['dt_paket'] = SetoranManajemenModel::join("partnership","setoranManajemen.partnership_id","=","partnership.id")
					->orderBy('setoranManajemen.id','DESC')
					->join("level","partnership.level_id","=","level.id")
					->whereRaw($where)
					->groupBy("partnership.id")
					->get(['setoranManajemen.*','partnership.name AS namaPartner',"level.name AS level","level.persen_parcel","level.persen_thr"]);               		
						
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

