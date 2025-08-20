<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Loan::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'loan_date' => 'required|date',
            'return_date' => 'required|date',
        ]);
        $loan = Loan::create($validated);
        return response()->json($loan, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $loan = Loan::find($id);

        if (!$loan){
            return response()->json(['message'=>'Loan not found'], 404);
        }
        return response()->json($loan, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json(['message' => 'Loan not found'], 404);
        }

        $validated = $request->validate([
            'member_id' => 'sometimes|exists:members,id',
            'loan_date' => 'sometimes|date',
            'return_date' => 'sometimes|date',
        ]);

        $loan->update($validated);

        return response()->json($loan, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $loan = Loan::find($id);

        if (!$loan) {
            return response()->json(['message' => 'Loan not found'], 404);
        }

        $loan->delete();

        return response()->json(['message' => 'Loan deleted'], 200);
    }
}
