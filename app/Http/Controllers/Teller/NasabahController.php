<?php

namespace App\Http\Controllers\Teller;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use Illuminate\Http\Request;

class NasabahController extends Controller
{
    public function index(Request $request)
    {
        $query = Nasabah::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('account_number', 'like', "%{$search}%")
                  ->orWhere('student_name', 'like', "%{$search}%")
                  ->orWhere('student_number', 'like', "%{$search}%");
            });
        }

        $nasabahs = $query->where('status', 'Aktif')->orderBy('student_name')->get();

        return view('teller.nasabah.index', compact('nasabahs', 'search'));
    }

    /**
     * QR Code lookup — returns JSON for AJAX scanner requests.
     */
    public function qrLookup(Request $request)
    {
        $request->validate(['account_number' => 'required|string']);

        $nasabah = Nasabah::where('account_number', $request->account_number)
            ->where('status', 'Aktif')
            ->first();

        if (!$nasabah) {
            return response()->json(['success' => false, 'message' => 'Nasabah tidak ditemukan.'], 404);
        }

        return response()->json([
            'success' => true,
            'nasabah' => [
                'id' => $nasabah->id,
                'account_number' => $nasabah->account_number,
                'student_name' => $nasabah->student_name,
                'class' => $nasabah->class,
                'balance' => number_format($nasabah->balance, 0, ',', '.'),
            ],
        ]);
    }
}
