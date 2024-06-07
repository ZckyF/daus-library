<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('borrow_books', function (Blueprint $table) {
            $table->id();
            $table->string('borrow_number')->unique();
            $table->foreignId('member_id')->constrained('members')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamp('borrow_date')->nullable();
            $table->timestamp('return_date')->nullable();
            $table->timestamp('returned_date')->nullable();
            $table->integer('quantity');
            $table->enum('status', ['borrowed', 'due', 'returned'])->default('borrowed');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_books');
    }
};
