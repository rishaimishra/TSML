<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Response;
use App\User;
 
class UserController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
       $users = User::all();
 
       return $users;
   }
 
   /**
    * Store a newly created resource in storage.
    *
    */
   public function store(Request $request)
   {
        try {

            $validator = Validator::make($request->all(), [
                'name'        => 'required', 
                'email'        => 'required',   
                'password'        => 'required',   
            ]);

            if ($validator->fails()) {
                $response['error']['validation'] = $validator->errors();
                return Response::json($response);
            }

            $userData = $request->all();
            $user = User::create($userData);


            $response['success']['message']="User Created";
            return Response::json($response);

        } catch (\Throwable $th) {
            $response['error']['message'] = $th->getMessage();
            return Response::json($response);
        }
       
   }

   public function show($id)
    {
        try{
            $user_detail=User::where('id',$id)->first();
            if($user_detail){
                $response['success']['user_detail'] = $user_detail;
                return Response::json($response);
            }else{
                $response['error']['message'] = "This id does not exist";
                return Response::json($response);
            }
            
           } catch (\Throwable $th) {
            $response['error']['message'] = $th->getMessage();
            return Response::json($response);
           } 
    }
}