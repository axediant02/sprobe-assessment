<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;
use Illuminate\Support\Carbon;

class BookController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		return response()->json(Book::with('authors')->get(), 200);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'description' => 'nullable|string',
			'published_at' => 'nullable|date',
		]);

		$validated['published_at'] = $validated['published_at'] ?? Carbon::now()->toDateString();

		$book = Book::create($validated);

		$user = $request->user();
		if ($user) {
			$author = Author::firstOrCreate(['name' => $user->name], ['bio' => null]);
			$book->authors()->syncWithoutDetaching([$author->id]);
		}

		return response()->json($book->load('authors'), 201);
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		$book = Book::with('authors')->find($id);

		if (!$book){
			return response()->json(['message'=>'Book not found'], 404);
		}
		return response()->json($book, 200);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		$book = Book::find($id);

		if (!$book) {
			return response()->json(['message' => 'Book not found'], 404);
		}

		$validated = $request->validate([
			'title' => 'sometimes|string|max:255',
			'description' => 'sometimes|nullable|string',
			'published_at' => 'sometimes|date',
		]);

		$book->update($validated);

		return response()->json($book->load('authors'), 200);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		$book = Book::find($id);

		if (!$book) {
			return response()->json(['message' => 'Book not found'], 404);
		}

		$book->delete();

		return response()->json(['message' => 'Book deleted'], 200);
	}
}
