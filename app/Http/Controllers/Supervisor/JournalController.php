<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\AccountingJournal;

class JournalController extends Controller
{
    public function index()
    {
        $journals = AccountingJournal::with(['transaction.nasabah', 'transaction.user'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('supervisor.journals.index', compact('journals'));
    }
}
