<?php

use App\Enums\TransactionStatusEnums;
use App\Enums\TransactionTypeEnums;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', TransactionTypeEnums::values());
            $table->string('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('status', TransactionStatusEnums::values())->default(TransactionStatusEnums::PENDING->value);
            $table->string('rejection_reason')->nullable();
            $table->foreignId('approved_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
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
