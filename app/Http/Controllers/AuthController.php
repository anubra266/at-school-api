<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        try {


            $request->validate([
                'firstName' => 'required',
                'middleName' => 'required',
                'lastName' => 'required',
                'email' => 'required|email',
                'telephone' => 'required',
                'dateOfBirth' => 'required',
                'password' => 'required|min:6',
                'profile_image' => 'required'
                ]);

                // Get image file
                //$image = $request->file('profile_image');
                $image_64 = $request->profile_image;
                $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
                $replace = substr($image_64, 0, strpos($image_64, ',')+1);
                // find substring fro replace here eg: data:image/png;base64,
                $image = str_replace($replace, '', $image_64);
                $image = str_replace(' ', '+', $image);
                $imageName = time().Str::random(10).'.'.$extension;
                Storage::disk('images')->put($imageName, base64_decode($image));


            $user = User::create([
                'firstName' => $request->firstName,
                'middleName' => $request->middleName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'dateOfBirth' => $request->dateOfBirth,
                'password' => bcrypt($request->password),
                'profile_image' => $imageName
            ]);
            //*attach role of new
            $newbie = Role::where('role','new')->first();
            $newbie_id = $newbie->id;
            $user->roles()->attach($newbie_id);
            return response()->json('User registered successfully',200);
        }

        //catch exception
        catch (Exception $e) {
            if ($e->getcode() == 23000) {
                return response()->json(['message' => 'Email already exists!'], 403);
            }
            return response()->json(["message"=>"All fields are required!"],500);
        }
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            $token = $user->createToken($user->email . '-' . now());

            return response()->json([
                'accessToken' => $token->accessToken
            ]);
        } else {
            return response()->json(['message' => 'Incorrect email or password!'], 404);
        }
    }
}
