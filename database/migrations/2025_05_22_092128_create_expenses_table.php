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
       Schema::create('expenses', function (Blueprint $table) {
    $table->id();
    $table->foreignId('expense_type_id')->constrained()->onDelete('cascade');
    $table->decimal('amount', 15, 2);
    $table->date('date');
    $table->foreignId('from_bank_id')->nullable()->constrained('banks')->onDelete('set null');
    $table->foreignId('from_account_id')->nullable()->constrained('accounts')->onDelete('set null');
    $table->foreignId('to_bank_id')->nullable()->constrained('banks')->onDelete('set null');
    $table->string('to_account')->nullable();
    $table->string('payment_type');
    $table->string('file_path')->nullable();
    $table->text('remark')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
