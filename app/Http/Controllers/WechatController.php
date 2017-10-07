<?php

namespace App\Http\Controllers;

use EasyWeChat\Message\Article;
use EasyWeChat\Message\Image;
use EasyWeChat\Message\Material;
use EasyWeChat\Message\News;
use EasyWeChat\Message\Text;
use EasyWeChat\Message\Video;
use EasyWeChat\Message\Voice;
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
                      . $this->withEvent($message->Event, $message);
                  break;
                case 'text':
                        $text = $this->withText($message->Content, $message);
                        return $text;
                  break;
                case 'image':
                        $text = new Image();
                        $text->media_id = $message->MediaId;
                        // 公众号在收到用户发送图片消息时，会将该图片存放在http://mmbiz.qpic.cn/mmbiz_jpg站点.
                        return $text;
                  break;
                case 'video':
                        $video = new Video();
                        $video->media_id = $message->MediaId;
                        $video->thumb_media_id  = $message->ThumbMediaId;
                        $video->title = '测试数据标题';
                        $video->description = '测试数据描述';
                        return $video;
                    break;
                case 'voice':
                        $voice = new Voice();
                        $voice->media_id = $message->MediaId;
                  return $voice;
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

    public function withText($text, $message)
    {
        $toUserName = $message->ToUserName;
        $fromUserName = $message->FromUserName;
        $createTime = $message->CreateTime;
        switch ($text) {
            case 'tw':
                $new1 = new News([
                    'title' => '测试数据1',
                    'description' => '测试数据描述',
                    'url' => 'www.baidu.com',
                    'image' => 'https://i.pximg.net/c/600x600/img-master/img/2017/09/23/21/31/40/65101437_p0_master1200.jpg'
                ]);
                $new2 = new News([
                    'title' => '测试数据2',
                    'description' => '测试数据描述',
                    'url' => 'www.baidu.com',
                    'image' => 'https://i.pximg.net/c/600x600/img-master/img/2017/09/23/21/31/40/65101437_p0_master1200.jpg'
                ]);
                $new3 = new News([
                    'title' => '测试数据3',
                    'description' => '测试数据描述',
                    'url' => 'www.baidu.com',
                    'image' => 'https://i.pximg.net/c/600x600/img-master/img/2017/09/23/21/31/40/65101437_p0_master1200.jpg'
                ]);
                return [$new1, $new2, $new3];
                break;
            case 'wz':
                $article = new Article([
                    'title' => 'DkfWeChat',
                    'author' => 'dkf',
                    'content' => '测试数据,测试数据,测试数据,测试数据,测试数据,测试数据,测试数据,测试数据.',
                    'thumb_media_id' => '04c3f38da9773912145e5d4afb198618377ae2e7.jpg',
                    'digest' => '图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空',
                    'source_url' => 'www.dengkangfa.com',
                    'show_cover' => 0
                ]);
                return $article;
                break;
            case 'sc':
                $material = new Material('image', '04c3f38da9773912145e5d4afb198618377ae2e7.jpg');
                return $material;
                break;
            case '群发':
                return $this->massMessage($message);
                break;
            default:
                $text = new Text();
                $text->content = '收到文字消息：收到'.$fromUserName.' 发送给 '.$toUserName.'的文字消息 <'.date('Y-m-d H:i:s', $createTime).'>'
                    . '文本消息内容：' . $message->Content;
                return $text;
                break;
        }
    }

    public function massMessage($message) {
        $wechat = app('wechat');

        $broadcast = $wechat->broadcast;
        $result = $broadcast->previewText("你好啊！", $message->FromUserName);
        return $broadcast->status($result->msg_id);
    }
}
