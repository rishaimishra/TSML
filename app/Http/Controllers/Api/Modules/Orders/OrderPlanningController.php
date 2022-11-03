<?php

namespace App\Http\Controllers\Api\Modules\Orders;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Monthlyproductionplan;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use Tymon\JWTAuth\Exceptions\JWTException;
use Response;

class OrderPlanningController extends Controller
{
    public function monthlyPlanSubmit(Request $request)
    {
    	 // echo "<pre>";print_r($request->all());exit();

      try{ 

             
                $data = $request->all();
                $data['start_date'] = date("Y-m-d", strtotime($data['start_date']));
                $data['end_date'] = date("Y-m-d", strtotime($data['end_date']));
                $data['status'] = 0;

                $res = Monthlyproductionplan::create($data);

		        return response()->json(['status'=>1,
		          'message' =>'success',
		          'result' => 'Production plan created'],
		          config('global.success_status'));
       


      }catch(\Exception $e){

       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
     }
    }

         public function prodQtyUpload(Request $request)
        {
        	 // return $request->input('excel');exit;
            $response = [];
            try{
             // if ($request->hasFile('excel'))
             // {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadsheet = $reader->load($request->excel);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                // return $sheetData;
                $removed = array_shift($sheetData);
                $data = json_encode($sheetData);

                foreach($sheetData as $val)
                {
                    $mailCheck = User::where('email',$val[1])->first();
                    return $val[3];
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

             
            
            }catch(\Exception $e){
                $response['error'] = $e->getMessage();
                return Response::json($response);
            }
        }
}
