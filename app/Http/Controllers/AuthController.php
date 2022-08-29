<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use Validator;
 
class AuthController extends Controller
{
  public function register(Request $request)
  {
      // dd($request->all());
      // jwt key -  i4qBSBNxEQ6XHhJcykjX2PxeXmB9a1nVhZwMlXz4fwsBwCBCoKmkxDEhrXTeUHo0
      $validator = Validator::make($request->all(), [
          'email' => 'required|string|email|max:255|unique:users',
          'name' => 'required',
          'phone' => 'required',
          'password'=> 'required'
      ]);
      if ($validator->fails()) {
          return response()->json($validator->errors());
      }
      $user = new User();
      $user->name = $request->name;
      $user->email = $request->email;
      $user->phone = $request->phone;
      $user->password = bcrypt($request->password);
      $user->save();
      $user = User::first();
      $token = JWTAuth::fromUser($user);

      // dd($token);

      return response()->json([
          'success' => true,
          'token' => $token,
      ]);
  }

   /**
    * Get a JWT via given credentials.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function login(Request $request)
   {
       // $credentials = request(['email', 'password']);
 
       // if (! $token = auth()->attempt($credentials)) {
       //     return response()->json(['error' => 'Unauthorized'], 401);
       // }
 
       // return $this->respondWithToken($token);

       // dd($request->all());
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
 
        $input = $request->only('email', 'password');
        $jwt_token = null;
 
        if (!$jwt_token = JWTAuth::attempt($input)) {
         
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }
 
        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);
   }
 
   /**
    * Get the authenticated User.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function me()
   {
       return response()->json(auth()->user());
   }
 
   /**
    * Log the user out (Invalidate the token).
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function logout()
   {
       auth()->logout();
 
       return response()->json(['message' => 'Successfully logged out']);
   }
 
   /**
    * Refresh a token.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function refresh()
   {
       return $this->respondWithToken(auth()->refresh());
   }
 
   /**
    * Get the token array structure.
    *
    * @param  string $token
    *
    * @return \Illuminate\Http\JsonResponse
    */
   protected function respondWithToken($token)
   {
       return response()->json([
           'access_token' => $token,
           'token_type' => 'bearer',
           'expires_in' => auth()->factory()->getTTL() * 60
       ]);
   }
}