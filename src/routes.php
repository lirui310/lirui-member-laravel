<?php

// 所有API路由 api开头
Route::prefix('api')->group(function () {

    Route::post('member/register', 'lirui\member\Api\Member@register');


});
