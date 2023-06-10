<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=> 'required:max:191',
            'email'=>'required|email|max:191|unique:users,email',
            'password'=>'required|min:8'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'validation_error'=>$validator->errors()->messages(),
            ]);
        }else{
            
            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
            ]);
            $token = $user->createToken($user->email. '_Token')->plainTextToken;
            return response()->json([
                'status'=>200,
                'username'=>$user->name,
                'token'=>$token,
                'message'=>"Register Successfully",
            ]);
        }
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' =>'',
            'password'=>'required|min:8'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'validation_error'=>$validator->errors()->messages()
            ]);
        }else{
            $user = User::where('email',$request->email)->first();
            $email = $request->email;
            
            $password= $request->password;
            if (! $user || ! Auth::attempt(['email'=>$email,'password'=>$password])) {
                return response()->json([
                    
                    'login_not_success'=> "Email or Password don't correct"
                ]);
            }else {
                if ($user->role_as === 1) {
                    $token = $user->createToken($user->email.'_AdminToken',['server:admin'])->plainTextToken;
                    
                } else {

                    $token = $user->createToken($user->email.'_Token',[''])->plainTextToken;
                }
                
                return response()->json([
                    'status' => 200,
                    'token'=>$token,
                    'name'=>$user->name,
                    'user_id'=>$user->id,
                ]);
            }
        }
    }
    public function logout()
    {
        Auth::user()->tokens->each(function($token, $key) {
            $token->delete();
        });
        return response()->json(['status'=>200]);

    }
}
