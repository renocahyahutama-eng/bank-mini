<?php

namespace App\Http\Controllers\Api\Nasabah;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;

class InfoController extends ApiController
{
    /**
     * Get School Bank Information, Guidelines, and FAQ for Students.
     */
    public function index(): JsonResponse
    {
        return $this->successResponse([
            'bank_info' => [
                'institution'       => 'SMK Negeri 1 Bantul',
                'name'              => 'Bank Mini SMKN 1 Bantul',
                'description'       => 'Layanan perbankan mini sekolah untuk melatih literasi keuangan, menabung, dan transparansi transaksi siswa berbasis digital.',
                'logo_url'          => url('images/logo.png'),
                'operational_hours' => 'Senin – Jumat: 08.00 – 14.00 WIB (Istirahat 12.00 – 13.00 WIB)',
                'location'          => 'Gedung Laboratorium Perbankan & Bisnis / Bank Mini Lt. 1, SMKN 1 Bantul',
                'address'           => 'Jl. Sabdodadi, Bantul, Daerah Istimewa Yogyakarta',
            ],
            'guides' => [
                [
                    'title'       => 'Cara Menabung (Setoran Tunai)',
                    'description' => 'Kunjungi loket Teller Bank Mini, tunjukkan QR Code dari aplikasi mobile ini, serahkan uang setoran kepada Teller, dan cek saldo Anda yang langsung bertambah secara real-time.',
                ],
                [
                    'title'       => 'Cara Mengambil Uang (Penarikan Tunai)',
                    'description' => 'Kunjungi loket Teller, tunjukkan QR Code rekening Anda, sebutkan nominal penarikan, masukkan 6 digit PIN Keamanan Anda, dan terima uang tunai beserta bukti struk digital.',
                ],
                [
                    'title'       => 'Keamanan Akun & PIN',
                    'description' => 'PIN 6 digit Anda bersifat rahasia dan hanya digunakan untuk memverifikasi penarikan saldo Anda di teller. Jangan pernah memberikan PIN atau password kepada orang lain.',
                ],
            ],
            'faq' => [
                [
                    'question' => 'Apakah ada biaya administrasi bulanan?',
                    'answer'   => 'Tidak ada. Bank Mini SMKN 1 Bantul bebas biaya administrasi bulanan untuk seluruh siswa nasabah.',
                ],
                [
                    'question' => 'Berapa minimal setoran tunai?',
                    'answer'   => 'Minimal setoran tabungan adalah Rp 5.000.',
                ],
                [
                    'question' => 'Bagaimana jika lupa PIN keamanan transaksi?',
                    'answer'   => 'Silakan datangi petugas Administrator Bank Mini di sekolah dengan membawa kartu identitas siswa untuk reset PIN.',
                ],
            ],
        ], 'Informasi Bank Mini SMKN 1 Bantul berhasil dimuat.');
    }
}
