<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanItem;

class LoanItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(LoanItem::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'book_id' => 'required|exists:books,id',
            'return_date' => 'required|date',
        ]);
        $loanItem = LoanItem::create($validated);
        return response()->json($loanItem, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $loanItem = LoanItem::find($id);

        if (!$loanItem){
            return response()->json(['message'=>'Loan Item not found'], 404);
        }
        return response()->json($loanItem, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $loanItem = LoanItem::find($id);

        if (!$loanItem) {
            return response()->json(['message' => 'Loan Item not found'], 404);
        }

        $validated = $request->validate([
            'loan_id' => 'sometimes|exists:loans,id',
            'book_id' => 'sometimes|exists:books,id',
            'return_date' => 'sometimes|date',
        ]);

        $loanItem->update($validated);

        return response()->json($loanItem, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $loanItem = LoanItem::find($id);

        if (!$loanItem) {
            return response()->json(['message' => 'Loan Item not found'], 404);
        }

        $loanItem->delete();

        return response()->json(['message' => 'Loan Item deleted'], 200);
    }
}
