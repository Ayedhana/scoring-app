<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('account_number')->unique();
            $table->string('branch')->nullable();
            $table->string('account_type')->nullable();
            $table->string('currency', 10)->default('TND');
            $table->date('open_date')->nullable();
            $table->decimal('balance', 20, 2)->default(0);
            $table->enum('status', ['active', 'closed', 'dormant'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};