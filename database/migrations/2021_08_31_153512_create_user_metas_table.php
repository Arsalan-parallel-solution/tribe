<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_metas', function (Blueprint $table) {
            $table->id();
            $table->integer('age');
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('gender')->nullable();
            $table->string('sexual_orientation')->nullable();
            $table->string('pronouns')->nullable();
            $table->string('ethnicity')->nullable();
            $table->string('hiv_status')->nullable();
            $table->text('social_media_links')->nullable();
            $table->text('desciption')->nullable();
            $table->string('looking_for')->nullable();
            $table->string('position')->nullable();
            $table->string('hangout')->nullable();
            $table->text('tribe')->nullable();
            $table->string('profile_image')->nullable();
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
        Schema::dropIfExists('user_metas');
    }
}
