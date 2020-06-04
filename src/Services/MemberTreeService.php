<?php


namespace lirui\member\Services;


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
}