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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('complete_name');
            $table->string('email')->unique();
            $table->string('identification_number')->unique();
            $table->string('password');
            $table->integer('age');
            $table->string('citoyennete');
            $table->string('telephone')->unique();
            $table->text('residence');
            $table->enum('language', ['en', 'fr', 'other'])->default('fr');
            $table->string('photo')->nullable();
            $table->string('poste_presente_cdt')->nullable();
            $table->string('nom_parti_politique_cdt')->nullable();
            $table->text('exp_politique_cdt')->nullable();
            $table->text('exp_pro_cdt')->nullable();
            $table->string('niveau_etude_cdt')->nullable();
            $table->string('domaine_etude_cdt')->nullable();
            $table->text('realisations')->nullable();
            $table->json('reseaux_sociaux')->nullable();
            $table->boolean('isConfirm')->default(false);
            $table->boolean('isActivated')->default(true);
            $table->enum('role', ['candidat', 'admin', 'organisateur','electeur'])->default('electeur');
            $table->json('pieces_jointes');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
