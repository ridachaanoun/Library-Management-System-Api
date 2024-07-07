<?php

// app/Http/Controllers/LoanController.php
namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['book', 'member'])->get();
        return response()->json(['data' => $loans], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'issued_date' => 'required|date',
            'due_date' => 'required|date|after:issued_date'
        ]);

        $loan = Loan::create([
            'book_id' => $request->book_id,
            'member_id' => $request->member_id,
            'issued_date' => $request->issued_date,
            'due_date' => $request->due_date
        ]);

        return response()->json(['message' => 'Book issued successfully', 'data' => $loan], 201);
    }

    public function returnBook(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update([
            'returned_date' => $request->returned_date,
            'fine_amount' => $request->fine_amount
        ]);

        return response()->json(['message' => 'Book returned successfully', 'data' => $loan], 200);
    }
}

