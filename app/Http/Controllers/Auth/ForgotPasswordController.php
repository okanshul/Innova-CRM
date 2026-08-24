<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\AuditLog;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password request form.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password request and send OTP via email.
     */
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower(trim($request->email));
        $user = User::where('email', $email)->first();

        if ($user && $user->status === 'active') {
            // Invalidate any existing unused OTPs for this email
            PasswordResetOtp::where('email', $email)
                ->where('used', false)
                ->update(['used' => true]);

            // Generate a 6-digit numeric OTP
            $otpCode = sprintf('%06d', random_int(0, 999999));

            // Store in database
            PasswordResetOtp::create([
                'user_id' => $user->id,
                'email' => $email,
                'otp' => $otpCode,
                'attempts' => 0,
                'used' => false,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]);

            // Send email using system SMTP configuration
            try {
                Mail::to($email)->send(new PasswordResetOtpMail($otpCode, 10));
                AuditLog::record("Password reset OTP sent to email: {$email}", "Auth", $user->id);
            } catch (\Throwable $e) {
                AuditLog::record("Failed to send password reset OTP mail to: {$email}. Error: " . $e->getMessage(), "Auth", $user->id);
            }
        } else {
            // Log for non-existent email attempt without revealing existence to the caller
            AuditLog::record("Password reset requested for non-existent or inactive email: {$email}", "Auth");
        }

        // Store email in session for OTP verification phase
        session(['reset_email' => $email]);

        return redirect()->route('password.verify')->with('status', 'If an account with that email address exists, a 6-digit verification code has been sent to your email.');
    }

    /**
     * Show the OTP verification form.
     */
    public function showVerifyForm()
    {
        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Please request a password reset code first.']);
        }

        $latestOtp = PasswordResetOtp::where('email', $email)
            ->orderBy('created_at', 'desc')
            ->first();

        $cooldown = 0;
        if ($latestOtp) {
            $secondsSinceCreation = Carbon::now()->diffInSeconds($latestOtp->created_at);
            if ($secondsSinceCreation < 60) {
                $cooldown = 60 - $secondsSinceCreation;
            }
        }

        return view('auth.verify-otp', [
            'email' => $email,
            'cooldown' => $cooldown,
        ]);
    }

    /**
     * Verify the entered 6-digit OTP.
     */
    public function verifyOtp(Request $request)
    {
        $email = session('reset_email') ?? strtolower(trim($request->input('email', '')));

        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Session expired. Please request a new verification code.']);
        }

        // Combine array of 6 digit inputs if present, or raw otp string
        $otpInput = $request->input('otp');
        if (is_array($otpInput)) {
            $submittedOtp = implode('', $otpInput);
        } else {
            $submittedOtp = trim((string) $otpInput);
        }

        if (strlen($submittedOtp) !== 6 || !ctype_digit($submittedOtp)) {
            return back()->withErrors(['otp' => 'Please enter a valid 6-digit numeric verification code.']);
        }

        $otpRecord = PasswordResetOtp::where('email', $email)
            ->where('used', false)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'No active verification code found. Please request a new code.']);
        }

        if ($otpRecord->isExpired()) {
            return back()->withErrors(['otp' => 'This verification code has expired. Please request a new code.']);
        }

        if ($otpRecord->isMaxAttemptsExceeded(5)) {
            return back()->withErrors(['otp' => 'Maximum verification attempts exceeded. Please request a new code.']);
        }

        if ($otpRecord->otp !== $submittedOtp) {
            $otpRecord->increment('attempts');
            $remaining = 5 - $otpRecord->fresh()->attempts;

            AuditLog::record("Failed OTP verification attempt for email: {$email}", "Auth", $otpRecord->user_id);

            if ($remaining <= 0) {
                return back()->withErrors(['otp' => 'Maximum verification attempts exceeded. Please request a new code.']);
            }

            return back()->withErrors(['otp' => "Invalid verification code. You have {$remaining} attempt(s) remaining."]);
        }

        // OTP matches successfully
        session([
            'otp_verified_email' => $email,
            'verified_otp_id' => $otpRecord->id,
        ]);

        AuditLog::record("OTP code successfully verified for email: {$email}", "Auth", $otpRecord->user_id);

        return redirect()->route('password.reset')->with('status', 'Verification successful! Please set your new password.');
    }

    /**
     * Resend a new OTP to the user's email.
     */
    public function resendOtp(Request $request)
    {
        $email = session('reset_email') ?? strtolower(trim($request->input('email', '')));

        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Session expired. Please request a password reset.']);
        }

        $latestOtp = PasswordResetOtp::where('email', $email)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($latestOtp) {
            $secondsSinceCreation = Carbon::now()->diffInSeconds($latestOtp->created_at);
            if ($secondsSinceCreation < 60) {
                $secondsLeft = 60 - $secondsSinceCreation;
                return back()->withErrors(['resend' => "Please wait {$secondsLeft} seconds before requesting a new verification code."]);
            }
        }

        $user = User::where('email', $email)->first();

        if ($user && $user->status === 'active') {
            PasswordResetOtp::where('email', $email)
                ->where('used', false)
                ->update(['used' => true]);

            $otpCode = sprintf('%06d', random_int(0, 999999));

            PasswordResetOtp::create([
                'user_id' => $user->id,
                'email' => $email,
                'otp' => $otpCode,
                'attempts' => 0,
                'used' => false,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]);

            try {
                Mail::to($email)->send(new PasswordResetOtpMail($otpCode, 10));
                AuditLog::record("Password reset OTP resent to email: {$email}", "Auth", $user->id);
            } catch (\Throwable $e) {
                AuditLog::record("Failed to resend OTP mail to: {$email}. Error: " . $e->getMessage(), "Auth", $user->id);
            }
        }

        session(['reset_email' => $email]);

        return back()->with('status', 'A new 6-digit verification code has been sent to your email.');
    }

    /**
     * Show the reset password form.
     */
    public function showResetForm()
    {
        $email = session('otp_verified_email');

        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Please verify your OTP code first before resetting your password.']);
        }

        return view('auth.reset-password', [
            'email' => $email,
        ]);
    }

    /**
     * Update user password in DB and invalidate sessions/OTPs.
     */
    public function resetPassword(Request $request)
    {
        $email = session('otp_verified_email');

        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Session expired. Please start the password reset process again.']);
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => 'User account not found.']);
        }

        // Update password and invalidate active tokens
        $user->password = Hash::make($request->password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Mark OTPs for this email as used
        PasswordResetOtp::where('email', $email)
            ->where('used', false)
            ->update(['used' => true]);

        AuditLog::record("Password successfully reset", "Auth", $user->id);

        // Clear password reset session state
        session()->forget(['reset_email', 'otp_verified_email', 'verified_otp_id']);

        return redirect()->route('login')->with('status', 'Your password has been reset successfully! Please sign in with your new password.');
    }
}
