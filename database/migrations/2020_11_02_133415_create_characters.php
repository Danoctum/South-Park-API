<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharacters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('full_name')->nullable();
            $table->integer('age')->nullable();
            $table->string('sex')->nullable();
            $table->string('hair_color')->nullable();   
            $table->string('occupation')->nullable();
            $table->string('grade')->nullable();
            $table->string('religion')->nullable();
            $table->string('voiced_by')->nullable();
            $table->foreignId('first_appearance_episode_id')->nullable()->constrained('episodes');
            $table->timestamps();
            //  Aliases and family are done with relational tables.
            //  This time added constrains so that the foreign key relationships are strict in MySQL as well.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('characters');
    }
}
