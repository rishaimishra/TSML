<?php

namespace App\Http\Controllers\Api\Modules\Sap\SalesOrder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SalesContarct;
use App\Models\SalesContractMaterial;
use App\Models\SalesContarctSpecs;
use App\Models\ScPriceDetail;
use DB;

class SalesContractController extends Controller
{
    public function salesCntSubmit(Request $request)
    {    
         try{ 
                 // echo "<pre>";print_r($request->all());exit();
		    	 $contract['po_no'] = $request->input('po_no');
		    	 $contract['contract_ty'] = $request->input('contract_ty');
		    	 $contract['sales_grp'] = $request->input('sales_grp');
		    	 $contract['qty_cont'] = $request->input('qty_cont');
		    	 $contract['net_val'] = $request->input('net_val');
		    	 $contract['sold_to_party'] = $request->input('sold_to_party');
		    	 $contract['ship_to_party'] = $request->input('ship_to_party');
		    	 $contract['cus_ref'] = $request->input('cus_ref');
		    	 $contract['cus_ref_dt'] = $request->input('cus_ref_dt');
		    	 $contract['shp_cond'] = $request->input('shp_cond');
		    	 $contract['sales_org'] = $request->input('sales_org');
		    	 $contract['dis_chnl'] = $request->input('dis_chnl');
		    	 $contract['div'] = $request->input('div');
		    	 $contract['sales_ofc'] = $request->input('sales_ofc');
		    	 $contract['cost_ref'] = $request->input('cost_ref');

		    	 $id = SalesContarct::create($contract);

		    	 $contarctId = $id['id'];
		    	 // echo "<pre>";print_r($request->input('material'));exit();

		    	 $materials = $request->input('material');

		    	 foreach ($materials as $key => $value) {
		    	 	 
		    	 	  $arr['contarct_id'] = $contarctId;
		    	 	  $arr['mat_code'] = $value['mat_code'];
		    	 	  $arr['rfq_no'] = $value['rfq_no'];
		    	 	  $arr['total'] = $value['total'];
		    	 	  $arr['incoterms'] = $value['incoterms'];
		    	 	  $arr['pay_terms'] = $value['pay_terms'];
		    	 	  $arr['freight'] = $value['freight'];
		    	 	  $arr['cus_grp'] = $value['cus_grp'];
		    	 	  $arr['fr_ind'] = $value['fr_ind'];


		    	 	  $sid = SalesContractMaterial::create($arr);
                      
                      foreach ($value['specs'] as $ke => $valu) {

                      	  $specs['mat_id'] = $sid['id'];
	                      $specs['comp'] = $valu['comp'];
	                      $specs['max'] = $valu['max'];
	                      $specs['min'] = $valu['min'];
	                      $specs['permissible'] = $valu['permissible'];
	                      $specs['uom'] = $valu['uom'];

	                      SalesContarctSpecs::create($specs);
                      }
                      

                       
                      // echo "<pre>";print_r($value['price_det']);exit();

                      

                      foreach ($value['price_det'] as $k => $v) {
                      	   

	                      $priceDet['mat_id'] = $sid['id'];
	                      $priceDet['mat_code'] = $value['mat_code'];
	                      $priceDet['cnty'] = $v['cnty'];
	                      $priceDet['des'] = $v['des'];
	                      $priceDet['amt'] = $v['amt'];


	                      ScPriceDetail::create($priceDet);
	                     
                      }


                       // echo "<pre>";print_r($specs);exit();

		    	 }
                  
		    	  return response()->json(['status'=>1,
					          'message' =>'success',
					          'result' => $contarctId],
					          config('global.success_status'));


	      }catch(\Exception $e){

			       return response()->json(['status'=>0,
			       	'message' =>'error',
			       	'result' => $e->getMessage()],config('global.failed_status'));
	     }
    }


     // ----------------------------- get all price braeks --------------------------------------------

