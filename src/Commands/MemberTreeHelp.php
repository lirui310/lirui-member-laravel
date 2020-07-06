<?php

namespace lirui\member\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use lirui\member\Services\MemberTreeService;

class MemberTreeHelp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'member-tree-help 
                            {method} 
                            {param1?} 
                            {param2?} 
                            {param3?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'member_tree console help MethodName:';

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
        $methodName = $this->option('method');
        $param1 = $this->option('param1');
        $param2 = $this->option('param2');
        $param3 = $this->option('param3');

        $reflection = new \ReflectionClass('lirui\member\Services\MemberTreeService');

        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            $name = $method->name;
            $params = $method->getParameters();
            $count = count($params);
            if ($name == $methodName) {
                $result = $this->doMethod($name, $count, $param1, $param2, $param3);
                $this->info($result);
                break;
            }
        }
    }

    protected function doMethod(string $name, int $count = 0, mixed $param1 = null, mixed $param2 = null, mixed $param3 = null): string
    {
        $memberTreeService = new MemberTreeService();
        switch ($count) {
            case 0:
                $result = $memberTreeService->$name();
                break;
            case 1:
                $result = $memberTreeService->$name($param1);
                break;
            case 2:
                $result = $memberTreeService->$name($param1, $param2);
                break;
            case 3:
                $result = $memberTreeService->$name($param1, $param2, $param3);
                break;
            default:
                $result = '参数数量异常';
        }

        return is_string($result) ? $result : json_encode($result);
    }
}