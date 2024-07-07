<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['book', 'member'])->get();
        return response()->json(['data' => $reservations], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'reserved_date' => 'required|date'
        ]);

        $reservation = Reservation::create([
            'book_id' => $request->book_id,
            'member_id' => $request->member_id,
            'reserved_date' => $request->reserved_date
        ]);

        return response()->json(['message' => 'Book reserved successfully', 'data' => $reservation], 201);
    }

    public function notifyMember($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update([
            'notification_sent' => true
        ]);

        return response()->json(['message' => 'Member notified successfully', 'data' => $reservation], 200);
    }
}

