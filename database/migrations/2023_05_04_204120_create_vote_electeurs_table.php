<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vote_electeurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vote_id')->nullable()->constrained('votes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('electeur_id')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('candidat_id')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->boolean('isVoted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vote_electeurs');
    }
};
