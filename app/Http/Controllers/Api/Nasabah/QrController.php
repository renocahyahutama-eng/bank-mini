<?php

namespace App\Http\Controllers\Api\Nasabah;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QrController extends ApiController
{
    /**
     * Get Digital QR Code payload & Student Card details for Teller Scanning.
     */
    public function index(Request $request): JsonResponse
    {
        $nasabah = $this->getNasabah($request);

        if (!$nasabah || $nasabah->status !== 'Aktif') {
            return $this->errorResponse('Rekening nasabah tidak aktif.', 403);
        }

        return $this->successResponse([
            'qr_payload' => $nasabah->account_number,
            'card' => [
                'institution' => 'SMK Negeri 1 Bantul',
                'service' => 'Bank Mini Sekolah',
                'account_number' => $nasabah->account_number,
                'student_name' => $nasabah->student_name,
                'student_number' => $nasabah->student_number,
                'class' => $nasabah->class,
                'jurusan' => $nasabah->jurusan,
                'status' => $nasabah->status,
                'balance' => (float) $nasabah->balance,
                'balance_formatted' => 'Rp ' . number_format((float) $nasabah->balance, 0, ',', '.'),
            ],
            'instructions' => [
                'id' => 'Tunjukkan QR Code ini kepada Petugas Teller Bank Mini SMKN 1 Bantul untuk melakukan transaksi Setoran Tunai atau Penarikan Tunai tanpa perlu membawa buku tabungan fisik.',
            ],
        ], 'Data QR Code kartu nasabah berhasil dimuat.');
    }
}
