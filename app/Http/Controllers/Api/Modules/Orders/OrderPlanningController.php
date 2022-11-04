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
use App\Models\DailyProduction;
use DB;

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
        	 // return $request->all();exit;
            $response = [];
            try{

            	$start = $request->input('start');
            	$end = $request->input('end');
            
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadsheet = $reader->load($request->excel);
                $sheetData = $spreadsheet->getActiveSheet()->toArray();
                // return $sheetData;
                $removed = array_shift($sheetData);
                $data = json_encode($sheetData);

                foreach($sheetData as $k => $val)
                {
                   
                    // return $val[5];
                        $user = array();
  
                        
                        $user['plant'] = $val[0];
                        $user['category'] = $val[1];
                        $user['subcategory'] = $val[2];
                        $user['met_group'] = $val[3];
                        $user['met_no'] = $val[4];
                        $user['grade_code'] = $val[5];
                        $user['size'] = $val[6];
                        $user['qty'] = $val[7];
                        $user['start'] = date("Y-m-d", strtotime($start));
                        $user['end'] = date("Y-m-d", strtotime($end));
                        
                        DailyProduction::create($user);
              
                }

                $response['success'] = true;
                $response['message'] = 'Daily Production Uploaded Successfully';
                return Response::json($response);

             
            
            }catch(\Exception $e){
                $response['error'] = $e->getMessage();
                return Response::json($response);
            }
        }


       public function getOrderPlanning()
       {
       	   try{ 

               $res = DB::table('monthly_production_plans')
                ->leftJoin('daily_productions', function($join)
                         {
                             $join->on('monthly_production_plans.plant', '=', 'daily_productions.plant');
                             $join->on('monthly_production_plans.start_date','=','daily_productions.start');
                             $join->on('monthly_production_plans.end_date','daily_productions.end');
                             $join->on('monthly_production_plans.size','daily_productions.size');
                         })
               ->select('monthly_production_plans.open_stk','monthly_production_plans.mnthly_prod','daily_productions.*')->get();
               echo "<pre>";print_r($res);exit();

		        return response()->json(['status'=>1,
		          'message' =>'success',
		          'result' => 'Quote created'],
		          config('global.success_status'));

		      }catch(\Exception $e){

		       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
		     }
       }
}
