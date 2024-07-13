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
        Schema::create('movies', function (Blueprint $table) {
            $table->uuid('id')->default('')->primary();
            $table->string('imdb_id')->default('');
            $table->string('title');
            $table->text('overview');
            $table->string('backdrop_path');
            $table->string('poster_path');
            $table->string('release_date', 10);
            $table->float('vote_average', 4, 2);
            $table->unsignedSmallInteger('runtime');
            $table->string('youtube_id')->nullable();
            $table->enum('type', ['movie', 'tv_show']);
            $table->unsignedInteger('views')->default('0');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movies');
    }
};
