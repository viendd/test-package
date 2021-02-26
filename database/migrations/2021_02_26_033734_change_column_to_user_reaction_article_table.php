<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnToUserReactionArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_reaction_article', function (Blueprint $table) {
            $table->renameColumn('is_trust', 'is_like');
            $table->dropColumn('evidence');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_reaction_article', function (Blueprint $table) {
            $table->string('evidence')->nullable();
            $table->renameColumn('is_like', 'is_trust');
        });
    }
}
