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
          $ids = array();
    	 foreach ($request->Items as $key => $value) {
    	 	
    	 	  // echo "<pre>";print_r($value['item']);exit();
    	 	$data['ContractType'] = $request->OrganizationalData['ContractType'];
    	 	$data['SalesOrganization'] = $request->OrganizationalData['SalesOrganization'];
    	 	$data['DistributionChannel'] = $request->OrganizationalData['DistributionChannel'];
    	 	$data['Division'] = $request->OrganizationalData['Division'];
    	 	$data['ContractValidFrom'] = $request->OrganizationalData['ContractValidFrom'];
    	 	$data['ContractValidTo'] = $request->OrganizationalData['ContractValidTo'];
    	 	$data['Salesoffice'] = $request->OrganizationalData['Salesoffice'];
    	 	$data['Salesgroup'] = $request->OrganizationalData['Salesgroup'];
    	 	$data['Incoterms'] = $request->OrganizationalData['Incoterms'];
    	 	$data['Paymentterms'] = $request->OrganizationalData['Paymentterms'];


    	 	$data['QtyContractTSML'] = $request->SoldToParty['QtyContractTSML'];
    	 	$data['Sold_To_Party'] = $request->SoldToParty['Sold_To_Party'];
    	 	$data['Ship_To_Party'] = $request->SoldToParty['Ship_To_Party'];
    	 	$data['Cust_Referance'] = $request->SoldToParty['Cust_Referance'];
    	 	$data['NetValue'] = $request->SoldToParty['NetValue'];
    	 	$data['Cust_Ref_Date'] = $request->SoldToParty['Cust_Ref_Date'];


    	 	$data['Shp_Cond'] = $request->Sales['Shp_Cond'];


    	 	$data['item'] = $value['item'];
    	 	$data['Material'] = $value['Material'];
    	 	$data['Quantity'] = $value['Quantity'];
    	 	$data['CustomarMaterialNumber'] = $value['CustomarMaterialNumber'];
    	 	$data['OrderQuantity'] = $value['OrderQuantity'];
    	 	$data['Plant'] = $value['Plant'];


    	 	$data['ItemNumber'] = $request->Conditions[$key]['ItemNumber'];
    	 	$data['CnTy'] = $request->Conditions[$key]['CnTy'];
    	 	$data['Amount'] = $request->Conditions[$key]['Amount'];



            $data['Freight'] = $request->AdditionalDataA['Freight'];
    	 	$data['CustomerGroup4'] = $request->AdditionalDataA['CustomerGroup4'];
    	 	$data['FreightIndicator'] = $request->AdditionalDataforPricing['FreightIndicator'];

    	 	$data['date'] = date('Y-m-d');

            $res = DB::table('sc_excel_datas')->insertGetId($data);

            array_push($ids, $res);

    	 }

    	 // echo "<pre>";print_r($data);exit();

 
    	 	$this->scExceldown($ids);
    	 	return response()->json(['status'=>1, 'message' =>'success','result' => $ids],config('global.success_status'));
    	 
    }


    public function scExceldown($ids)
    {  
    	// $ids = [1,2];
    	// dd($ids);
    	return Excel::download(new ExportDocs($ids), 'sc.xlsx');
    }
}
