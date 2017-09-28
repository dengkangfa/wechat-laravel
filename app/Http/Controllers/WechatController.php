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
            switch($message->MsgType) {
                case 'event':
                  return '收到事件消息：收到'.$fromUserName.' 发送给 '.$toUserName.'的事件消息 <'.date('Y-m-d H:i:s', $createTime).'>' . PHP_EOV
                      . withEvent($message->Event, $message);
                  break;
                case 'text':
                  return '收到文字消息：收到'.$fromUserName.' 发送给 '.$toUserName.'的文字消息 <'.date('Y-m-d H:i:s', $createTime).'>'
                            . '文本消息内容：' . $message->Content;
                  break;
                case 'image':
                  return '收到图片消息：收到'.$fromUserName.' 发送给 '.$toUserName.'的图片消息 <'.date('Y-m-d H:i:s', $createTime).'>'
                            . '图片链接：' . $message->PicUrl;
                  break;
                case 'voice':
                  return '收到语音消息：收到'.$fromUserName.' 发送给 '.$toUserName.'的语音消息 <'.date('Y-m-d H:i:s', $createTime).'>'
                            . '语音消息媒体id：' . $message->MediaId . '语音格式：' . $message->Format . 'Recognition：' . $message->Recognition;
                  break;
                case 'location':
                  return '收到坐标消息：收到'.$fromUserName.' 发送给 '.$toUserName.'的坐标消息 <'.date('Y-m-d H:i:s', $createTime).'>'
                            . '地理位置维度：' . $message->Location_X . '地理位置经度：' . $message->Location_Y . '地图缩放大小：'
                            . $message->Scale . '地理位置信息：' . $message->Label;
                  break;
                case 'link':
                  return '收到链接消息：收到'.$fromUserName.' 发送给 '.$toUserName.'的链接消息 <'.date('Y-m-d H:i:s', $createTime).'>'
                            . '消息标题：' . $message->Title . ' 消息描述：' . $message->Description . '消息链接：' . $message->Url;
                  break;
                default:
                  return '收到其他消息：收到'.$fromUserName.' 发送给 '.$toUserName.'的其他消息 <'.date('Y-m-d H:i:s', $createTime).'>';
                  break;
            }
        });

        return $wechat->server->serve();
    }

    public function withEvent($event, $message)
    {
        switch ($event) {
            case 'subscribe':
                return '欢迎您订阅PHP分享者,EventKey' . $message->EventKey . ' Ticket' . $message->Ticket;
                break;
            case 'unsubscribe':
                return '感谢您曾经的订阅';
                break;
            case 'SCAN':
                return '你已关注我们公众号, 二维码的ticket:' . $message->Ticket . ' EventKey:' . $message->EventKey;
                break;
            case 'location':
                return '地理位置维度：' . $message->Latitude . ' 地理位置经度：' . $message->Logitude . ' 地理位置精度：' . $message->Precision;
                break;
            case 'CLICK':
                return 'click事件,事件KEY值：' . $message->EventKey;
                break;
            case 'VIEW':
                return '点击菜单跳转链接时的事件推送:' . $message->EventKey;
                break;
            default:
                return '其他事件';
        }
    }
}
