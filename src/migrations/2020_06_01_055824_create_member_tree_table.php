<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberTreeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_tree', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->bigInteger('member_id')->nullable(false)->default(0)->comment('member 主键ID')->primary('member_id');
            $table->bigInteger('pid')->nullable(false)->default(0)->comment('推荐人主键ID');
            $table->bigInteger('lft')->nullable(false)->default(0)->comment('lft');
            $table->bigInteger('rgt')->nullable(false)->default(0)->comment('rgt');

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
        Schema::dropIfExists('member_tree');
    }
}
