<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtendPostingItmes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posting_items', function (Blueprint $table) {
            $table->string('description', 255)->after('user_id');
            $table->text('notes')->after('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posting_items', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('notes');
        });
    }
}
