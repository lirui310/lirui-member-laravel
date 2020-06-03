<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->bigIncrements('id');
            $table->char('username', 20)->nullable(false)->default("")->comment('唯一username')->unique('username');
            $table->char('password', 32)->nullable(false)->default("")->comment('32位密码');
            $table->char('salt', 8)->nullable(false)->default("")->comment('加密盐');
            $table->char('code', 8)->nullable(false)->default("")->comment('会员推荐码')->unique('code');
            $table->bigInteger('pid')->nullable(false)->default(0)->comment('推荐人ID')->index('pid');
            $table->tinyInteger('authentication')->nullable(false)->default(0)->comment('是否实名认证 0:未认证 1:已认证');
            $table->tinyInteger('status')->nullable(false)->default(1)->comment('账号状态 0:非正常 1:正常');
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
        Schema::dropIfExists('member');
    }
}
