<?php

namespace App\Http\Controllers\Api\Modules\Bulk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JWTAuth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Models\SapSalesOrganization;
use App\Models\SapIncoterms;
use App\Models\SapPaymentTerms;
use App\Models\SapOrderType;
use Response;
use Hash;
class BulkController extends Controller
{
    public function storeUser(Request $request)
    {
        $response = [];
        try{
         if ($request->hasFile('excel'))
         {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($request->excel);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            // return $sheetData;
            $removed = array_shift($sheetData);
            $data = json_encode($sheetData);

            foreach($sheetData as $val)
            {
                $mailCheck = User::where('email',$val[1])->first();
                // return $val[3];
                // return \Hash::make($val[3]);
                if (@$mailCheck=='') {
                    $user = new User;
                    $user->name = $val[0];
                    $user->email = $val[1];
                    $user->phone = $val[2];
                    $user->password = $val[3];
                    $user->city = $val[4];
                    $user->state = $val[5];
                    $user->pincode = $val[6];
                    $user->user_type = 'KAM';
                    $user->save();
                }
            }

            $response['success'] = true;
            $response['message'] = 'User Uploaded Successfully';
            return Response::json($response);

         }else{
            $response['success'] = false;
            $response['message'] = 'No Excel File Found';
            return Response::json($response);
         }   
         

         
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }

    public function storExceleData(Request $request)
    {
        // dd('store-excel-data');
        $response = [];
        try{
         if ($request->hasFile('excel'))
         {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($request->excel);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            // return $sheetData;
            $removed = array_shift($sheetData);
            $data = json_encode($sheetData);
            // dd($data);
            foreach($sheetData as $val)
            {
                 
                    $user = new SapOrderType;
                    $user->order_type_code = $val[0];
                    $user->order_type_dec = $val[1];                    
                    $user->save();
                
            }

            $response['success'] = true;
            $response['message'] = 'Excel uploaded successfully';
            return Response::json($response);

         }else{
            $response['success'] = false;
            $response['message'] = 'No Excel File Found';
            return Response::json($response);
         }   
         

         
        
        }catch(\Exception $e){
            $response['error'] = $e->getMessage();
            return Response::json($response);
        }
    }
}
