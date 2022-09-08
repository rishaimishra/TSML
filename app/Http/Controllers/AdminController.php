<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Response;

class AdminController extends Controller
{
    public function demo(){
        return 111444;
    }

    /**
    * Log the user out (Invalidate the token).
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function logout()
   {
       auth('admins')->logout();
 
       return response()->json(['message' => 'Successfully logged out']);
   }

   // --------- user status update------------------
   public function userStatusUpdate(Request $request)
   {
     

      try{
            
           $user_id = $request->input('user_id');
           $status = $request->input('status');
           $user = User::find($user_id);

           if(!empty($user) && isset($user))
           {
               
                if(!empty($status))
                 {
                    $userArr = array();
                    $update=User::where('id',$user_id)->update(['user_status' => $status]);
                    $fetch_user=User::where('id',$user_id)->get();
                    // return  $fetch_user;die;
                    
                    $response['data']['message'] = "User status updated";
                    $response['data']['region'] = $fetch_user;
                    return Response::json($response); 
                 }
                 else{

                   $response['data']['message'] = "No status provided";
                   $response['data']['region'] = [];
                    return Response::json($response);
                }

           }
           else{

               $response['data']['message'] = "This id does not exists for update";
               $response['data']['region'] = [];
                return Response::json($response);
           }
            
            
       } catch (\Throwable $th) {
          $response['data']['message'] = $th->getMessage();
          return Response::json($response);
       } 


   }

 
    
}
