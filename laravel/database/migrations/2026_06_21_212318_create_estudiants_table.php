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
        Schema::create('estudiants', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 60);
            $table->string('email')->unique();
            $table->string('telefon', 20);
            $table->string('adreca')->nullable();
            $table->string('numero_document_identitat', 20)->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiants');
    }
};
