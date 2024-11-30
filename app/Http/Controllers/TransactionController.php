<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      if (!Auth::check()) {
         return response()->json(['error' => 'Unauthorized'], 401);
      }

      $transactions = Transaction::where('user_id', Auth::id())->with('category')->get();

      return response()->json($transactions);
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
         'category_id' => 'required|exists:categories,id',
         'description' => 'nullable|string|max:255',
         'amount' => 'required|numeric|min:0',
         'transaction_date' => 'required|date',
      ]);

      $transaction = Transaction::create([
         'user_id' => Auth::id(),
         'category_id' => $request->category_id,
         'description' => $request->description,
         'amount' => $request->amount,
         'transaction_date' => $request->transaction_date,
      ]);

      return response()->json(['transaction' => $transaction, 'message' => 'Transaction created successfully.'], 201);
   }

   /**
    * Display the specified resource.
    */
   public function show(Transaction $transaction)
   {
      if ($transaction->user_id !== Auth::id()) {
         return response()->json(['error' => 'Unauthorized'], 403);
      }

      return response()->json($transaction->load('category'));
   }

   /**
    * Show the form for editing the specified resource.
    */
   public function edit(Transaction $transaction)
   {
      //
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, Transaction $transaction)
   {
      if ($transaction->user_id !== Auth::id()) {
         return response()->json(['error' => 'Unauthorized'], 403);
      }

      $request->validate([
         'category_id' => 'sometimes|exists:categories,id',
         'description' => 'nullable|string|max:255',
         'amount' => 'required|numeric|min:0',
         'transaction_date' => 'required|date',
      ]);

      $transaction->update($request->only('category_id', 'description', 'amount', 'transaction_date'));

      return response()->json(['transaction' => $transaction, 'message' => 'Transaction updated successfully.']);
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(Transaction $transaction)
   {
      if ($transaction->user_id !== Auth::id()) {
         return response()->json(['error' => 'Unauthorized'], 403);
      }

      $transaction->delete();

      return response()->json(['message' => 'Transaction deleted successfully.']);
   }
}
