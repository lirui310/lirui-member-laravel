<?php

namespace lirui\member\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'member';

    public function info()
    {
        return $this->hasOne('lirui\member\Models\MemberInfo', 'member_id');
    }

    public function account()
    {
        return $this->hasOne('lirui\member\Models\MemberAccount', 'member_id');
    }

}