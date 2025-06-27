<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConversationIdToMessagesTable extends Migration
{
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('conversation_id')->after('id')->constrained('conversations')->onDelete('cascade');
            
            $table->dropForeign(['ad_id']);
            $table->dropColumn('ad_id');
        });
    }

    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['conversation_id']);
            $table->dropColumn('conversation_id');

            $table->foreignId('ad_id')->constrained('ads')->onDelete('cascade');
        });
    }
}
