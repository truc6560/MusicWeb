<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListenHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('listen_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('song_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('played_at')->useCurrent();
            $table->foreign('song_id')->references('song_id')->on('songs')->onDelete('cascade');
            $table->index('song_id');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('listen_history');
    }
}
