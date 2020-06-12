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
            $this->treeAdd($member_id, $pid);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->setError($e->getMessage());
            $this->setErrorCode(100);
            return false;
        }
    }

    /**
     * @description 查看某个节点下的所有子节点 包含自己这个节点
     * @param int $member_id 节点id，默认是顶级节点0
     * @param string $limit 每次取出数量
     * @return array                所有节点的id
     */
    public function getSubChild(int $member_id = 0, string $limit = ""): array
    {
        $table = $this->treeTableName;
        $sql = "SELECT node.member_id FROM {$table} AS node,{$table} AS parent 
WHERE node.lft BETWEEN parent.lft AND parent.rgt AND parent.member_id = {$member_id} ORDER BY node.lft";
        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }
        $res = DB::select($sql);
        return $res;
    }

    /**
     * @description 查看某个节点下的所有子节点 包含自己这个节点
     * @param int $member_id 节点id，默认是顶级节点0
     * @return int                统计数量
     */
    public function countSubChild(int $member_id = 0): int
    {
        $table = $this->treeTableName;
        $sql = "SELECT COUNT(node.member_id) AS c FROM {$table} AS node,{$table} AS parent 
WHERE node.lft BETWEEN parent.lft AND parent.rgt AND parent.member_id = {$member_id} ORDER BY node.lft";
        $res = DB::select($sql);
        return 0;
    }


}