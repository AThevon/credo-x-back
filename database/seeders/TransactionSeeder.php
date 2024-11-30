<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
   /**
    * Run the database seeds.
    */
   public function run(): void
   {
      $user = User::first(); // Associe la transaction au premier utilisateur
      $categories = Category::where('is_default', true)->get(); // Catégories par défaut

      foreach ($categories as $category) {
         Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Test transaction for ' . $category->name,
            'amount' => rand(10, 1000) / 10, // Montant aléatoire entre 1.00 et 100.00
            'transaction_date' => Carbon::now()->subDays(rand(1, 30)), // Date aléatoire dans le dernier mois
         ]);
      }
   }
}
