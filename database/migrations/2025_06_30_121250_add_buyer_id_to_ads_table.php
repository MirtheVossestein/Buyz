<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('ads', function (Blueprint $table) {
        $table->unsignedBigInteger('buyer_id')->nullable()->after('status');

        $table->foreign('buyer_id')->references('id')->on('users')->onDelete('set null');
    });
}

public function down()
{
    Schema::table('ads', function (Blueprint $table) {
        $table->dropForeign(['buyer_id']);
        $table->dropColumn('buyer_id');
    });
}
};
