<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->enum('status', ['te_koop', 'verkocht', 'gereserveerd'])->default('te_koop')->change();
        });
    }

    public function down()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->enum('status', ['te_koop', 'verkocht'])->default('te_koop')->change();
        });
    }
};
