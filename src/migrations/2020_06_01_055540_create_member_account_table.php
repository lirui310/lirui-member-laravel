<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_account', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->bigInteger('member_id')->nullable(false)->default(0)->comment('member 主键ID')->primary('member_id');
            $table->decimal('account_1', 12, 2)->default(0)->comment('account_1');
            $table->decimal('account_2', 12, 2)->default(0)->comment('account_2');
            $table->decimal('account_3', 12, 2)->default(0)->comment('account_3');
            $table->decimal('account_4', 12, 2)->default(0)->comment('account_4');

            $table->decimal('account_5', 15, 4)->default(0)->comment('account_5');
            $table->decimal('account_6', 15, 4)->default(0)->comment('account_6');

            $table->integer('account_7')->default(0)->comment('account_7');
            $table->integer('account_8')->default(0)->comment('account_8');

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
        Schema::dropIfExists('member_account');
    }
}
