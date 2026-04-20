<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSongsTable extends Migration
{
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id('song_id');
            $table->string('title');
            $table->string('audio_file')->nullable();
            $table->string('image_url')->nullable();
            $table->date('release_date')->nullable();
            $table->integer('duration')->nullable();
            $table->unsignedBigInteger('artist_id');
            $table->unsignedBigInteger('album_id')->nullable();
            $table->text('lyrics')->nullable();
            $table->integer('plays')->default(0);
            $table->string('genres')->nullable();
            $table->timestamps();

            $table->foreign('artist_id')->references('artist_id')->on('artists')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('songs');
    }
}
