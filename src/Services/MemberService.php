<?php


namespace lirui\member\Services;


use Illuminate\Support\Facades\DB;
use lirui\member\Models\Member;
use lirui\member\Models\MemberAccount;
use lirui\member\Models\MemberInfo;

class MemberService extends BaseService
{
    /**
     * 用户注册
     * @param string $username
     * @param string $password
     * @param int $pid 推荐人id 如果这个值 不使用 推荐人code
     * @param string $code 没有填写推荐人id 使用这个code去查找
     * @param array $info 注册的时候可以 添加的info
     * @param bool $setTree 是否记录到tree数据中
     * @return bool
     */
    public function register(string $username, string $password, int $pid = 0, string $code = '', array $info = [], bool $setTree = false): bool
    {
        DB::beginTransaction();
        try {
            $member = new Member();
            $member->username = $username;
            $member->salt = $salt = $this->generateSalt();
            $member->password = $this->generatePassword($password, $salt);
            $member->code = $this->generateCode();
            $member->pid = $pid ? $pid : ($code ? $this->getIdByCode($code) : 0);
            $member->save();

            $id = $member->id;
            if ($setTree) {
                // 创建tree 关系表
                $this->treeAdd($id, $pid);
            }
            // 创建基本信息
            $memberInfo = new MemberInfo();
            $memberInfo->member_id = $id;
            if ($info) {
                foreach ($info as $k => $v) {
                    $memberInfo->$k = $v;
                }
            }
            $memberInfo->save();

            // 创建账户
            $memberAccount = new MemberAccount();
            $memberAccount->member_id = $id;
            $memberAccount->save();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->setErrorCode(100);
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * 用户登陆
     * @param string $username
     * @param string $password
     * @param bool $setLoginLog
     * @return array
     */
    public function login(string $username, string $password, bool $setLoginLog = false): array
    {
        $result = [];
        $member = Member::query()->where(['username', $username])->first();
        $check = $member ? ($this->checkPasswordBySalt($member->salt, $password, $member->password)) : false;
        if ($check) {
            if ($member->status != 1) {
                $this->setErrorCode(1);
                $this->setError('status is not 1!');
            } else {
                $result['id'] = $id = $member->id;
                $result['username'] = $username;
                // 登陆成功 写入日志
                $setLoginLog && $this->loginLog($id, $username);
            }
        } else {
            $this->setErrorCode(2);
            $this->setError('username or password is wrong!');
        }

        return $result;
    }

    public function getIdByCode(string $code): int
    {
        $id = Member::query()->whereRaw("binary code='{$code}'")->value('id');
        return $id ?? 0;
    }

    public function getMemberById(int $id): Member
    {
        return Member::find($id);
    }

    public function getMemberInfoById(int $id)
    {
        return Member::find($id)->info;
    }

    public function getMemberAccountById(int $id)
    {
        return Member::find($id)->account;
    }

    public function getStatusById(int $id): int
    {
        $member = $this->getMemberById($id);
        return $member ? (int)($member->status) : 0;
    }

    public function getRegisterTimeById(int $id): string
    {
        $member = $this->getMemberById($id);
        return $member ? (string)($member->created_at) : '';
    }

    public function checkPasswordById(int $id, string $password): bool
    {
        $member = Member::find($id);
        return $member ? $this->checkPasswordBySalt($member->salt, $password, $member->password) : false;
    }

    public function checkPasswordBySalt(string $salt, string $password, string $tablePassword): bool
    {
        return ($this->generatePassword($password, $salt) == $tablePassword) ? true : false;
    }

    protected function generateCode(): string
    {
        $code = '';
        $charArray = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'W', 'X', 'Y', 'Z',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'w', 'x', 'y', 'z', '0', '1',
            '2', '3', '4', '5', '6', '7', '8', '9'
        ];
        for ($i = 0; $i < 8; $i++) {
            $key = array_rand($charArray);
            $code .= $charArray[$key];
        }
        return (string)$code;
    }

    protected function generateSalt(): string
    {
        return (string)(mt_rand(1000, 9999) . mt_rand(100, 999));
    }

    protected function generatePassword(string $password, string $salt): string
    {
        return md5($password . $salt . $this->salt);
    }

}