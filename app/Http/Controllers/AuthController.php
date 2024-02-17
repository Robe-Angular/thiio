<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Consumer;
use App\Http\Helper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        
        
        $params = Helper::get_params($request);
        $params_array = (array) $params;

        //$credentials = $request->only('email', 'password');
        
        //return response()->json(['error' => $params, 'credentials' => $credentials], 401);

        $user_role = '';
        
        $user_updated = Consumer::where('email',$params->email)->update([
            'email_verified_at' => date('Y/m/d H:i:s',time())
        ]);

        if ($token = auth('consumer')->attempt($params_array)) {
            $user_role = 'consumer';
            return response()->json([
                'token' => $token,
                'user_role' => $user_role
            ]);
        }else{
            if ($token = auth('admin')->attempt($params_array)) {
                $user_role = 'admin';
                return response()->json([
                    'token' => $token,
                    'user_role' => $user_role
                ]);
            }   
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(Request $request){
        
        $params = Helper::get_params($request);
        $params_array = (array) $params;
        
        $validator = \Illuminate\Support\Facades\Validator::make($params_array,[
            'name' => 'required|string|unique:consumers|max:255',
            'email' => 'required|email|unique:consumers|max:255',
            'password' => 'required|min:5'
        ]);
        
        if($validator->fails()){
            $errors = $validator->errors();
            return response()->json([
                'error' => $errors
            ], 400);
        }
        
        if($validator->passes()){
            $user = Consumer::create([
                'name' => $params->name,
                'email' => $params->email,
                'password' => \Illuminate\Support\Facades\Hash::make($params->password)
            ]);
            return response()->json([   
                'success' =>'success'
            ],200);
        }else{
            return response()->json([
                'message' => 'no admin'
            ], 400);
        }
    }
    
}
