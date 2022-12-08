<?php

namespace App\Http\Controllers\Api\Modules\Notification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Camnotification;
use App\Models\CusNotification;
use App\Models\SalesNotification;
use DB;

class NotificationController extends Controller
{
    public function camNotificationSubmit(Request $request)
    {

    	  try{ 
                 
                 $data = array();
		    	
		         
		         $url_type = $request->input('url_type');
		         // echo "<pre>";print_r($url_type);exit();
		         if($url_type == "R")
		         {

		            $res = DB::table('quotes')->leftjoin('users','quotes.user_id','users.id')
		            ->select('users.zone')
		            ->where('quotes.rfq_no',$request->input('desc_no'))
		            ->whereNull('quotes.deleted_at')->first();

                     $zone = $res->zone;
		         }
		         

		         else{
                     
                     $desc_no = DB::table('orders')->where('po_no',$request->input('desc_no'))->first();
                     
		         	 $resA = DB::table('quotes')->leftjoin('users','quotes.user_id','users.id')
		            ->select('users.zone')
		            ->where('quotes.rfq_no',$desc_no->rfq_no)
		            ->whereNull('quotes.deleted_at')->first();
		            // echo "<pre>";print_r($resA);exit();

		            $zone = $resA->zone;
		         }

		    	 $data['desc'] = $request->input('desc');
		    	 $data['desc_no'] = $request->input('desc_no');
		    	 $data['user_id'] = $request->input('user_id');
		    	 $data['url_type'] = $request->input('url_type');
		    	 $data['status'] = 1;
		    	 $data['sender_ids'] = $zone;

		    	 Camnotification::create($data);

		    	 // echo "<pre>";print_r($data);exit();
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

		         $res = DB::table('users')->leftjoin('cam_notifications','users.zone','cam_notifications.sender_ids')
                 ->leftjoin('users as cus_user','cam_notifications.user_id','cus_user.id')
		         ->select('cam_notifications.*','cus_user.name as cus_name')->where('users.id',$id)
		         ->where('cam_notifications.status',1)
		         ->get();

		         foreach ($res as $key => $value) {
		         	 
		         	 $data[$key]['id'] = $value->id;
		         	 $data[$key]['desc'] = $value->desc;
			    	 $data[$key]['desc_no'] = $value->desc_no;
			    	 $data[$key]['user_id'] = $value->cus_name;
			    	 $data[$key]['url_type'] = $value->url_type;
			    	 
			    	 
		         }
		         
		    	 


		    	 // echo "<pre>";print_r($id);exit();
		        return response()->json(['status'=>1,
		          'message' =>'success',
		          'result' => $data],
		          config('global.success_status'));


      }catch(\Exception $e){

       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
     }

    	 
    }


    public function cusNotificationSubmit(Request $request)
    {

    	  try{ 
                 
                 $data = array();
		    	
		        
		         // echo "<pre>";print_r($url_type);exit();

		    	 $data['desc'] = $request->input('desc');
		    	 $data['desc_no'] = $request->input('desc_no');
		    	 $data['user_id'] = $request->input('user_id');
		    	 $data['url_type'] = $request->input('url_type');
		    	 $data['status'] = 1;
		    	 $data['sender_ids'] = $request->input('sender_id');

		    	 CusNotification::create($data);

		    	 // echo "<pre>";print_r($data);exit();
		        return response()->json(['status'=>1,
		          'message' =>'success',
		          'result' => 'Notification submitted'],
		          config('global.success_status'));


      }catch(\Exception $e){

       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
     }

    	 
    }


    public function getCusNotification($id)
    {

    	  try{ 

                 $data = array();

		         $res = DB::table('cus_notifications')
		         ->leftjoin('users','cus_notifications.user_id','users.id')
		         ->select('cus_notifications.*','users.name as uname')
		         ->where('cus_notifications.sender_ids',$id)
		         ->where('cus_notifications.status',1)
		         ->get();

		         foreach ($res as $key => $value) {
		         	 
		         	 $data[$key]['id'] = $value->id;
		         	 $data[$key]['desc'] = $value->desc;
			    	 $data[$key]['desc_no'] = $value->desc_no;
			    	 $data[$key]['user_id'] = $value->uname;
			    	 $data[$key]['url_type'] = $value->url_type;
			    	 
			    	 
		         }
		         
		    	 


		    	 // echo "<pre>";print_r($id);exit();
		        return response()->json(['status'=>1,
		          'message' =>'success',
		          'result' => $data],
		          config('global.success_status'));


      }catch(\Exception $e){

       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
     }

    	 
    }

    


    public function salesNotificationSubmit(Request $request)
    {

    	  try{ 
                 
                 $data = array();
		    	
		        
		         // echo "<pre>";print_r($url_type);exit();

		    	 $data['desc'] = $request->input('desc');
		    	 $data['desc_no'] = $request->input('desc_no');
		    	 $data['user_id'] = $request->input('user_id');
		    	 $data['url_type'] = $request->input('url_type');
		    	 $data['status'] = 1;
		   

		    	 SalesNotification::create($data);

		    	 // echo "<pre>";print_r($data);exit();
		        return response()->json(['status'=>1,
		          'message' =>'success',
		          'result' => 'Notification submitted'],
		          config('global.success_status'));


      }catch(\Exception $e){

       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
     }

    	 
    }




    public function getSalesNotification()
    {

    	  try{ 

                 $data = array();

		         $res = DB::table('sales_notifications')
		         ->leftjoin('users','sales_notifications.user_id','users.id')
		         ->select('sales_notifications.*','users.name')
		         ->where('sales_notifications.status',1)->get();
		         

		         foreach ($res as $key => $value) {
		         	 
		         	 $data[$key]['id'] = $value->id;
		         	 $data[$key]['desc'] = $value->desc;
			    	 $data[$key]['desc_no'] = $value->desc_no;
			    	 $data[$key]['user_id'] = $value->name;
			    	 $data[$key]['url_type'] = $value->url_type;
			    	 
			    	 
		         }
		         
		    	 


		    	 // echo "<pre>";print_r($id);exit();
		        return response()->json(['status'=>1,
		          'message' =>'success',
		          'result' => $data],
		          config('global.success_status'));


      }catch(\Exception $e){

       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
     }

    	 
    }



    public function upCamNoti(Request $request)
    {

    	  try{ 

                 
               Camnotification::where('id',$request->input('id'))->update(['status' => $request->input('status')]);

		    	 // echo "<pre>";print_r($id);exit();
		        return response()->json(['status'=>1,
		          'message' =>'success',
		          'result' => 'Notification updated'],
		          config('global.success_status'));


	      }catch(\Exception $e){

	       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
	     }
    }


     public function upCusNoti(Request $request)
    {

    	  try{ 

                 
               CusNotification::where('id',$request->input('id'))->update(['status' => $request->input('status')]);

		    	 // echo "<pre>";print_r($id);exit();
		        return response()->json(['status'=>1,
		          'message' =>'success',
		          'result' => 'Notification updated'],
		          config('global.success_status'));


	      }catch(\Exception $e){

	       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
	     }
   }


    public function upSalesNoti(Request $request)
    {

    	  try{ 

                 
               SalesNotification::where('id',$request->input('id'))->update(['status' => $request->input('status')]);

		    	 // echo "<pre>";print_r($id);exit();
		        return response()->json(['status'=>1,
		          'message' =>'success',
		          'result' => 'Notification updated'],
		          config('global.success_status'));


	      }catch(\Exception $e){

	       return response()->json(['status'=>0,'message' =>'error','result' => $e->getMessage()],config('global.failed_status'));
	     }
    }

    	 
    




}
