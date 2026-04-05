<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->decimal('score_total', 5, 2)->default(0);
            $table->enum('niveau_risque', ['faible', 'moyen', 'eleve'])->default('moyen');
            $table->json('detail_scores')->nullable();
            $table->foreignId('calculated_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};