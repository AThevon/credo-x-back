<?php

namespace App\Models;

use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
   protected $fillable = ['user_id', 'name', 'type', 'is_default'];

   protected $casts = [
      'type' => CategoryType::class,
      'is_default' => 'boolean',
   ];

   public function isDefault(): bool
   {
      return $this->is_default;
   }
}
