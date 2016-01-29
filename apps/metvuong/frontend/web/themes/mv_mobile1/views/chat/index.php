<?php
use yii\web\View;
use yii\helpers\Url;

$script = <<< JS
var usrname = '__username__';
var dm = 'metvuong.com';
JS;
Yii::$app->getView()->registerJs(strtr($script, ['__username__'=>Yii::$app->user->identity->username]), View::POS_HEAD);
Yii::$app->getView()->registerJsFile('/js/strophe.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('/js/chat.js', ['position'=>View::POS_BEGIN]);
?>
<ul>
    <li><a>kt200707g11</a></li>
</ul>
<script id="entry-template" type="text/x-handlebars-template">
    <div class="entry">
        <h1>{{title}}</h1>
        <div class="body">
            {{body}}
        </div>
    </div>
</script>

<div class="chat-group">
    <div class="title-chat clearfix"><em class="fa fa-close pull-right"></em><em class="fa fa-comments"></em>kt200707g11</div>
    <div class="wrap-chat clearfix">
        <div class="wrap-me chat-infor">
            <div class="avatar-chat pull-left"><a href="#"><img
                        src="/frontend/web/themes/metvuong2/resources/images/2015 - dddd1.jpg" alt=""></a></div>
            <div class="wrap-txt-chat pull-left">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            </div>
        </div>
        <div class="wrap-you chat-infor">
            <div class="avatar-chat pull-right"><a href="#"><img
                        src="/frontend/web/themes/metvuong2/resources/images/621042015085736.jpg" alt=""></a></div>
            <div class="wrap-txt-chat pull-right">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            </div>
        </div>
        <div class="wrap-me chat-infor">
            <div class="avatar-chat pull-left"><a href="#"><img
                        src="/frontend/web/themes/metvuong2/resources/images/2015 - dddd1.jpg" alt=""></a></div>
            <div class="wrap-txt-chat pull-left">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            </div>
        </div>
        <div class="wrap-you chat-infor">
            <div class="avatar-chat pull-right"><a href="#"><img
                        src="/frontend/web/themes/metvuong2/resources/images/621042015085736.jpg" alt=""></a></div>
            <div class="wrap-txt-chat pull-right">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            </div>
        </div>
        <div class="wrap-me chat-infor">
            <div class="avatar-chat pull-left"><a href="#"><img
                        src="/frontend/web/themes/metvuong2/resources/images/2015 - dddd1.jpg" alt=""></a></div>
            <div class="wrap-txt-chat pull-left">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            </div>
        </div>
        <div class="wrap-you chat-infor">
            <div class="avatar-chat pull-right"><a href="#"><img
                        src="/frontend/web/themes/metvuong2/resources/images/621042015085736.jpg" alt=""></a></div>
            <div class="wrap-txt-chat pull-right">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            </div>
        </div>
        <div class="loading-chat">
            Typing<span class="one">.</span><span class="two">.</span><span class="three">.</span></div>
    </div>
    <div class="type-input-chat"><input type="text" id="typingMsg" placeholder="Nhập tin nhắn...">
        <button class="sm-chat"><em class="fa fa-location-arrow"></em></button>
    </div>
</div>

<hr>
<div id='log'></div>
