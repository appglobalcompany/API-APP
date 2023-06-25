<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'regex:/^[\p{Arabic}A-Za-z]+$/u'],
            'last_name' => ['required', 'string', 'regex:/^[\p{Arabic}A-Za-z]+$/u'],
            'phone' => ['required', 'numeric'],
            'gender' => ['required', 'string', Rule::in(['mela', 'female'])],
            'birthdate' => ['required', 'string', 'date'],
            'avatar' => ['nullable', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        
        $user =  User::create([
            'first_name'=> $request->first_name,
            'last_name'=> $request->last_name,
            'phone'=> $request->phone,
            'gender'=> $request->gender,
            'birthdate'=> $request->birthdate,
            'avatar'=> $request->avatar,
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);

        $tokens = $user->createToken('Android')->plainTextToken;
        $user->token = 'Bearer ' . $tokens;

        return  response()->json(['data'=>$user,'message'=>'you register success'], 200);

    }
}
