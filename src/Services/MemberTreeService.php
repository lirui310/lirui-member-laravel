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
     * @param int $memberId
     * @param int $pid
     * @return bool
     */
    public function add(int $memberId = 0, int $pid = 0): bool
    {
        try {
            DB::beginTransaction();
            $this->treeAdd($memberId, $pid);
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
     * @param int $memberId 节点id，默认是顶级节点0
     * @param string $limit 每次取出LIMIT（分页）
     * @return array                所有节点的id
     */
    public function getSubChild(int $memberId = 0, $limit = ""): array
    {
        $table = $this->treeTableName;
        $sql = "SELECT node.member_id FROM {$table} AS node,{$table} AS parent 
WHERE node.lft BETWEEN parent.lft AND parent.rgt AND parent.member_id = {$memberId} ORDER BY node.lft";
        if ($limit) {
            $sql .= " LIMIT $limit";
        }
        $res = DB::select($sql);
        return $res;
    }

    /**
     * @description 查看某个节点下的所有子节点 包含自己这个节点
     * @param int $memberId 节点id，默认是顶级节点0
     * @return int                统计数量
     */
    public function countSubChild(int $memberId = 0): int
    {
        $table = $this->treeTableName;
        $sql = "SELECT COUNT(node.member_id) as c FROM {$table} AS node,{$table} AS parent 
WHERE node.lft BETWEEN parent.lft AND parent.rgt AND parent.member_id = {$memberId} ORDER BY node.lft";
        $result = DB::select($sql);
        return (int)($result[0]->c);
    }

    /**
     * @description 查看某个节点下的所有子节点 不包含自己这个节点
     * @param int $memberId 节点id，默认是顶级节点0
     * @param string $limit 每次取出LIMIT（分页）
     * @return array                所有节点的id
     */
    public function getSubChildNoMe(int $memberId = 0, $limit = ""): array
    {
        $table = $this->treeTableName;
        $sql = "SELECT node.member_id FROM {$table} AS node,{$table} AS parent 
WHERE node.lft BETWEEN parent.lft AND parent.rgt AND parent.member_id = {$memberId} AND node.member_id <> {$memberId} ORDER BY node.lft";
        if ($limit) {
            $sql .= " LIMIT $limit";
        }
        $res = DB::select($sql);
        return $res;
    }

    /**
     * @description 查看某个节点下的所有子节点 不包含自己这个节点
     * @param int $memberId 节点id，默认是顶级节点0
     * @return int                统计数量
     */
    public function countSubChildNoMe(int $memberId = 0): int
    {
        $table = $this->treeTableName;
        $sql = "SELECT COUNT(node.member_id) as c FROM {$table} AS node,{$table} AS parent 
WHERE node.lft BETWEEN parent.lft AND parent.rgt AND parent.member_id = {$memberId} AND node.member_id <> {$memberId} ORDER BY node.lft";
        $result = DB::select($sql);
        return (int)($result[0]->c);
    }

    /**
     * @description 获取某个节点的所有父级uid 包含自己节点
     * @param int $memberId
     * @return array 返回uid
     */
    public function getParentIds(int $memberId = 0): array
    {
        $table = $this->treeTableName;
        if ($memberId == 0) {
            return [0];
        }
        $sql = "SELECT parent.member_id FROM {$table} AS node,{$table} AS parent WHERE node.lft BETWEEN parent.lft AND parent.rgt AND node.member_id = {$memberId} ORDER BY parent.lft";
        $res = DB::select($sql);
        return $res;
    }

    /**
     * @description 获取某个节点的所有父级uid 不包含自己节点
     * @param int $memberId
     * @return array 返回uid
     */
    public function getParentIdsNoMe(int $memberId = 0): array
    {
        $table = $this->treeTableName;
        if ($memberId == 0) {
            return [];
        }
        $sql = "SELECT parent.member_id FROM {$table} AS node,{$table} AS parent WHERE node.lft BETWEEN parent.lft AND parent.rgt AND node.member_id = {$memberId} AND parent.member_id <> {$memberId} ORDER BY parent.lft";
        $res = DB::select($sql);
        return $res;
    }
}