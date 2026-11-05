<?php

namespace App\Http\Controllers\Api\Nasabah;

use App\Http\Controllers\Api\ApiController;
use App\Models\CustomerAccount;
use App\Models\Nasabah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    /**
     * Handle Nasabah Mobile Login.
     * Supports login via username, account_number, or student_number (NIS).
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string',
            'password'   => 'required|string',
            'device_name' => 'nullable|string|max:100',
        ], [
            'identifier.required' => 'Nomor rekening, username, atau NIS wajib diisi.',
            'password.required'   => 'Password wajib diisi.',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal', 422, $validator->errors());
        }

        $identifier = trim($request->input('identifier'));
        $password   = $request->input('password');

        // 1. Try finding by CustomerAccount username
        $customerAccount = CustomerAccount::with('nasabah')->where('username', $identifier)->first();

        // 2. If not found by username, try by account_number, student_number (NIS), or email
        if (!$customerAccount) {
            $nasabah = Nasabah::with('customerAccount')
                ->where('account_number', $identifier)
                ->orWhere('student_number', $identifier)
                ->orWhere('email', $identifier)
                ->first();

            if ($nasabah && $nasabah->customerAccount) {
                $customerAccount = $nasabah->customerAccount;
                $customerAccount->setRelation('nasabah', $nasabah);
            }
        }

        if (!$customerAccount || !Hash::check($password, $customerAccount->password)) {
            return $this->errorResponse('Kombinasi kredensial login atau password salah.', 401);
        }

        $nasabah = $customerAccount->nasabah;

        if (!$nasabah) {
            return $this->errorResponse('Data nasabah tidak ditemukan pada akun ini.', 404);
        }

        if ($nasabah->status !== 'Aktif') {
            return $this->errorResponse('Rekening nasabah saat ini tidak aktif atau dibekukan. Silakan hubungi petugas Bank Mini.', 403);
        }

        // Generate Sanctum Bearer Token
        $deviceName = $request->input('device_name', 'mobile-device');
        $token = $customerAccount->createToken($deviceName)->plainTextToken;

        return $this->successResponse([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'nasabah' => [
                'id' => $nasabah->id,
                'account_number' => $nasabah->account_number,
                'student_number' => $nasabah->student_number,
                'student_name' => $nasabah->student_name,
                'class' => $nasabah->class,
                'jurusan' => $nasabah->jurusan,
                'gender' => $nasabah->gender,
                'phone_number' => $nasabah->phone_number,
                'email' => $nasabah->email,
                'status' => $nasabah->status,
                'balance' => (float) $nasabah->balance,
                'balance_formatted' => 'Rp ' . number_format((float) $nasabah->balance, 0, ',', '.'),
            ],
            'account' => [
                'id' => $customerAccount->id,
                'username' => $customerAccount->username,
            ],
        ], 'Login berhasil. Selamat datang di Bank Mini SMKN 1 Bantul.');
    }

    /**
     * Get Current Authenticated Nasabah Info.
     */
    public function me(Request $request): JsonResponse
    {
        $customerAccount = $request->user();
        $nasabah = $customerAccount->nasabah;

        if (!$nasabah || $nasabah->status !== 'Aktif') {
            return $this->errorResponse('Rekening nasabah tidak aktif.', 403);
        }

        return $this->successResponse([
            'nasabah' => [
                'id' => $nasabah->id,
                'account_number' => $nasabah->account_number,
                'student_number' => $nasabah->student_number,
                'student_name' => $nasabah->student_name,
                'class' => $nasabah->class,
                'jurusan' => $nasabah->jurusan,
                'gender' => $nasabah->gender,
                'phone_number' => $nasabah->phone_number,
                'email' => $nasabah->email,
                'status' => $nasabah->status,
                'balance' => (float) $nasabah->balance,
                'balance_formatted' => 'Rp ' . number_format((float) $nasabah->balance, 0, ',', '.'),
                'registered_at' => $nasabah->created_at ? $nasabah->created_at->format('Y-m-d H:i:s') : null,
            ],
            'account' => [
                'id' => $customerAccount->id,
                'username' => $customerAccount->username,
            ],
        ], 'Data profil nasabah berhasil dimuat.');
    }

    /**
     * Logout and Revoke Current Access Token.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse(null, 'Berhasil logout dan token dinonaktifkan.');
    }
}
