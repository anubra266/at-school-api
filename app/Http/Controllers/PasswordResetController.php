<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Carbon\Carbon;
use App\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */

     

    public function create(Request $request)
    {
        $request->validate(["email" => "required|string|email"]);
        $user = User::where("email", $request->email)->first();
        if (!$user)
            return response()->json(["message" => "We can't find a user with that e-mail address."], 404);
        $passwordReset = PasswordReset::updateOrCreate(["email" => $user->email], ["email" => $user->email, "token" => Str::random(60)]);
        if ($user && $passwordReset)
            try {
                $user->notify(
                    new PasswordResetRequest($passwordReset->token, $request->red_link)
                );
                return response()->json(["message" => "We have e-mailed your password reset link!"]);
            } catch (Exception $e) {
                return response()->json(["message" => "An error occured. Check your Internet connection and try again later!"], 400);
            }
    }


    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->first();

        if (!$passwordReset) {
            return response()->json(["message" => "This password reset token is invalid."], 400);
        }
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(30)->isPast()) {
            $passwordReset->delete();
            return response()->json(["message" => "This password reset token is invalid."], 400);
        }
        return response()->json($passwordReset);
    }
    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] token
     */
    public function reset(Request $request)
    {
        $request->validate([
            "email" => "required|string|email",
            "password" => "required",
            "token" => "required|string"
        ]);
        $passwordReset = PasswordReset::where([
            ["token", $request->token],
            ["email", $request->email]
        ])->first();
        if (!$passwordReset) {
            return response()->json([
                "message" => "This password reset token is invalid."
            ], 404);
        }
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user) {
            return response()->json(["message" => "We could not find a user with that address"], 400);
        }
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();

        try {
            $user->notify(new PasswordResetSuccess($passwordReset));
            return response()->json(["message" => "Password reset was successful."]);
        } catch (Exception $e) {
            return response()->json(["message" => "An error occured. Try again later!"], 400);
        }
    }
}
