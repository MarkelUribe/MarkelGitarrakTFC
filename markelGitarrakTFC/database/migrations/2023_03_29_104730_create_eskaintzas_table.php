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
        Schema::create('eskaintzak', function (Blueprint $table) {
            $table->increments('id');
            $table->string('izena');
            $table->string('azalpena');
            $table->integer('prezioa');
            $table->string('argazkiak');
            $table->string('kokapena');
            $table->string('estatua');
            $table->unsignedInteger('motaId');
            $table->unsignedInteger('userId');
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('motaId')->references('id')->on('eskaintzamotak')->onDelete('cascade');
            $table->index('userId');
            $table->index('motaId');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eskaintzak');
    }
};
