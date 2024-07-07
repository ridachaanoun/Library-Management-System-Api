<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    // Add a new book
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'published_year' => 'required|integer',
            'ISBN' => 'required|string|unique:books,ISBN',
            'copies_available' => 'required|integer',
        ]);

        $book = Book::create($request->all());

        return response()->json([
            'message' => 'Book added successfully',
            'data' => $book
        ], 201);
    }

    // Get all books
    public function index(Request $request)
    {
        $query = Book::query();
        // Search by title, author, or genre
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('title', 'LIKE', "%{$search}%")
            ->orWhere('author', 'LIKE', "%{$search}%")
            ->orWhere('genre', 'LIKE', "%{$search}%");
        }
             
        // Pagination with 10 items per page
        $books = $query->Paginate(10);

        return response()->json($books, 200);
    }
    // Get a single book
    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return response()->json($book, 200);
    }

    // Update a book
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'string|max:255',
            'author' => 'string|max:255',
            'genre' => 'string|max:255',
            'published_year' => 'integer',
            'ISBN' => 'string|unique:books,ISBN,' . $id,
            'copies_available' => 'integer',
        ]);

        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $book->update($request->all());

        return response()->json([
            'message' => 'Book updated successfully',
            'data' => $book
        ], 200);
    }

    // Delete a book
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        $book->delete();

        return response()->json(['message' => 'Book deleted successfully'], 200);
    }
}
