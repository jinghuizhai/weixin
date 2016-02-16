<?php
	//根据微信的请求分发给不同的函数处理
	require_once 'config.php';
	require_once './class/handle.php';
    file_put_contents('log.log','haha');
    class WeinxinParse{
        public $toUser;
        public $fromUser;
        public $eventKey;
        public $msgType;
        public $content;
        public $event;//subscribe unsubscribe
        private $token = 'mecaiwu';

        function __construct(){
            $this->init();
        }

        function init(){
            $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
            $postObj = simplexml_load_string($postStr);
            $this->toUser = $postObj->ToUserName;
            $this->fromUser = $postObj->FromUserName;
            $this->eventKey = $postObj->EventKey;
            $this->msgType = strtolower($postObj->MsgType);
            $this->content = $postObj->Content;
            $this->event = $postObj->Event;
        }

        function postText($content){
            $xml = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            </xml>";
            return sprintf($xml,$this->fromUser,$this->toUser,time(),$content);
        }
    }

    $weixin = new WeinxinParse;
    //文本消息
    if($weixin->msgType == 'text'){
        file_put_contents('log.log','texct');
        $content = &$weixin->content;
        if($content == '我的信息' || $content == 'my' || $content == 'me'){
            echo  $weixin->postText($weixin->fromUser);
        }else{
            $msgArr = explode('/', str_replace(' ','',$content));
            if(count($msgArr) == 2){
                preg_match('/(^0371\d{8}$)|(^\d{8}$)|(^1[34578]\d{9}$)/',$msgArr[0],$phone);
                preg_match('/^[\x{4e00}-\x{9fa5}]{2,}/u',$msgArr[1],$name);
                if(count($phone) && count($name)){
                    //存入数据库
                    echo $weixin->postText(post_recommend($weixin->fromUser,$phone[0],$name[0]));
                }else{
                    echo $weixin->postText('您发送的信息格式不正确，请重新发送');
                }
            }else{
                // echo $weixin->postText('您发送的信息格式不正确，请重新发送');
                //图灵机器人
                echo $weixin->postText(tuling($weixin->fromUser,$content));
            }
        }
    }
    
    if($weixin->msgType == 'event'){//点击事件
        switch ($weixin->eventKey) {
            case 'want_recommend'://我要推荐
                $retContent = want_recommend($weixin->fromUser);
                // $retContent = "请输入电话和姓名，格式为:15211223344/李晓明";
                break;
            case 'personal_info'://个人信息
                // $retContent =  '个人信息';
                $retContent = requestUserInfo($weixin->fromUser);
                break;
            case 'rebate_record'://佣金记录
                // $retContent = '佣金记录';
                $retContent = recordRebate(date("Y"),$weixin->fromUser);
                break;
            case 'recommend_record'://推荐记录
                $retContent =  '推荐记录';
                $retContent = recordRecommend($weixin->fromUser);
                break;
            default:
                $retContent = 'other';
                break;
        }
        echo $weixin->postText($retContent);
    }

    if($weixin->event == 'subscribe'){
        //对订阅用户的操作
    }
?>