<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNullableColumnToArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->tinyInteger('type')->default(\App\Modules\Article\Models\Article::TYPE_IMAGE);
            $table->string('video')->nullable();
            $table->dropForeign(['category_id']);
            $table->unsignedInteger('category_id')->nullable()->change();
            $table->text('content')->nullable()->change();
            $table->string('image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->string('image')->change();
            $table->text('content')->change();
            $table->unsignedInteger('category_id')->change();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->dropColumn('video');
        });
    }
}
