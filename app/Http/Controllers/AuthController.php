<?php

namespace App\Http\Controllers;

use File;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\CustomHelpers;
use Illuminate\Validation\Rule;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UseUpdateRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UseRegisterrRequest;
use App\Http\Requests\UseUpdateImageRequest;

class AuthController extends Controller
{
    public function register(UseRegisterrRequest $request)
    {
        $user =  User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'birthdate' => $request->birthdate,
            'avatar' => $request->avatar,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $tokens = $user->createToken('Android')->plainTextToken;
        $user->token = 'Bearer ' . $tokens;

        return  response()->json(['data' => $user, 'message' => 'you register success'], 200);
    }


    public function login(UserRequest $request)
    {

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (!Hash::check($request->password, $user->password)) {
                return  response()->json(['message' => 'wrong password'], 400);
            }

            $tokens = $user->createToken('Android')->plainTextToken;
            $user->token = 'Bearer ' . $tokens;

            return  response()->json(['data' => $user, 'message' => 'you login success'], 200);
        } else {
            return  response()->json(['message' => 'you are not register before'], 400);
        }
    }

    public function logout()
    {

        $user = Auth::guard('sanctum')->user();
        $user->tokens()->delete();
        return  response()->json(['message' => 'logout success'], 200);
    }



    public function updateUserInfo(UseUpdateRequest $request)
    {
            $user=Auth::user();

            if($request->hasFile('avatar')){
                if(File::exists(public_path('images/'. $user->avatar))) {
                    File::delete(public_path('images/'. $user->avatar));
                  }

                  $path = public_path('images/');
                  !is_dir($path) &&
                  mkdir($path, 0777, true);
                  $imageName = time() . '.' . $request->avatar->extension();
                  $request->avatar->move($path, $imageName);
            }


            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'birthdate' => $request->birthdate,
                'avatar' => $imageName,
                'password' => Hash::make($request->password),
            ]);

            

            return  response()->json(['message' => $user], 200);


    }




}
