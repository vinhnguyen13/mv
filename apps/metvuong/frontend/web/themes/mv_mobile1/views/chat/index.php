<?php
use yii\web\View;
use yii\helpers\Url;

Yii::$app->getView()->registerCssFile('/css/chat.css');
$from = Yii::$app->request->get('from', 'admin');
$to = Yii::$app->request->get('to', 'kt200707g11');

$script = <<< JS
var usrname = '__username__';
var dm = 'metvuong.com';
JS;
Yii::$app->getView()->registerJs(strtr($script, ['__username__'=>$from/*Yii::$app->user->identity->username*/]), View::POS_HEAD);
Yii::$app->getView()->registerJsFile('/js/strophe.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('/js/chat.js', ['position'=>View::POS_BEGIN]);
?>
<ul>
    <li><a href="chat?from=kt200707g11&to=admin">kt200707g11</a></li>
    <li><a href="chat?from=admin&to=kt200707g11">admin</a></li>
</ul>
<script id="chat-send-template" type="text/x-handlebars-template">
    <div class="wrap-me chat-infor">
        <div class="avatar-chat pull-left"><a href="#"><img src="/frontend/web/themes/metvuong2/resources/images/2015 - dddd1.jpg" alt=""></a></div>
        <div class="wrap-txt-chat pull-left">
            {{msg}}
        </div>
    </div>
</script>
<script id="chat-receive-template" type="text/x-handlebars-template">
    <div class="wrap-you chat-infor">
        <div class="avatar-chat pull-right"><a href="#"><img src="/frontend/web/themes/metvuong2/resources/images/621042015085736.jpg" alt=""></a></div>
        <div class="wrap-txt-chat pull-right">
            {{msg}}
        </div>
    </div>
</script>
<script id="chat-typing-template" type="text/x-handlebars-template">
    <div class="loading-chat">Typing<span class="one">.</span><span class="two">.</span><span class="three">.</span></div>
</script>

<div class="chat-group" to="<?=$to;?>">
    <div class="title-chat clearfix"><em class="fa fa-close pull-right"></em><em class="fa fa-comments"></em><?=$to;?></div>
    <div class="wrap-chat clearfix">
    </div>
    <div class="type-input-chat"><input type="text" id="typingMsg" placeholder="Nhập tin nhắn...">
        <button class="sm-chat"><em class="fa fa-location-arrow"></em></button>
    </div>
</div>

