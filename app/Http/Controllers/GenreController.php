<?php
// app/Http/Controllers/GenreController.php
namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::with('user')->paginate(10) ;
        return response()->json(['data' => $genres], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        $genre = Genre::create([
            'name' => $request->name,
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Genre created successfully',
            'data' => $genre,
        ], 201);
    }

    public function show($id)
    {
        $genre = Genre::findOrFail($id)->with('user');
        return response()->json(['data' => $genre], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $genre = Genre::findOrFail($id);

        $genre->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Genre updated successfully', 'data' => $genre], 200);
    }

    public function destroy($id)
    {
        $genre = Genre::findOrFail($id);
        $genre->delete();
        return response()->json(['message' => 'Genre deleted successfully'], 200);
    }
}

