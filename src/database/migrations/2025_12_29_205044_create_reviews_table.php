<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade')
                ->comment('取引ID');

            $table->foreignId('reviewer_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('評価したユーザーID');

            $table->foreignId('reviewed_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('評価されたユーザーID');

            $table->unsignedTinyInteger('rating')
                ->comment('評価点（1～5）');

            $table->timestamps();

            $table->unique(['order_id', 'reviewer_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
