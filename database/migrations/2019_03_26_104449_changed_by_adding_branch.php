<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangedByAddingBranch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roomcodes', function (Blueprint $table) {
            $table->tinyInteger('branch_id')->default(1);
            $table->index('branch_id');

            $table->string('room_type', 10)->change();
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->tinyInteger('branch_id')->default(1);
            $table->index('branch_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roomcodes', function (Blueprint $table) {
            $table->dropIndex('branch_id');
            $table->dropColumn('branch_id');
        });

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('branch_id');
            $table->dropIndex('branch_id');
        });
    }
}
