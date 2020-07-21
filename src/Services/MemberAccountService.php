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

    public function setAccount(int $memberId, int $account, int $type, $number, string $remark = ''): bool
    {
        // 处理获取的表
        $m = $memberId % 3;
        $table = 'table' . $m;
        $tableName = $this->$table;

        // 组装账户
        $accountName = 'account_' . $account;
        $accountBelowString = 'account_' . $account . '_below_0';
        $accountBelow0 = $this->$accountBelowString;

        $precision = $this->getPrecision($account);

        try {
            DB::beginTransaction();
            $memberAccount = DB::table('member_account')->where(['member_id' => $memberId])->lockForUpdate()->get();
            if (!$memberAccount) {
                throw new \Exception('account error');
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

}