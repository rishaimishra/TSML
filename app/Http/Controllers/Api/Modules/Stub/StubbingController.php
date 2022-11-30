<?php

namespace App\Http\Controllers\Api\Modules\Stub;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StubbingController extends Controller
{
    public function gstDetailsDummy()
    {
    	  $result['stjCd'] = "MHCG0674";
    	  $result['lgnm'] = "REYNOLDS PENS INDIA PRIVATE LIMITED";
    	  $result['stj'] = "SAKINAKA_703";
    	  $result['dty'] = "Input Service Distributor (ISD)";
    	  $result['adadr'] = [];
    	  $result['cxdt'] = "MHCG0674";
    	  $result['gstin'] = "27AABCR4412R1Z1";
    	  $result['nba'] = [
                             "Recipient of Goods or Services"
                          ];
    	  $result['lstupdt'] = "07/09/2019";
    	  $result['rgdt'] = "07/09/2019";
    	  $result['ctb'] = "Private Limited Company";
    	  $result['pradr'] = $this->demo_json();
    	  $result['tradeNam'] = "REYNOLDS PENS INDIA PRIVATE LIMITED";
    	  $result['sts'] = "Active";
    	  $result['ctjCd'] = "VM0804";
    	  $result['ctj'] = "VM0804";



		  return response()->json(['status'=>1,
          'message' =>'success',
          'result' => $result],
          config('global.success_status'));
    }

    public function demo_json(){


        $data['addr']['bnm'] = "Universal Business Park";
        $data['addr']['st']  = "Chandvli Farm road, off saki viha road";
        $data['addr']['loc']  = "Sakinaka. Andheri east";
        $data['addr']['bno']  = "204 - B wing";
        $data['addr']['dst']  = "Mumbai Suburban";
        $data['addr']['stcd'] = "Maharashtra";
        $data['addr']['city'] = "";
        $data['addr']['flno']  = "2nd floor";
        $data['addr']['lt']  ="";
        $data['addr']['pncd'] = "400072";
        $data['addr']['lg'] = "";
        $data['ntr'] = "Recipient of Goods or Services";

        return $data;
    }
}
