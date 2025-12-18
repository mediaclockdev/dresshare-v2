<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OtpPasswordController extends Controller
{
    private $otpExpireMinutes = 15;

    /**
     * STEP 1 — Request OTP
     */
    public function requestOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        // Find user (do not expose if email doesn't exist)
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Please enter a registered email ID.'
            ]);
        }

        // Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // Store hashed OTP in password_resets table
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => Hash::make($otp),
            'created_at' => Carbon::now()
        ]);

        // Send OTP email
        Mail::to($email)->send(new SendOtpMail($otp));

        return response()->json([
            'status' => true,
            'message' => 'If this email exists, an OTP has been sent.'
        ]);
    }

    /**
     * STEP 2 — Reset Password using OTP
     */
    public function resetWithOtp(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'otp'      => 'required|string',
            'password' => 'required|min:8|confirmed'
        ]);

        $email = $request->email;
        $otp   = $request->otp;

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid or expired OTP.'
            ], 400);
        }

        // Check expiry
        if (Carbon::parse($record->created_at)->addMinutes($this->otpExpireMinutes)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $email)->delete();
            return response()->json([
                'status'  => false,
                'message' => 'OTP expired. Please request a new one.'
            ], 400);
        }

        // Validate OTP
        if (!Hash::check($otp, $record->token)) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid OTP.'
            ], 400);
        }

        // Reset password
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found.'
            ], 404);
        }

        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Delete OTP record
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Optional: delete existing Sanctum tokens
        if (method_exists($user, 'tokens')) {
            $user->tokens()->delete();
        }

        return response()->json([
            'status'  => true,
            'message' => 'Password reset successful.'
        ]);
    }
}
