## member module

### 安装

composer install lirui\lirui-member-laravel

php artisan vendor:publish --provider lirui\member\MemberServiceProvider


### service使用 不包含字段验证 请参考提供的字段验证方法单独调用或者参考提供的API（可用于生产环境）

======================== MemberService ================================

$memberService = new lirui\member\Services\MemberService();




======================== MemberInfoService =============================


======================== MemberAccountService ==========================


======================== MemberTreeService =============================






### errorCode

======================== 抛出异常 =============================

100： 异常错误

======================== 登录相关 =============================

1： 会员状态不是1

2： 会员username或者password不对

======================== 验证相关 =============================

150: username已经被注册

======================== info信息相关 =============================

200: 保存info信息失败，没有找到id的info信息或者info信息为空

