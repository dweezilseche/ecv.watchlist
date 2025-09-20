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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->integer('id_movie_tmdb')->nullable();
            $table->string('name')->nullable();
            $table->string('image')->nullable();
            $table->boolean('seen')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('actors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('actor_movie', function (Blueprint $table) {
            $table->unsignedBigInteger('actor_id');
            $table->unsignedBigInteger('movie_id');
            $table->foreign('actor_id')->references('id')->on('actors')->onDelete('cascade');;
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');;
        });


        //Ici on fait le lien entre les genres et les genres des films

        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->integer('id_genre_tmdb')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });

        Schema::create('genre_movie', function (Blueprint $table) {
            $table->unsignedBigInteger('genre_id');
            $table->unsignedBigInteger('movie_id');
            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade');;
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
        Schema::dropIfExists('grenres');
        Schema::dropIfExists('genre_movie');
    }
};
