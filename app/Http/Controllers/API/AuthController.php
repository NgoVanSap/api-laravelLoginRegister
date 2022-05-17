<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $rules = [
            'name'      => 'unique:users|required',
            'email'     => 'required_without:phone|unique:users|required',
            'password'  => 'required|min:6',
            'phone'     => 'required_without:phone|unique:users|required|numeric',
        ];

        $input     = $request->only('name', 'email','password','phone');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }
        $name     = $request->name;
        $email    = $request->email;
        $password = $request->password;
        $phone    = $request->format_phone_number($data['phone']);
        $user     = User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password),'phone' => $phone]);

        if($user) {
            return response()->json([
                'success' => 'Register Thành công!',
            ]);
        }

    }

    public function login(Request $request){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $tokenResult =  $user->createToken('MyApp')->accessToken;
            return response()->json([
                'success' => 'Login thành công!',
                '_token'  => $tokenResult->token,
                'user'    => $user,
            ]);
        }
        else{
            return response()->json(['error'=>'Tài khoản hoặc mật khẩu sai!'], 401);
        }

    }
}
