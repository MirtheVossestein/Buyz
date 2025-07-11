<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationsTable extends Migration
{
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ad_id');
            $table->unsignedBigInteger('user_one_id');  
            $table->unsignedBigInteger('user_two_id');  

            $table->timestamps();

            $table->foreign('ad_id')->references('id')->on('ads')->onDelete('cascade');
            $table->foreign('user_one_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_two_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['ad_id', 'user_one_id', 'user_two_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('conversations');
    }
}
