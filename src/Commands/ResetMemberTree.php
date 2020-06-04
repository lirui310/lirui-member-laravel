<?php

namespace lirui\member\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use lirui\member\Services\MemberTreeService;

class ResetMemberTree extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset-member-tree {--id=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reset member tree 初始化tree数据，根据member数据重新生成tree';

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
        $this->info('开始初始化member_tree');
        DB::statement('TRUNCATE TABLE `member_tree`');
        // 初始化tree数据
        $memberTreeService = new MemberTreeService();
        $memberTreeService->initTableData();

        $beginId = $this->option('id');

        $this->info("开始更新member数据，从member表中id={$beginId} 开始生成到tree中，时间根据member数据而定，请耐心等待...");

        sleep(5);

        $this->info('生成tree数据完成！');
    }
}