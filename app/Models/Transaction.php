<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
   protected $fillable = [
      'user_id',
      'category_id',
      'title',
      'amount',
      'transaction_date',
   ];

   protected $casts = [
      'amount' => 'float', // Assure un cast automatique
   ];

   /**
    * Relation avec l'utilisateur.
    */
   public function user()
   {
      return $this->belongsTo(User::class);
   }

   /**
    * Relation avec la catégorie.
    */
   public function category()
   {
      return $this->belongsTo(Category::class);
   }
}
