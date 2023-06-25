<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\CustomHelpers;
use Illuminate\Validation\Rule;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UseRegisterrRequest;
use App\Http\Requests\UseUpdateImageRequest;
use Illuminate\Support\Facades\Storage;

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

    // $image_path = public_path('images/'.'1687683145.jpg');
    // if(File::exists($image_path)) {
    //   File::delete($image_path);
    // }

    public function updateImage(UseUpdateImageRequest $request)
    {

        $path = public_path('images/');
        !is_dir($path) &&
            mkdir($path, 0777, true);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move($path, $imageName);
             
                $user=Auth::user();
                $user->update(['avatar' =>$imageName]);

        return  response()->json(['message' => $user], 200);


    }




}
