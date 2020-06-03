<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberLoginLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_login_log', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->bigIncrements('id');
            $table->bigInteger('member_id')->nullable(false)->default(0)->comment('登陆 member 主键ID');
            $table->string('ip', 255)->nullable(false)->default('')->comment('登陆IP');
            $table->string('client', 255)->nullable(false)->default('')->comment('登陆终端');
            $table->string('message', 255)->nullable(false)->default('')->comment('登陆保存其他信息（如设备码等）');

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
        Schema::dropIfExists('member_login_log');
    }
}
