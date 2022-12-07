<?php

namespace App\Http\Controllers\Api\Modules\Sap\SalesOrder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class SalesContractController extends Controller
{
    public function salesCntSubmit(Request $request)
    {
    	 echo "<pre>";print_r($request->all());exit();
    }


     // ----------------------------- get all price braeks --------------------------------------------

	   public function priceBreakFetch($po_no)
	    {

	    	  try{ 
                   
                 	 $res = DB::table('orders')->leftjoin('sc_transactions','orders.rfq_no','sc_transactions.rfq_no')
                 	 ->leftjoin('plants','sc_transactions.plant','plants.id')
                 	 ->groupBy('sc_transactions.mat_code')
                 	     ->select('sc_transactions.*','plants.code as pcode')->where('orders.po_no',$po_no)->get();
                 	 // echo "<pre>";print_r($newcount);exit();
                 	 foreach ($res as $key => $value) {
                 	 	
                 	 	 $data[$key]['id'] = $value->id;
                 	 	 $data[$key]['code'] = $value->code;
                 	 	 $data[$key]['value'] = $value->value;
                 	 	 $data[$key]['mat_code'] = $value->mat_code;
                 	 	 $data[$key]['pcode'] = $value->pcode;
                 	 	 $data[$key]['rfq_no'] = $value->rfq_no;
                 	 	 $data[$key]['price_det'] = $this->priceBreakById($value->mat_code);
                 	 	 $data[$key]['specs'] = $this->subcatspecs($value->mat_code);
           
                 	 }
			    	 
			        return response()->json(['status'=>1,
			          'message' =>'success',
			          'result' => $data],
			          config('global.success_status'));


	      }catch(\Exception $e){

	       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
	     }

	    	 
	    }

	 // ---------------------------------------------------------------------------

	// ----------------------------- get all price braeks by mat no. --------------

	   public function priceBreakById($mat_no)
	    {

	    	  
                   
                 	 $res = DB::table('sc_transactions')->leftjoin('price_masters','sc_transactions.code','price_masters.code')
                 	     ->select('sc_transactions.*','price_masters.component')->where('sc_transactions.mat_code',$mat_no)->get();

                 	  foreach ($res as $key => $value) {
                 	  	
                 	  	  $arr[$key]['cnty'] = $value->code;
                 	  	  $arr[$key]['des'] = $value->component;
                 	  	  $arr[$key]['amt'] = $value->value;
                 	  }
                 	 // echo "<pre>";print_r($newcount);exit();
			    	 
			        return $arr;



	    	 
	    }

	 // ---------------------------------------------------------------------------

	   public function subcatspecs($mat_no)
	    {

	    	  
                   
                 	 $res = DB::table('sc_transactions')->leftjoin('product_size_mat_no','sc_transactions.mat_code','product_size_mat_no.mat_no')
                 	 ->leftjoin('sub_categorys','product_size_mat_no.sub_cat_id','sub_categorys.id')
                 	     ->select('sc_transactions.mat_code','sub_categorys.*')->where('sc_transactions.mat_code',$mat_no)->get();

                 	  foreach ($res as $key => $value) {
                 	  	  
                 	  	  $cr = explode("-",str_replace("%","",$value->Cr));
                 	  	  $c = explode("-",str_replace("%","",$value->C));
                 	  	  $Phos = explode("-",str_replace("%","",$value->Phos));
                 	  	  $s = explode("-",str_replace("%","",$value->S));
                 	  	  $si = explode("-",str_replace("%","",$value->Si));
                 	  	  $ti = explode("-",str_replace("%","",$value->Ti));

                 	  	  $arr['cr_max'] = (!empty($c[0]) ? $c[0] : '');
                 	  	  $arr['cr_min'] = (!empty($cr[1]) ? $cr[1] : '');
                 	  	  $arr['c_max'] = (!empty($c[0]) ? $c[0] : '');
                 	  	  $arr['c_min'] = (!empty($c[1]) ? $c[1] : '');
                 	  	  $arr['phos_max'] = (!empty($Phos[0]) ? $Phos[0] : '');
                 	  	  $arr['phos_min'] = (!empty($Phos[1]) ? $Phos[1] : '');
                 	  	  $arr['s_max'] = (!empty($s[0]) ? $s[0] : '');
                 	  	  $arr['s_min'] = (!empty($s[1]) ? $s[1] : '');
                 	  	  $arr['si_max'] = (!empty($si[0]) ? $si[0] : '');
                 	  	  $arr['si_min'] = (!empty($si[1]) ? $si[1] : '');
                 	  	  $arr['ti_max'] = (!empty($ti[0]) ? $ti[0] : '');
                 	  	  $arr['ti_min'] = (!empty($ti[1]) ? $ti[1] : '');
                 	  	  $arr['uom'] = "%";
                 	  }
                 	 // echo "<pre>";print_r($newcount);exit();
			    	 
			        return $arr;



	    	 
	    }
}
