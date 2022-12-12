<?php

namespace App\Http\Controllers\API\Landlone;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\LUser;
use Hash;

class LandloneAuthController extends Controller
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
            $luser = LUser::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
            ]);

            return response()->json([
                'status' => 200,
                'username' => $luser->first_name,
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
            $luser = LUser::where('email',$request->email)->first();
            if(! $luser || ! Hash::check($request->password, $luser->password))
            {
                return response()->json([
                    'status' => 401,
                    'message' => 'Invaild Credentails'
                ]);
            }
            else
            {
                $token = $luser->createToken($luser->email.'_Token')->plainTextToken;

                return response()->json([
                    'status' => 200,
                    'username' => $luser->first_name,
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
