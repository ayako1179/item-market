<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();

            // 1取引 = 1チャット
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade')
                ->unique()
                ->comment('取引ID');

            // 購入者
            $table->foreignId('buyer_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('購入者ユーザーID');

            // 出品者
            $table->foreignId('seller_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('出品者ユーザーID');

            // 最新メッセージ日時（並び替え用）
            $table->timestamp('last_message_at')
                ->nullable()
                ->comment('最新メッセージ日時');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chats');
    }
}
