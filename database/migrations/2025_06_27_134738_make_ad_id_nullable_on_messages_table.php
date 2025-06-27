<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeAdIdNullableOnMessagesTable extends Migration
{
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            // Pas hier de kolom 'ad_id' aan: maak het nullable
            $table->foreignId('ad_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            // Maak de kolom weer NOT NULL (niet nullable)
            $table->foreignId('ad_id')->nullable(false)->change();
        });
    }
}
