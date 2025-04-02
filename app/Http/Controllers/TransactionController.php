<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with(['participant.user', 'participant.guest'])->get();

        return response()->json($transactions, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validatedData = $request->validate([
            'seminar_id' => 'required|exists:seminars,id',
            'payment_method' => 'required|string',
            'account_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'reference_number' => 'nullable|string',
            'screenshot' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'payment_status' => 'required|string',
        ]);
    
        $filePath = null; // Initialize as null
    
        // Handle file upload only if a file exists
        if ($request->hasFile('screenshot')) {
            $screenshot = $request->file('screenshot');
            $filename = time() . '_' . $screenshot->getClientOriginalName();
            $filePath = $screenshot->storeAs('screenshots', $filename, 'public'); 
        }
    
        // Create transaction record
        $transaction = Transaction::create([
            'seminar_id' => (int) $validatedData['seminar_id'],
            'payment_method' => $validatedData['payment_method'],
            'account_name' => $validatedData['account_name'] ?? null,
            'account_number' => $validatedData['account_number'] ?? null,
            'reference_number' => $validatedData['reference_number'] ?? null,
            'screenshot' => $filePath, // Will remain null if no file was uploaded
            'payment_status' => $validatedData['payment_status'] ?? 'pending', // Default to pending
            'guest_id' => null,
            'participant_id' => null,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Transaction successfully created!',
            'transaction' => $transaction,
        ], 201, ['Content-Type' => 'application/json']);
    }
    


    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate request input
        $request->validate([
            'payment_status' => 'required|in:completed,rejected',
        ]);
    
        // Find the transaction
        $transaction = Transaction::find($id);
    
        // Check if transaction exists
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }
    
        // Update the payment status
        $transaction->update(['payment_status' => $request->payment_status]);
    
        return response()->json(['payment_status' => $transaction->payment_status, 'message' => 'Payment status updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully'], 200);
    }
}
