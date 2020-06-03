<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberAccountLog0Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_account_log_0', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->bigIncrements('id');
            $table->bigInteger('member_id')->nullable(false)->default(0)->comment('变动 member 主键ID');
            $table->char('account', 30)->nullable(false)->default(0)->comment('变动账户 account_1,account_2...');
            $table->decimal('number', 15,4)->nullable(false)->default(0)->comment('变动数量');
            $table->tinyInteger('type')->nullable(false)->default(0)->comment('变动类型');
            $table->string('remark', 255)->nullable(false)->default('')->comment('变动说明');
            $table->timestamps();

            // 创建索引
            $table->index(['member_id', 'account', 'type']);
            $table->index(['account', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_account_log_0');
    }
}
