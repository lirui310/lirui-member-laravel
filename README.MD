## member module

### 安装

---
`composer install lirui\lirui-member-laravel`

`php artisan vendor:publish --provider lirui\member\MemberServiceProvider`

`php artisan migrate`

`php artisan init-table`

 
 #### 注意 请设置 config/database中设置 strict修改为false 非严格模式运行mysql 否则获取节点统计SQL异常
 

### service使用 字段只做基础验证 字段高级验证根据自身业务处理

---

##### MemberService

$memberService = new lirui\member\Services\MemberService();




##### MemberInfoService


##### MemberAccountService


##### MemberTreeService




### errorCode

---
- 抛出异常

100： 异常错误

- 登录相关

1： 会员状态不是1

2： 会员username或者password不对

- 验证相关

150: username已经被注册

- info信息相关

200: 保存info信息失败，没有找到id的info信息或者info信息为空


### artisan命令
---
- 初始化数据表：
`php artisan init-table` 清空所有表 初始化 member_tree表的数据 第一次安装或者清空测试数据使用

- Tree结构重新生成：
`php artisan reset-member-tree` 初始化 member_tree表的数据 根据member数据重新生成tree中的数据 可用于修复tree的数据

`php artisan reset-member-tree --id=1` 可以传参数 默认id=1开始

