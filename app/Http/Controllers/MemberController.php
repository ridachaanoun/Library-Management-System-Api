<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\validator;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::with('user')->get();
        return response()->json(['data' => $members], 200);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:members,email', // Add unique rule for members table
            'password' => 'required',
            'membership_date' => 'required|date',
            'membership_status' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'error' => $validator->errors(),
            ], 401);
        }

        // Create a new member with user's details and request data

            $user = Auth::user();

            $member = Member::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'membership_date' => $request->membership_date,
                'membership_status' => $request->membership_status,
            ]);

            return response()->json([
                'message' => 'Member added successfully',
                'data' => $member,
            ], 201);
    }

  public function show($id)
    {

            $member = Member::with('user')->find($id);

            if (!$member) {
                return response()->json(['message' => 'member not found'], 404);
            }

            return response()->json(['data' => $member], 200);

    }

   public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email|unique:members,email,'.$id, // Ensure unique except for the current member
            'password' => 'required',
            'membership_date' => 'required|date',
            'membership_status' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'error' => $validator->errors(),
            ], 401);
        }

        // Find the member by ID
        $member = Member::findOrFail($id);

        // Update the member with the request data
        try {
            $member->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'membership_date' => $request->membership_date,
                'membership_status' => $request->membership_status,
            ]);

            return response()->json([
                'message' => 'Member updated successfully',
                'data' => $member,
            ], 200);

        } catch (\Exception $e) {
            // Handle unique constraint violation for email
            if (strpos($e->getMessage(), 'Integrity constraint violation') !== false) {
                return response()->json([
                    'message' => 'The email already used',
                    'error' => $e->getMessage(),
                ], 400);
            }

            // Handle other unexpected exceptions
            return response()->json([
                'message' => 'Failed to update member',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();
        return response()->json(['message' => 'Member deleted successfully'], 200);
    }
}