	   public function priceBreakFetch($po_no)
	    {

	    	  try{ 
                   
                 	 $res = DB::table('orders')->leftjoin('sc_transactions','orders.rfq_no','sc_transactions.rfq_no')
                 	 ->leftjoin('plants','sc_transactions.plant','plants.id')
                 	 ->leftjoin('quotes','orders.rfq_no','quotes.rfq_no')
                 	 ->leftjoin('users','quotes.user_id','users.id')
                 	 ->groupBy('sc_transactions.mat_code')
                 	     ->select('sc_transactions.*','plants.code as pcode','users.name','orders.po_date')->where('orders.po_no',$po_no)->get();
                 	 // echo "<pre>";print_r($newcount);exit();
                 	 foreach ($res as $key => $value) {
                 	 	
                 	 	 $data[$key]['id'] = $value->id;
                 	 	 $data[$key]['code'] = $value->code;
                 	 	 $data[$key]['cus_name'] = $value->name;
                 	 	 $data[$key]['po_date'] = $value->po_date;
                 	 	 $data[$key]['value'] = $value->value;
                 	 	 $data[$key]['mat_code'] = $value->mat_code;
                 	 	 $data[$key]['pcode'] = $value->pcode;
                 	 	 $data[$key]['rfq_no'] = $value->rfq_no;
                 	 	 $data[$key]['price_det'] = $this->priceBreakById($value->mat_code);
                 	 	 $data[$key]['specs'] = $this->subcatspecs($value->mat_code);
                 	 	 $data[$key]['total'] = $this->totalRfqPrice($value->schedule);

           
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


	    public function totalRfqPrice($sche)
	    {
	    	      $tot_price = DB::table('quote_schedules')->where('schedule_no',$sche)
                 	  ->whereNull('deleted_at')->first();
                 	 // echo "<pre>";print_r($sche);exit();
                 	  if(!empty($tot_price))
                 	  {

			    	   $tot_price = $tot_price->quantity * $tot_price->kam_price;
			    	}else{

			    		 $tot_price = "";
			    	}

			    	return $tot_price;
	    }

        // ---------------------------- contarct no. update ---------------------------

	  	public function updateContarctsNo(Request $request)
	    {

	    	  try{   
	    	  	     $date = $request->input('date');
                     $dt = date("Y-m-d", strtotime($date));
                 	 SalesContarct::where('id',$request->input('id'))->update(['sc_no' => $request->input('sc_no'),'sc_dt' => $dt]);
			    	 
			        return response()->json(['status'=>1,
			          'message' =>'success',
			          'result' => 'Contract no. updated'],
			          config('global.success_status'));


	      }catch(\Exception $e){

	       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
	     }

	    	 
	    }
	    // ----------------------------------------------------------------------------------


	    // ---------------------------- prepare so list ---------------------------

	  	public function prepareSoList()
	    {

	    	  try{   
	    	  	     $res = DB::table('sales_contracts')->leftjoin('orders','sales_contracts.po_no','orders.po_no')->select('sales_contracts.*','orders.po_date')->get()->toArray();
			    	 
			        return response()->json(['status'=>1,
			          'message' =>'success',
			          'result' => $res],
			          config('global.success_status'));


	      }catch(\Exception $e){

	       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
	     }

	    	 
	    }
	    // --------------------------------------------------------------------


	 // ----------------------------- submit so -------------------------

     public function so_submit(Request $request)
      {

          try{ 
               
               $transact_id = $request->input('transact_id');   
               $co_no = $request->input('co_no');
               $po_no = $request->input('po_no');
               $pay_proc = $request->input('pay_proc');
               $fin_doc_no = $request->input('fin_doc_no');
               
                   // echo "<pre>";print_r($newcount);exit();
             
              return response()->json(['status'=>1,
                'message' =>'success',
                'result' => $res],
                config('global.success_status'));


        }catch(\Exception $e){

         return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
       }

         
      }

   // ---------------------------------------------------------------------------
}
