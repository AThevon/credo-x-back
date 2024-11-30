<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   /**
    * Run the migrations.
    */
   public function up(): void
   {
      Schema::create('transactions', function (Blueprint $table) {
         $table->id();
         $table->unsignedBigInteger('user_id');
         $table->unsignedBigInteger('category_id');
         $table->string('title')->nullable();
         $table->decimal('amount', 10, 2);
         $table->timestamp('transaction_date');
         $table->timestamps();

         // Foreign keys
         $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
         $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
      });
   }

   /**
    * Reverse the migrations.
    */
   public function down(): void
   {
      Schema::dropIfExists('transactions');
   }
};
