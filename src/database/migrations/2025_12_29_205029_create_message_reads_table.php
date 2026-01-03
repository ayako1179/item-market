<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageReadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_reads', function (Blueprint $table) {
            $table->id();

            $table->foreignId('message_id')
                ->constrained('messages')
                ->onDelete('cascade')
                ->comment('メッセージID');

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('ユーザーID');

            $table->timestamp('read_at')
                ->nullable()
                ->comment('既読日時');

            $table->timestamps();

            $table->unique(['message_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_reads');
    }
}
