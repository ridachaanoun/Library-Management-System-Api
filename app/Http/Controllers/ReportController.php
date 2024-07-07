<?php

// app/Http/Controllers/ReportController.php
namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function issuedBooksReport()
    {
        $issuedBooks = Loan::whereNull('returned_date')->with(['book', 'member'])->get();
        return response()->json(['data' => $issuedBooks], 200);
    }

    public function overdueBooksReport()
    {
        $today = now()->toDateString();
        $overdueBooks = Loan::where('due_date', '<', $today)
                            ->whereNull('returned_date')
                            ->with(['book', 'member'])
                            ->get();
        return response()->json(['data' => $overdueBooks], 200);
    }

    public function finesReport()
    {
        $fines = Loan::where('fine_amount', '>', 0)->with(['book', 'member'])->get();
        return response()->json(['data' => $fines], 200);
    }
}
