<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            // チャットID
            $table->foreignId('chat_id')
                ->constrained('chats')
                ->onDelete('cascade')
                ->comment('チャットID');

            // 投稿者ユーザーID
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('投稿者ユーザーID');

            // メッセージ本文
            $table->string('body', 400);

            // 添付画像パス
            $table->string('attachment_path', 255)
                ->nullable();

            $table->timestamps();

            // 論理削除
            $table->softDeletes()
                ->comment('論理削除日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
