<?php


namespace lirui\member\Services;


use Illuminate\Support\Facades\DB;
use lirui\member\Models\MemberTree;

class MemberTreeService extends MemberService
{
    // 初始化数据 添加最顶级节点
    public function initTableData(): bool
    {
        $memberTree = new MemberTree();
        $memberTree->member_id = 0;
        $memberTree->pid = 0;
        $memberTree->lft = 1;
        $memberTree->rgt = 2;
        $memberTree->save();
        return true;
    }

    /**
     * @description 添加新节点
     * @param int $member_id
     * @param int $pid
     * @return bool
     */
    public function add(int $member_id = 0, int $pid = 0): bool
    {
        try {
            DB::beginTransaction();

            $pData = DB::table($this->treeTableName)
                ->where(['member_id' => $pid])
                ->first();
            if (!$pData) {
                $pid = 0;
                $lft = DB::table($this->treeTableName)
                    ->where(['uid' => $pid])
                    ->value('lft');
            }
            $lft = $pData->lft;

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

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }
}