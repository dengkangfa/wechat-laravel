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
            $toUserName = $message->ToUserName;
            $fromUserName = $message->FromUserName;
            $createTime = $message->CreateTime;
            case 'event':
              return '收到事件消息：收到'.$fromUserName.' 发送给 '.$toUserName.'的事件消息 <'.date('Y-m-d H:i:s', $createTime).'>';
              break;
            case 'text':
              return '收到文字消息：收到'.$fromUserName.' 发送给 '.$toUserName.'的文字消息 <'.date('Y-m-d H:i:s', $createTime).'>';
              break;
            case 'image':
              return '收到图片消息：收到'.$fromUserName.' 发送给 '.$toUserName.'的图片消息 <'.date('Y-m-d H:i:s', $createTime).'>';
              break;
            case 'voice':
              return '收到语音消息：收到'.$fromUserName.' 发送给 '.$toUserName.'的语音消息 <'.date('Y-m-d H:i:s', $createTime).'>';
              break;
            case 'location':
              return '收到坐标消息：收到'.$fromUserName.' 发送给 '.$toUserName.'的坐标消息 <'.date('Y-m-d H:i:s', $createTime).'>';
              break;
            case 'link':
              return '收到链接消息：收到'.$fromUserName.' 发送给 '.$toUserName.'的链接消息 <'.date('Y-m-d H:i:s', $createTime).'>';
              break;
            default:
              return '收到其他消息：收到'.$fromUserName.' 发送给 '.$toUserName.'的其他消息 <'.date('Y-m-d H:i:s', $createTime).'>';
              break;
        });

        return $wechat->server->serve();
    }
}
