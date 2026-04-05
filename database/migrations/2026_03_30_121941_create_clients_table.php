<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('numero_client')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('cin')->unique()->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('adresse')->nullable();
            $table->enum('type_client', ['particulier', 'entreprise'])->default('particulier');
            $table->decimal('solde_moyen', 15, 2)->default(0);
            $table->integer('nombre_incidents')->default(0);
            $table->decimal('montant_credits', 15, 2)->default(0);
            $table->integer('anciennete_mois')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};