<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WechatController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        $wechat = app('wechat');
        $wechat->server->setMessageHandler(function($message){
            return "测试自动部署!";
        });

        return $wechat->server->serve();
    }
}
