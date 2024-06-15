<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MemberModel;
use App\Models\PartnershipModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use DB;
use PDF;

class LapMitraController extends Controller
{
	
	var $set        = "lapMitra";
	var $title      = "Laporan Mitra";
	var $subtitle   = "Laporan Mitra";
	var $folder     = "laporan/lapMitra";

	public function index(){
		$lapMitra = "";
		$data['title']  = $this->title;
		$data['subtitle']  = $this->subtitle;
		$data['isi']    = $this->set;
		$data['set']    = "view";
		$data['filter_1']="";
		return view($this->folder.'/index',$data)->with('lapMitra', $lapMitra);
	}
	public function filter(Request $request){		
		$data['filter_1'] = "";
		$where="1=1";$file = "index";
		if(isset($request->jenis)){ 			
			$data['filter_1'] = $request->jenis;			
			if($request->jenis=="partnership"){								
				$data['dt_partnership'] = PartnershipModel::whereRaw($where)
					->orderBy('partnership.name', 'ASC')
					->get();							
			}else{
				$data['dt_partnership'] = MemberModel::leftJoin("partnership","member.partnership_id","=","partnership.id")
					->orderBy('member.name','ASC')->get(['member.*','partnership.name as partner']);
			}		
		}
						
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

