<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Enums\CategoryType;
use App\Models\Category;

class TransactionController extends Controller
{
   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      $transactions = Transaction::where('user_id', Auth::id())
         ->with('category')
         ->get();

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
         'title' => 'nullable|string|max:255',
         'amount' => 'required|numeric|min:0',
         'transaction_date' => 'required|date',
      ]);

      $transaction = Transaction::create([
         'user_id' => Auth::id(),
         'category_id' => $request->category_id,
         'title' => $request->title,
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
      $request->validate([
         'category_id' => 'sometimes|exists:categories,id',
         'title' => 'nullable|string|max:255',
         'amount' => 'required|numeric|min:0',
         'transaction_date' => 'required|date',
      ]);

      $transaction->update($request->only('category_id', 'title', 'amount', 'transaction_date'));

      return response()->json(['transaction' => $transaction, 'message' => 'Transaction updated successfully.']);
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(Transaction $transaction)
   {
      $transaction->delete();

      return response()->json(['message' => 'Transaction deleted successfully.']);
   }
}
