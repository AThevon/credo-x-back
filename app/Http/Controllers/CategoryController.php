<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rules\Enum;
use App\Enums\CategoryType;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
   /**
    * Display a listing of the resource.
    */

    public function getByType(string $type)
    {
        // Vérifie si le type est valide en utilisant l'enum
        if (!CategoryType::tryFrom($type)) {
            return response()->json([
                'message' => 'Type invalide. Les valeurs acceptées sont income ou expense.',
            ], 400);
        }
    
        // Filtrer les catégories par type
        $categories = Category::where('type', $type)->get();
    
        // Retourner les catégories en JSON
        return response()->json($categories);
    }
    

   public function index()
   {
      $defaultCategories = Category::where('is_default', true)->get(); // Catégories par défaut
      $userCategories = Auth::check()
         ? Category::where('user_id', Auth::id())->get()
         : collect(); // Collection vide si aucun utilisateur connecté

      return response()->json([
         'default' => $defaultCategories,
         'user' => $userCategories,
      ]);
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
         'name' => 'required|string|max:255',
         'type' => ['required', new Enum(CategoryType::class)],
      ]);

      $category = Category::create([
         'user_id' => Auth::id(),
         'name' => $request->name,
         'type' => $request->type,
         'is_default' => false,
      ]);

      return response()->json(['category' => $category, 'message' => 'Category created successfully.'], 201);
   }

   /**
    * Display the specified resource.
    */
   public function show(Category $category)
   {
      if ($category->user_id && $category->user_id !== Auth::id()) {
         return response()->json(['error' => 'Unauthorized'], 403);
      }

      return response()->json($category);
   }

   /**
    * Show the form for editing the specified resource.
    */
   public function edit(Category $category)
   {
      //
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, Category $category)
   {
      if ($category->isDefault()) {
         return response()->json(['error' => 'Default categories cannot be updated.'], 403);
      }

      if ($category->user_id !== Auth::id()) {
         return response()->json(['error' => 'Unauthorized'], 403);
      }

      $request->validate([
         'name' => 'sometimes|required|string|max:255',
         'type' => 'sometimes|in:default,income,expense',
      ]);

      $category->update($request->only('name', 'type'));

      return response()->json(['category' => $category, 'message' => 'Category updated successfully.']);
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(Category $category)
   {
      if ($category->isDefault()) {
         return response()->json(['error' => 'Default categories cannot be deleted.'], 403);
      }

      if ($category->user_id !== Auth::id()) {
         return response()->json(['error' => 'Unauthorized'], 403);
      }

      $category->delete();

      return response()->json(['message' => 'Category deleted successfully.']);
   }
}
