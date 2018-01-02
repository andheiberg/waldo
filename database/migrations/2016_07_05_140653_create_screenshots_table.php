<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScreenshotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('screenshots', function (Blueprint $table) {
            $table->string('id');
            $table->string('commit_id');
            $table->string('branch_id');
            $table->string('env');
            $table->string('suite');
            $table->string('feature');
            $table->string('scenario');
            $table->string('step');
            $table->string('user_agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('platform')->nullable();
            $table->string('screen')->nullable();
            $table->boolean('touch')->nullable();
            $table->integer('score')->nullable();
            $table->string('base_line_id')->nullable();
            $table->integer('diff_path')->nullable();
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
        Schema::drop('screenshots');
    }
}
