<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Enums\CategoryType;

class CategorySeeder extends Seeder
{
   public function run()
   {
      $defaultCategories = [
         ['name' => 'Salaire', 'type' => CategoryType::Income->value, 'is_default' => true],
         ['name' => 'Loyer', 'type' => CategoryType::Expense->value, 'is_default' => true],
         ['name' => 'Alimentation', 'type' => CategoryType::Expense->value, 'is_default' => true],
      ];

      foreach ($defaultCategories as $category) {
         Category::create($category);
      }
   }
}
