<?php

namespace lirui\member\Services;


use Illuminate\Support\Facades\DB;

class MemberAccountService extends MemberService
{
    // 账户是否可以低于0
    private $account_1_below_0 = false;
    private $account_2_below_0 = false;
    private $account_3_below_0 = false;
    private $account_4_below_0 = false;
    private $account_5_below_0 = false;
    private $account_6_below_0 = false;
    private $account_7_below_0 = false;
    private $account_8_below_0 = false;

    // 账户日志表
    private $table0 = 'member_account_log_0';
    private $table1 = 'member_account_log_1';
    private $table2 = 'member_account_log_2';


    public function __construct()
    {
        $config = config('memberModule');
        $this->account_1_below_0 = $config['account_1_below_0'] ?? false;
        $this->account_2_below_0 = $config['account_2_below_0'] ?? false;
        $this->account_3_below_0 = $config['account_3_below_0'] ?? false;
        $this->account_4_below_0 = $config['account_4_below_0'] ?? false;
        $this->account_5_below_0 = $config['account_5_below_0'] ?? false;
        $this->account_6_below_0 = $config['account_6_below_0'] ?? false;
        $this->account_7_below_0 = $config['account_7_below_0'] ?? false;
        $this->account_8_below_0 = $config['account_8_below_0'] ?? false;
    }

    public function getPrecision(int $account = 1): int
    {
        if (in_array($account, [1, 2, 3, 4])) {
            return 2;
        } else if (in_array($account, [5, 6])) {
            return 4;
        } else {
            return 0;
        }
    }

    public function getTableAndAccount(int $memberId, int $account): array
    {
        // 处理获取的表
        $m = $memberId % 3;
        $table = 'table' . $m;
        $tableName = $this->$table ?? null;

        // 组装账户
        $accountName = 'account_' . $account;
        $accountBelowString = 'account_' . $account . '_below_0';
        $accountBelow0 = $this->$accountBelowString ?? null;

        return [$tableName, $accountName, $accountBelow0];
    }

    // 账户发生变动
    public function setAccount(int $memberId, int $account, int $type, $number, string $remark = ''): bool
    {
        try {
            if (!in_array($account, [1, 2, 3, 4, 5, 6, 7, 8])) {
                throw new \Exception('account error');
            }
            $tableAndAccount = $this->getTableAndAccount($memberId, $account);
            $tableName = $tableAndAccount[0];
            // 组装账户
            $accountName = $tableAndAccount[1];
            $accountBelow0 = $tableAndAccount[2];

            if (!$tableName || !$accountBelow0) {
                throw new \Exception('tableName or accountBelow0 error');
            }

            // 获取精确位置
            $precision = $this->getPrecision($account);

            DB::beginTransaction();
            $memberAccount = DB::table('member_account')->where(['member_id' => $memberId])->lockForUpdate()->get();
            if (!$memberAccount) {
                throw new \Exception('member_account is null');
            }
            $accountValue = $memberAccount->$accountName;
            $newAccountValue = bcadd($accountValue, $number, $precision);

            if (!$accountBelow0 && $newAccountValue < 0) {
                throw new \Exception('newAccountValue below 0 error');
            }
            // 修改账户
            DB::table('member_account')->where(['member_id' => $memberId])->update([$accountName => $newAccountValue]);
            // 添加日志
            $data = [];
            $data['member_id'] = $memberId;
            $data['account'] = $accountName;
            $data['number'] = $number;
            $data['type'] = $type;
            $data['remark'] = $remark;
            DB::table($tableName)->insert($data);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            $this->setErrorCode(100);
            return false;
        }
    }

    // 获取账户变动日志
    public function getAccountLog(int $memberId, int $account, int $type = -1, int $limit = 20, int $offset = 0): array
    {
        if (!in_array($account, [0, 1, 2, 3, 4, 5, 6, 7, 8])) {
            return [];
        }

        $where = [];
        if ($type >= 0) {
            $where['type'] = $type;
        }

        $tableAndAccount = $this->getTableAndAccount($memberId, $account);
        $tableName = $tableAndAccount[0];
        // 组装账户
        $accountName = $tableAndAccount[1];
        // 等于0 查询所有账户
        if ($account > 0) {
            $where['account'] = $accountName;
        }

        $buildSql = DB::table($tableName)->where($where);
        $data = $buildSql
            ->offset($offset)
            ->limit($limit)
            ->get();
        $count = $buildSql->count();

        return ['data' => $data, 'count' => $count];
    }

}