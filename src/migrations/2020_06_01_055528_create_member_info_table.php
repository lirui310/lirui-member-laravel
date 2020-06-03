<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_info', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->bigInteger('member_id')->nullable(false)->default(0)->comment('member 主键ID')->primary('member_id');
            $table->string('head_image', 255)->nullable(false)->default("")->comment('头像图片');
            $table->string('mobile', 255)->nullable(false)->default("")->comment('手机号')->index('mobile');
            $table->char('nickname', 32)->nullable(false)->default("")->comment('昵称');
            $table->char('name', 32)->nullable(false)->default("")->comment('姓名');
            $table->smallInteger('sex')->nullable(false)->default(0)->comment('年龄 0:未知 1:男 2:女');
            $table->smallInteger('age')->nullable(false)->default(0)->comment('年龄');
            $table->char('area', 255)->nullable(false)->default("")->comment('地区');
            $table->char('zhifubao', 255)->nullable(false)->default("")->comment('支付宝号');
            $table->char('weixin', 255)->nullable(false)->default("")->comment('微信号');

            $table->string('image_1', 255)->nullable(false)->default("")->comment('1号图片位置');
            $table->string('image_2', 255)->nullable(false)->default("")->comment('2号图片位置');
            $table->string('image_3', 255)->nullable(false)->default("")->comment('3号图片位置');
            $table->string('image_4', 255)->nullable(false)->default("")->comment('4号图片位置');
            $table->string('image_5', 255)->nullable(false)->default("")->comment('5号图片位置');

            $table->string('column_1', 255)->nullable(false)->default("")->comment('1号预留列');
            $table->string('column_2', 255)->nullable(false)->default("")->comment('2号预留列');
            $table->string('column_3', 255)->nullable(false)->default("")->comment('3号预留列');
            $table->string('column_4', 255)->nullable(false)->default("")->comment('4号预留列');
            $table->string('column_5', 255)->nullable(false)->default("")->comment('5号预留列');

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
        Schema::dropIfExists('member_info');
    }
}
