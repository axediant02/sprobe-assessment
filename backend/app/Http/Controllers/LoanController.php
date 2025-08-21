<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use Illuminate\Support\Facades\Log;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Loan::with(['user','book'])->where('user_id', request()->user()->id)->get(),
            200
        );
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'book_id' => 'required|exists:books,id',
                'loan_date' => 'required|date',
                'return_date' => 'nullable|date|after_or_equal:loan_date',
            ]);

            $validated['user_id'] = $request->user()->id;

            $loan = Loan::create($validated);

            return response()->json($loan->load(['user', 'book']), 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create loan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $loan = Loan::with(['user', 'book'])->find($id);

            if (!$loan) {
                return response()->json(['message' => 'Loan not found'], 404);
            }

            return response()->json($loan, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching loan: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch loan'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $loan = Loan::find($id);

            if (!$loan) {
                return response()->json(['message' => 'Loan not found'], 404);
            }

            $validated = $request->validate([
                'user_id' => 'sometimes|exists:users,id',
                'book_id' => 'sometimes|exists:books,id',
                'loan_date' => 'sometimes|date',
                'return_date' => 'sometimes|date|after_or_equal:loan_date',
                'status' => 'sometimes|in:ongoing,completed',
            ]);

            $loan->update($validated);

            return response()->json($loan->load(['user', 'book']), 200);
        } catch (\Exception $e) {
            Log::error('Error updating loan: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update loan'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $loan = Loan::find($id);

            if (!$loan) {
                return response()->json(['message' => 'Loan not found'], 404);
            }

            $loan->delete();

            return response()->json(['message' => 'Loan deleted'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting loan: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete loan'], 500);
        }
    }
}
