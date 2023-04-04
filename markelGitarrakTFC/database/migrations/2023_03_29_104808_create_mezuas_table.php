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
        Schema::create('mezuak', function (Blueprint $table) {
            $table->increments('id');
            $table->string('textua');
            $table->timestamp('data')->useCurrent = true;
            $table->integer('irakurrita');
            $table->unsignedInteger('userId');
            $table->unsignedInteger('chatId');
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('chatId')->references('id')->on('chats')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mezuak');
    }
};
