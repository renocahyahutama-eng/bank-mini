<?php

namespace App\Http\Controllers\Api\Nasabah;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends ApiController
{
    /**
     * Get Complete Nasabah Profile.
     */
    public function show(Request $request): JsonResponse
    {
        $nasabah = $this->getNasabah($request);
        $customerAccount = $request->user();

        if (!$nasabah || $nasabah->status !== 'Aktif') {
            return $this->errorResponse('Rekening nasabah tidak aktif.', 403);
        }

        return $this->successResponse([
            'profile' => [
                'id' => $nasabah->id,
                'account_number' => $nasabah->account_number,
                'student_number' => $nasabah->student_number,
                'student_name' => $nasabah->student_name,
                'class' => $nasabah->class,
                'jurusan' => $nasabah->jurusan,
                'gender' => $nasabah->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                'gender_code' => $nasabah->gender,
                'phone_number' => $nasabah->phone_number,
                'status' => $nasabah->status,
                'balance' => (float) $nasabah->balance,
                'balance_formatted' => 'Rp ' . number_format((float) $nasabah->balance, 0, ',', '.'),
                'username' => $customerAccount->username,
                'has_security_pin' => !empty($nasabah->security_pin),
                'registered_at' => $nasabah->created_at ? $nasabah->created_at->format('Y-m-d H:i:s') : null,
                'registered_at_formatted' => $nasabah->created_at ? $nasabah->created_at->translatedFormat('d F Y') : null,
            ],
            'institution' => [
                'school_name' => 'SMK Negeri 1 Bantul',
                'service_unit' => 'Bank Mini SMKN 1 Bantul',
            ],
        ], 'Profil nasabah berhasil dimuat.');
    }

    /**
     * Change Security PIN (6 digits) used for Teller Transactions.
     */
    public function changePin(Request $request): JsonResponse
    {
        $nasabah = $this->getNasabah($request);

        if (!$nasabah || $nasabah->status !== 'Aktif') {
            return $this->errorResponse('Rekening nasabah tidak aktif.', 403);
        }

        $validator = Validator::make($request->all(), [
            'current_pin' => 'required|digits:6',
            'new_pin'     => 'required|digits:6|different:current_pin|confirmed',
        ], [
            'current_pin.required'   => 'PIN saat ini wajib diisi.',
            'current_pin.digits'     => 'PIN saat ini harus berupa 6 digit angka.',
            'new_pin.required'       => 'PIN baru wajib diisi.',
            'new_pin.digits'         => 'PIN baru harus berupa 6 digit angka.',
            'new_pin.different'      => 'PIN baru tidak boleh sama dengan PIN saat ini.',
            'new_pin.confirmed'      => 'Konfirmasi PIN baru tidak cocok.',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal', 422, $validator->errors());
        }

        if (!Hash::check($request->input('current_pin'), $nasabah->security_pin)) {
            return $this->errorResponse('PIN saat ini yang Anda masukkan salah.', 422, [
                'current_pin' => ['PIN saat ini tidak cocok.'],
            ]);
        }

        $nasabah->security_pin = Hash::make($request->input('new_pin'));
        $nasabah->save();

        return $this->successResponse(null, 'PIN keamanan transaksi 6 digit berhasil diperbarui.');
    }

    /**
     * Change Mobile Login Password.
     */
    public function changePassword(Request $request): JsonResponse
    {
        $customerAccount = $request->user();
        $nasabah = $this->getNasabah($request);

        if (!$nasabah || $nasabah->status !== 'Aktif') {
            return $this->errorResponse('Rekening nasabah tidak aktif.', 403);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:6|different:current_password|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required'     => 'Password baru wajib diisi.',
            'new_password.min'          => 'Password baru minimal 6 karakter.',
            'new_password.different'    => 'Password baru harus berbeda dari password saat ini.',
            'new_password.confirmed'    => 'Konfirmasi password baru tidak cocok.',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal', 422, $validator->errors());
        }

        if (!Hash::check($request->input('current_password'), $customerAccount->password)) {
            return $this->errorResponse('Password saat ini salah.', 422, [
                'current_password' => ['Password saat ini tidak cocok.'],
            ]);
        }

        $customerAccount->password = $request->input('new_password');
        $customerAccount->save();

        return $this->successResponse(null, 'Password login akun nasabah berhasil diperbarui.');
    }

    /**
     * Update Student Contact Phone Number.
     */
    public function updatePhone(Request $request): JsonResponse
    {
        $nasabah = $this->getNasabah($request);

        if (!$nasabah || $nasabah->status !== 'Aktif') {
            return $this->errorResponse('Rekening nasabah tidak aktif.', 403);
        }

        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|max:20|regex:/^[0-9+\s\-()]+$/',
        ], [
            'phone_number.required' => 'Nomor HP/WhatsApp wajib diisi.',
            'phone_number.regex'    => 'Format nomor HP tidak valid.',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal', 422, $validator->errors());
        }

        $nasabah->phone_number = trim($request->input('phone_number'));
        $nasabah->save();

        return $this->successResponse([
            'phone_number' => $nasabah->phone_number,
        ], 'Nomor telepon/kontak berhasil diperbarui.');
    }
}
