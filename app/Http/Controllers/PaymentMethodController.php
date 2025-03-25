<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::all();

        return response()->json(['data' => $paymentMethods]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'other_payment_method' => 'nullable|string',
            'account_name' => 'required|string',
            'account_number' => 'required|string',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $qrCodePath = null;
        if ($request->hasFile('qr_code')) {
            $qrCodePath = $request->file('qr_code')->store('qr_codes');
        }

        $payment = PaymentMethod::create([
            'payment_method' => $request->payment_method,
            'other_payment_method' => $request->other_payment_method,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'qr_code' => $qrCodePath,
        ]);

        return response()->json(['message' => 'Payment method added successfully!', 'data' => $payment]);
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'other_payment_method' => 'nullable|string',
            'account_name' => 'required|string',
            'account_number' => 'required|string',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('qr_code')) {
            if ($paymentMethod->qr_code) {
                Storage::delete($paymentMethod->qr_code);
            }
            $qrCodePath = $request->file('qr_code')->store('qr_codes');
            $paymentMethod->qr_code = $qrCodePath;
        }

        $paymentMethod->update($request->only(['payment_method', 'other_payment_method', 'account_name', 'account_number']));

        return response()->json(['message' => 'Payment method updated successfully!', 'data' => $paymentMethod]);
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->qr_code) {
            Storage::delete($paymentMethod->qr_code);
        }
        $paymentMethod->delete();

        return response()->json(['message' => 'Payment method deleted successfully!']);
    }
}
