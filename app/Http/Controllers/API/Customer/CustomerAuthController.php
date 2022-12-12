<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CUser;
use Hash;

class CustomerAuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'password' => 'required|min:8',
            'phone' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'validation_error' => $validator->message(),
            ]);
        }
        else
        {
            $cuser = CUser::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
            ]);

            return response()->json([
                'status' => 200,
                'username' => $cuser->first_name,
                'message' => 'Registered Successfully'
            ]);
        }
    }

    public function login(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'validation_error' => $validator->message()
            ]);
        }
        else
        {
            $cuser = CUser::where('email',$request->email)->first();
            if(! $cuser || ! Hash::check($request->password, $cuser->password))
            {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invaild Credentails'
                ]);
            }
            else
            {
                $token = $cuser->createToken($cuser->email.'_Token')->plainTextToken;

                return response()->json([
                    'status' => 200,
                    'username' => $cuser->first_name,
                    'token' => $token,
                    'message' => 'You are Logged In'
                ]);
            }
        }
    }

    public function logout(Request $request){

        auth()->user()->tokens()->delete();

        return [
            'message' => 'user logged out'
        ];
    }
}
