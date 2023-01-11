<?php

namespace App\Http\Controllers\Api\Modules\Temp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportDocs;
use DB;

class TemporaryController extends Controller
{
    public function scExcelSubmit(Request $request)
    {
    	 // echo "<pre>";print_r($request->OrganizationalData['ContractType']);exit();

    	 foreach ($request->Items as $key => $value) {
    	 	
    	 	  // echo "<pre>";print_r($value['item']);exit();
    	 	$data[$key]['ContractType'] = $request->OrganizationalData['ContractType'];
    	 	$data[$key]['SalesOrganization'] = $request->OrganizationalData['SalesOrganization'];
    	 	$data[$key]['DistributionChannel'] = $request->OrganizationalData['DistributionChannel'];
    	 	$data[$key]['Division'] = $request->OrganizationalData['Division'];
    	 	$data[$key]['ContractValidFrom'] = $request->OrganizationalData['ContractValidFrom'];
    	 	$data[$key]['ContractValidTo'] = $request->OrganizationalData['ContractValidTo'];
    	 	$data[$key]['Salesoffice'] = $request->OrganizationalData['Salesoffice'];
    	 	$data[$key]['Salesgroup'] = $request->OrganizationalData['Salesgroup'];
    	 	$data[$key]['Incoterms'] = $request->OrganizationalData['Incoterms'];
    	 	$data[$key]['Paymentterms'] = $request->OrganizationalData['Paymentterms'];


    	 	$data[$key]['QtyContractTSML'] = $request->SoldToParty['QtyContractTSML'];
    	 	$data[$key]['Sold_To_Party'] = $request->SoldToParty['Sold_To_Party'];
    	 	$data[$key]['Ship_To_Party'] = $request->SoldToParty['Ship_To_Party'];
    	 	$data[$key]['Cust_Referance'] = $request->SoldToParty['Cust_Referance'];
    	 	$data[$key]['NetValue'] = $request->SoldToParty['NetValue'];
    	 	$data[$key]['Cust_Ref_Date'] = $request->SoldToParty['Cust_Ref_Date'];


    	 	$data[$key]['Shp_Cond'] = $request->Sales['Shp_Cond'];


    	 	$data[$key]['item'] = $value['item'];
    	 	$data[$key]['Material'] = $value['Material'];
    	 	$data[$key]['Quantity'] = $value['Quantity'];
    	 	$data[$key]['CustomarMaterialNumber'] = $value['CustomarMaterialNumber'];
    	 	$data[$key]['OrderQuantity'] = $value['OrderQuantity'];
    	 	$data[$key]['Plant'] = $value['Plant'];


    	 	$data[$key]['ItemNumber'] = $request->Conditions[$key]['ItemNumber'];
    	 	$data[$key]['CnTy'] = $request->Conditions[$key]['CnTy'];
    	 	$data[$key]['Amount'] = $request->Conditions[$key]['Amount'];



            $data[$key]['Freight'] = $request->AdditionalDataA['Freight'];
    	 	$data[$key]['CustomerGroup4'] = $request->AdditionalDataA['CustomerGroup4'];
    	 	$data[$key]['FreightIndicator'] = $request->AdditionalDataforPricing['FreightIndicator'];

    	 	$data[$key]['date'] = date('Y-m-d');



    	 }

    	 // echo "<pre>";print_r($data);exit();

    	 $res = DB::table('sc_excel_datas')->insert($data);

    	 if($res)
    	 { 
    	 	 // Excel::download(new ExportDocs, 'sc.xlsx');
    	 	return response()->json(['status'=>1, 'message' =>'success','result' => 'Submitted'],config('global.success_status'));
    	 }
    }


    public function scExceldown()
    {  
    	// dd('hi');
    	return Excel::download(new ExportDocs, 'sc.xlsx');
    }
}
