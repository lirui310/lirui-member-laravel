<?php

namespace lirui\member\Services;


use lirui\member\Models\MemberInfo;

class MemberInfoService extends MemberService
{
    public function save(int $id, array $info): bool
    {
        try {
            $memberInfo = MemberInfo::find($id);
            if ($memberInfo && $info) {
                foreach ($info as $k => $v) {
                    $memberInfo->$k = $v;
                }
                $memberInfo->save();
                return true;
            }
            $this->setErrorCode();
            return false;
        }catch (\Exception $e) {

        }

    }

    /**
     * 通过ID和字段名字获取字段的值 如果没有 返回空
     * @param int $id
     * @param string $column
     * @return string
     */
    public function getColumnValue(int $id, string $column): string
    {
        $memberInfo = MemberInfo::find($id);
        return $memberInfo ? ($memberInfo->$column ?? '') : '';
    }

    /**
     * 通过ID和字段名字 设置字段的值
     * @param int $id
     * @param string $column
     * @param $value
     * @return bool
     */
    public function setColumnValue(int $id, string $column, mixed $value): bool
    {
        $memberInfo = MemberInfo::find($id);
        if (!$memberInfo || !property_exists($memberInfo, $column)){
            return false;
        }
        $memberInfo->$column = $value;
        $memberInfo->save();
        return true;
    }
}