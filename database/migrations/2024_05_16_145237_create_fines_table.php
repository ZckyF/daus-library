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
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->string('fine_code')->unique();
            $table->foreignId('member_id')->nullable()->constrained('members')->onDelete('cascade')->onUpdate('cascade');
            $table->string('non_member_name')->nullable(); 
            $table->decimal('amount', 8, 2)->default(0);
            $table->decimal('amount_paid', 8, 2)->default(0);
            $table->decimal('change_amount', 8, 2)->default(0);
            $table->text('reason');
            $table->timestamp('charged_at')->nullable();
            $table->boolean('is_paid')->default(false);
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
        Schema::dropIfExists('fines');
    }
};
