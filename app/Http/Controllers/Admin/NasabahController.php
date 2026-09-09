<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerAccount;
use App\Models\Nasabah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NasabahController extends Controller
{
    public function index(Request $request)
    {
        $query = Nasabah::with('customerAccount');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('account_number', 'like', "%{$search}%")
                  ->orWhere('student_number', 'like', "%{$search}%")
                  ->orWhere('student_name', 'like', "%{$search}%")
                  ->orWhere('class', 'like', "%{$search}%")
                  ->orWhere('jurusan', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $nasabahs = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('admin.nasabah.index', compact('nasabahs', 'search'));
    }

    public function create()
    {
        $jurusanList = Nasabah::getJurusanList();
        $defaultJurusan = array_key_first($jurusanList) ?? 'RPL';
        $accountNumber = Nasabah::generateAccountNumber($defaultJurusan);

        return view('admin.nasabah.create', compact('jurusanList', 'accountNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_number' => 'required|string|max:20|unique:nasabahs,student_number',
            'student_name' => 'required|string|max:150',
            'jurusan' => 'required|string|max:20',
            'class' => 'required|string|max:20',
            'gender' => 'required|in:L,P',
            'phone_number' => 'nullable|string|max:20',
            'security_pin' => 'required|digits:6',
            'login_username' => 'required|string|max:255|unique:customer_accounts,username',
            'login_password' => 'required|string|min:6|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $accountNumber = Nasabah::generateAccountNumber($request->jurusan);

            $nasabah = Nasabah::create([
                'account_number' => $accountNumber,
                'student_number' => $request->student_number,
                'student_name' => $request->student_name,
                'jurusan' => strtoupper($request->jurusan),
                'class' => $request->class,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'security_pin' => Hash::make($request->security_pin),
                'balance' => 0,
                'status' => 'Aktif',
            ]);

            CustomerAccount::create([
                'nasabah_id' => $nasabah->id,
                'username' => $request->login_username,
                'password' => $request->login_password,
            ]);

            DB::commit();

            return redirect()->route('admin.nasabah.show', $nasabah)
                ->with('success', "Nasabah berhasil ditambahkan dengan Nomor Rekening: {$nasabah->account_number}. QR Code siap digunakan.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan nasabah: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Nasabah $nasabah)
    {
        $nasabah->load('customerAccount');
        $jurusanList = Nasabah::getJurusanList();
        return view('admin.nasabah.edit', compact('nasabah', 'jurusanList'));
    }

    public function update(Request $request, Nasabah $nasabah)
    {
        $request->validate([
            'student_number' => 'required|string|max:20|unique:nasabahs,student_number,' . $nasabah->id,
            'student_name' => 'required|string|max:150',
            'jurusan' => 'nullable|string|max:20',
            'class' => 'required|string|max:20',
            'gender' => 'required|in:L,P',
            'phone_number' => 'nullable|string|max:20',
            'security_pin' => 'nullable|digits:6',
            'status' => 'required|in:Aktif,Nonaktif',
            'login_username' => 'required|string|max:255|unique:customer_accounts,username,' . ($nasabah->customerAccount->id ?? 'NULL'),
            'login_password' => 'nullable|string|min:6|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $nasabahData = [
                'student_number' => $request->student_number,
                'student_name' => $request->student_name,
                'jurusan' => $request->jurusan ? strtoupper($request->jurusan) : $nasabah->jurusan,
                'class' => $request->class,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'status' => $request->status,
            ];

            if ($request->filled('security_pin')) {
                $nasabahData['security_pin'] = Hash::make($request->security_pin);
            }

            $nasabah->update($nasabahData);

            // Update customer account
            $accountData = ['username' => $request->login_username];
            if ($request->filled('login_password')) {
                $accountData['password'] = $request->login_password;
            }

            if ($nasabah->customerAccount) {
                $nasabah->customerAccount->update($accountData);
            } else {
                CustomerAccount::create(array_merge($accountData, [
                    'nasabah_id' => $nasabah->id,
                    'password' => $request->login_password ?? 'password',
                ]));
            }

            DB::commit();

            return redirect()->route('admin.nasabah.index')->with('success', 'Data nasabah berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui nasabah: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Nasabah $nasabah)
    {
        $nasabah->load(['customerAccount', 'transactions' => function ($q) {
            $q->orderByDesc('created_at');
        }]);
        return view('admin.nasabah.show', compact('nasabah'));
    }

    /**
     * Mengubah status nasabah antara Aktif dan Nonaktif (menggantikan hapus).
     */
    public function toggleStatus(Nasabah $nasabah)
    {
        $newStatus = $nasabah->status === 'Aktif' ? 'Nonaktif' : 'Aktif';
        $nasabah->update(['status' => $newStatus]);

        return back()->with('success', "Status nasabah '{$nasabah->student_name}' ({$nasabah->account_number}) berhasil diubah menjadi {$newStatus}.");
    }
}
