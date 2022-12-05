<?php

namespace App\Http\Controllers\Api\Modules\Notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Camnotification;
use DB;

class NotificationController extends Controller
{
    public function camNotificationSubmit(Request $request)
    {

    	  try{ 

                 $data = array();
		    	 $ids = array();
		         
		         $url_type = $request->input('url_type');
		         if($url_type = 'R')
		         {
		            $res = DB::table('quotes')->leftjoin('users','quotes.user_id','users.id')
		            ->select('users.zone')
		            ->where('quotes.rfq_no',$request->input('desc_no'))
		            ->where('quotes.rfq_no','=',$request->input('desc_no'))
		            ->whereNull('quotes.deleted_at')->first();
		         }

		    	 $data['desc'] = $request->input('desc');
		    	 $data['desc_no'] = $request->input('desc_no');
		    	 $data['user_id'] = $request->input('user_id');
		    	 $data['url_type'] = $request->input('url_type');
		    	 $data['status'] = 1;
		    	 $data['sender_ids'] = $res->zone;

		    	 Camnotification::create($data);

		    	 // echo "<pre>";print_r($res->zone);exit();
		        return response()->json(['status'=>1,
		          'message' =>'success',
		          'result' => 'Notification submitted'],
		          config('global.success_status'));


      }catch(\Exception $e){

       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
     }

    	 
    }



    public function getCamNotification($id)
    {

    	  try{ 

                 $data = array();

		         $res = 
		         
		    	 $data['desc'] = $request->input('desc');
		    	 $data['desc_no'] = $request->input('desc_no');
		    	 $data['user_id'] = $request->input('user_id');
		    	 $data['url_type'] = $request->input('url_type');
		    	 $data['status'] = 1;
		    	 $data['sender_ids'] = $res->zone;


		    	 // echo "<pre>";print_r($res->zone);exit();
		        return response()->json(['status'=>1,
		          'message' =>'success',
		          'result' => 'Notification submitted'],
		          config('global.success_status'));


      }catch(\Exception $e){

       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
     }

    	 
    }




}
