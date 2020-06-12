<?php


namespace lirui\member\Services;


use Illuminate\Support\Facades\DB;
use lirui\member\Models\Member;
use lirui\member\Models\MemberInfo;
use lirui\member\Models\MemberTree;

class BaseService
{
    private $error;
    private $errorCode;

    protected $treeTableName = 'member_tree';

    // member_info 表拥有的字段
    public $memberInfoColumn = [
        'member_id', 'head_image', 'mobile', 'nickname', 'name', 'sex', 'age', 'area', 'zhifubao', 'weixin',
        'image_1', 'image_2', 'image_3', 'image_4', 'image_5', 'column_1', 'column_2', 'column_3',
        'column_4', 'column_5'
    ];

    // member_account 表拥有的字段
    public $memberAccountColumn = [
        'member_id', 'account_1', 'account_2', 'account_3', 'account_4', 'account_5', 'account_6', 'account_7', 'account_8'
    ];

    // module 加密salt
    protected $salt = 'lirui,1950767658@qq.com';

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param mixed $error
     */
    public function setError(string $error)
    {
        $this->error = $error;
    }

    /**
     * @return mixed
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * @param mixed $errorCode
     */
    public function setErrorCode(int $errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * 添加tree
     * @param int $member_id
     * @param int $pid
     */
    protected function treeAdd(int $member_id, int $pid)
    {
        $pData = DB::table($this->treeTableName)
            ->where(['member_id' => $pid])
            ->first();
        if (!$pData) {
            $pid = 0;
            $lft = DB::table($this->treeTableName)
                ->where(['uid' => $pid])
                ->value('lft');
        } else {
            $lft = $pData->lft;
        }

        // 更新操作
        DB::table($this->treeTableName)
            ->where('rgt', '>', $lft)
            ->increment('rgt', 2);
        DB::table($this->treeTableName)
            ->where('lft', '>', $lft)
            ->increment('lft', 2);

        $memberTree = new MemberTree();
        $memberTree->member_id = $member_id;
        $memberTree->pid = $pid;
        $memberTree->lft = $lft + 1;
        $memberTree->rgt = $lft + 2;
        $memberTree->save();
    }

    protected function loginLog(int $id, string $username): bool
    {
        return true;
    }

    /**
     * 检查 info 中的字段 是否 在数据库表字段 如果全在 返回 true  如果存在info中 但是不存在 数据表中 返回false 这样的info 是不能save的
     * @param array $info
     * @return bool
     */
    public function checkInfoColumn(array $info): bool
    {
        return true;
    }

    /**
     * 检查 account 中的字段 是否 在数据库表字段 如果全在 返回 true  如果存在account中 但是不存在 数据表中 返回false 这样的account 是不能save的
     * @param array $info
     * @return bool
     */
    public function checkAccountColumn(array $account): bool
    {
        return true;
    }

    /**
     * 检查 username是否可用 如果被注册 返回false 没有被注册 返回true
     * @param string $username
     * @return bool
     */
    public function checkUsernameCanUse(string $username): bool
    {
        $member = Member::query()->where(['username' => $username])->first();
        if ($member) {
            $this->setErrorCode(150);
            $this->setError('username is use!');
            return false;
        } else {
            return true;
        }
    }

    /**
     * 检查 password的长度是否满足条件 如果满足 返回 true 如果不满足 返回false
     * @param string $password
     * @return bool
     */
    public function checkPasswordLength(string $password, int $length = 6): bool
    {
        return strlen($password) < $length ? false : true;
    }

}