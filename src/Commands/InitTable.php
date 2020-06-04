<?php

namespace lirui\member\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use lirui\member\Services\MemberTreeService;

class InitTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'init table: clear table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 数据表清空
        DB::statement('TRUNCATE TABLE `member`');
        DB::statement('TRUNCATE TABLE `member_info`');
        DB::statement('TRUNCATE TABLE `member_account`');
        DB::statement('TRUNCATE TABLE `member_account_log_0`');
        DB::statement('TRUNCATE TABLE `member_account_log_1`');
        DB::statement('TRUNCATE TABLE `member_account_log_2`');
        DB::statement('TRUNCATE TABLE `member_tree`');
        DB::statement('TRUNCATE TABLE `member_login_log`');

        // 初始化tree数据
        $memberTreeService = new MemberTreeService();
        $memberTreeService->initTableData();

        $this->info('数据表初始化:success');
    }
}