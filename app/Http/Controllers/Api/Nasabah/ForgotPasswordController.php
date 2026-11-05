<?php

namespace App\Http\Controllers\Api\Nasabah;

use App\Http\Controllers\Api\ApiController;
use App\Mail\NasabahOtpMail;
use App\Models\Nasabah;
use App\Models\NasabahPasswordReset;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends ApiController
{
    /**
     * Request 6-digit OTP code to registered email.
     */
    public function requestOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string',
        ], [
            'identifier.required' => 'Masukkan email, username, atau NIS nasabah.',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal', 422, $validator->errors());
        }

        $identifier = trim($request->input('identifier'));

        // Find Nasabah by email, username, student_number (NIS), or account_number
        $nasabah = Nasabah::where('email', $identifier)
            ->orWhere('student_number', $identifier)
            ->orWhere('account_number', $identifier)
            ->orWhereHas('customerAccount', function ($q) use ($identifier) {
                $q->where('username', $identifier);
            })
            ->first();

        if (!$nasabah) {
            return $this->errorResponse('Akun nasabah tidak ditemukan. Pastikan data yang dimasukkan benar.', 404);
        }

        if (empty($nasabah->email)) {
            return $this->errorResponse(
                'Rekening nasabah ini belum memiliki alamat email yang terdaftar di sistem. Silakan hubungi loket Teller atau Administrator untuk mendaftarkan email Anda.',
                422
            );
        }

        if ($nasabah->status !== 'Aktif') {
            return $this->errorResponse('Rekening nasabah sedang non-aktif. Silakan hubungi Administrator.', 403);
        }

        // Generate 6-digit OTP
        $otp = sprintf('%06d', mt_rand(100000, 999999));

        // Invalidate old OTPs for this email
        NasabahPasswordReset::where('email', $nasabah->email)->delete();

        // Save new OTP valid for 10 minutes
        NasabahPasswordReset::create([
            'email' => $nasabah->email,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Attempt sending email
        $mailSent = false;
        try {
            Mail::to($nasabah->email)->send(new NasabahOtpMail($otp, $nasabah));
            $mailSent = true;
            Log::info("Email OTP reset password berhasil dikirim ke {$nasabah->email} (OTP: {$otp})");
        } catch (\Throwable $e) {
            Log::error("Gagal mengirim email OTP ke {$nasabah->email}: " . $e->getMessage());
            if (config('mail.default') === 'smtp') {
                return $this->errorResponse('Gagal mengirim email OTP ke ' . $nasabah->email . '. Periksa konfigurasi SMTP: ' . $e->getMessage(), 500);
            }
        }

        // Mask email for privacy (e.g. ren***@gmail.com)
        $parts = explode('@', $nasabah->email);
        $namePart = $parts[0];
        $domainPart = $parts[1] ?? '';
        $maskedName = substr($namePart, 0, min(3, strlen($namePart))) . '***';
        $maskedEmail = $maskedName . '@' . $domainPart;

        return $this->successResponse([
            'email' => $nasabah->email,
            'masked_email' => $maskedEmail,
            'expires_in_seconds' => 600,
            'mail_sent' => $mailSent,
        ], "Kode OTP 6-digit telah dikirimkan ke email {$maskedEmail}. Silakan periksa kotak masuk atau spam.");
    }

    /**
     * Verify OTP code.
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'otp.required'   => 'Kode OTP wajib diisi.',
            'otp.digits'     => 'Kode OTP harus berupa 6 digit angka.',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal', 422, $validator->errors());
        }

        $reset = NasabahPasswordReset::where('email', trim($request->input('email')))
            ->where('otp', trim($request->input('otp')))
            ->first();

        if (!$reset || $reset->isExpired()) {
            return $this->errorResponse('Kode OTP salah atau telah kedaluwarsa. Silakan minta kode baru.', 422);
        }

        return $this->successResponse(null, 'Kode OTP valid. Silakan buat password baru Anda.');
    }

    /**
     * Reset password using verified OTP.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email'                 => 'required|email',
            'otp'                   => 'required|digits:6',
            'password'              => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string',
        ], [
            'email.required'                 => 'Email wajib diisi.',
            'otp.required'                   => 'Kode OTP wajib diisi.',
            'password.required'              => 'Password baru wajib diisi.',
            'password.min'                   => 'Password baru minimal 6 karakter.',
            'password.confirmed'             => 'Konfirmasi password baru tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi password baru wajib diisi.',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal', 422, $validator->errors());
        }

        $email = trim($request->input('email'));
        $otp = trim($request->input('otp'));

        $reset = NasabahPasswordReset::where('email', $email)
            ->where('otp', $otp)
            ->first();

        if (!$reset || $reset->isExpired()) {
            return $this->errorResponse('Kode OTP tidak valid atau telah kedaluwarsa.', 422);
        }

        $nasabah = Nasabah::where('email', $email)->first();
        if (!$nasabah || !$nasabah->customerAccount) {
            return $this->errorResponse('Akun nasabah tidak ditemukan.', 404);
        }

        // Update password
        $customerAccount = $nasabah->customerAccount;
        $customerAccount->password = Hash::make($request->input('password'));
        $customerAccount->save();

        // Delete used OTP
        $reset->delete();

        Log::info("Password untuk nasabah {$nasabah->student_name} ({$nasabah->email}) berhasil direset via OTP.");

        return $this->successResponse(null, 'Password akun berhasil diubah! Silakan masuk dengan password baru Anda.');
    }
}

