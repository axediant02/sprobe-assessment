<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanItem;
use Illuminate\Support\Facades\Log;

class LoanItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response()->json(LoanItem::with(['loan.user', 'book'])->get(), 200);
        } catch (\Exception $e) {
            Log::error('Error fetching loan items: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch loan items'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'loan_id' => 'required|exists:loans,id',
                'book_id' => 'required|exists:books,id',
                'due_date' => 'required|date|after:today',
                'return_date' => 'nullable|date|after:today',
                'status' => 'sometimes|in:borrowed,returned',
            ]);

            Log::info('Creating loan item with validated data:', $validated);

            $loanItem = LoanItem::create($validated);

            Log::info('Loan item created successfully:', $loanItem->toArray());

            // Refresh the model to get the default values and then load relationships
            $loanItem->refresh();
            return response()->json($loanItem->load(['loan.user', 'book']), 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', $e->errors());
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error creating loan item: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create loan item: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $loanItem = LoanItem::with(['loan.user', 'book'])->find($id);

            if (!$loanItem) {
                return response()->json(['message' => 'Loan Item not found'], 404);
            }

            return response()->json($loanItem, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching loan item: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch loan item'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $loanItem = LoanItem::find($id);

            if (!$loanItem) {
                return response()->json(['message' => 'Loan Item not found'], 404);
            }

            $validated = $request->validate([
                'loan_id' => 'sometimes|exists:loans,id',
                'book_id' => 'sometimes|exists:books,id',
                'due_date' => 'sometimes|date',
                'return_date' => 'sometimes|date',
                'status' => 'sometimes|in:borrowed,returned',
            ]);

            $loanItem->update($validated);

            return response()->json($loanItem->load(['loan.user', 'book']), 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', $e->errors());
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error updating loan item: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update loan item'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $loanItem = LoanItem::find($id);

            if (!$loanItem) {
                return response()->json(['message' => 'Loan Item not found'], 404);
            }

            $loanItem->delete();

            return response()->json(['message' => 'Loan Item deleted'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting loan item: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete loan item'], 500);
        }
    }

    /**
     * Return a book (mark as returned)
     */
    public function return(string $id)
    {
        try {
            $loanItem = LoanItem::find($id);

            if (!$loanItem) {
                return response()->json(['message' => 'Loan Item not found'], 404);
            }

            $loanItem->update([
                'status' => 'returned',
                'return_date' => now(),
            ]);

            Log::info('Book returned successfully:', $loanItem->toArray());

            return response()->json([
                'message' => 'Book returned successfully',
                'loanItem' => $loanItem->load(['loan.user', 'book'])
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error returning book: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to return book'], 500);
        }
    }
}
