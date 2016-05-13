<?php
use yii\web\View;
use yii\helpers\Url;
use frontend\models\Chat;
if(!Yii::$app->user->isGuest) {
    $debug = !empty(Yii::$app->params['chat']['debug']) ? ( 'true') :  ( 'false');
    $script = "var xmpp_jid = '" . Yii::$app->user->identity->username . "';" .
        "var xmpp_dm = '" . Chat::find()->getDomain() . "';" .
        "var xmpp_key = '" . Chat::find()->getKey() . "';" .
        "var xmpp_debug = ".$debug.";";

    Yii::$app->getView()->registerJs($script, View::POS_HEAD);

    Yii::$app->getView()->registerJsFile('/js/libs/handlebars/handlebars.min.js', ['position' => View::POS_BEGIN]);

    Yii::$app->getView()->registerJsFile('/js/strophe.js', ['position' => View::POS_BEGIN]);
    Yii::$app->getView()->registerJsFile('/js/lib/strophe.chatstates.js', ['position' => View::POS_BEGIN]);
    Yii::$app->getView()->registerJsFile('/js/lib/strophe.disco.js', ['position' => View::POS_BEGIN]);
    Yii::$app->getView()->registerJsFile('/js/lib/strophe.muc.js', ['position' => View::POS_BEGIN]);
    Yii::$app->getView()->registerJsFile('/js/lib/strophe.ping.js', ['position' => View::POS_BEGIN]);
    Yii::$app->getView()->registerJsFile('/js/lib/strophe.pubsub.js', ['position' => View::POS_BEGIN]);
    Yii::$app->getView()->registerJsFile('/js/lib/strophe.register.js', ['position' => View::POS_BEGIN]);
    Yii::$app->getView()->registerJsFile('/js/lib/strophe.roster.js', ['position' => View::POS_BEGIN]);
    Yii::$app->getView()->registerJsFile('/js/lib/chat.ui.js', ['position' => View::POS_BEGIN]);
    Yii::$app->getView()->registerJsFile('/js/lib/chat.js', ['position' => View::POS_BEGIN]);

    $parseUrl = Yii::$app->urlManager->parseRequest(Yii::$app->request);
    $urlBase = !empty($parseUrl[0]) ? $parseUrl[0] : '';
    ?>
    <script>
        $(document).ready(function () {
            chatUI.connect();
            $(document).bind('chat/receiveMessage', function (event, msg, params) {
                chatUI.setConservation(params.from, params.to);
                if(params.type == 'chat'){
                    if(params.chatType != chatUI.MSG_SEND_ME){
//                        if($('.wrap-history-item').length == 0){
                            $(document).trigger('chat/showBoxChat', [chatUI.usrFromJid(params.from)]);
//                        }
                        chatUI.notify(chatUI.NOTIFY_CHAT, 1);
                    }
                    chatUI.loadMessageToBox(msg, params);
                }else if(params.type == 'notify'){
                    chatUI.notify(chatUI.NOTIFY_OTHER, params.total);
                    var timer = 0;
                    timer = setTimeout(function () {
                        $.ajax({
                            type: "post",
                            url: "<?=Url::to(['/notification/update', 'username'=> Yii::$app->user->identity->username])?>",
                            data: {id: true},
                            success: function (data) {

                            }
                        });
                    }, 500);
                    if(params.fromName && params.product) {
                        $('body').alertBox({
                            txt: lajax.t(msg, {owner: '<a href="#">'+params.fromName+'</a>', product: '<a href="#">'+params.product+'</a>'}),
                            duration: 4000
                        });
                    }
                }else if(params.type == 'online'){
                    console.log('------------------', params);
                }
                if(!checkMobile()){
                    $('.wrapNotifyTotal').find('#notifyTotal').hide();
                }

            });

            $(document).bind('chat/afterConnect', function (event, data) {
                $(document).trigger('chat/addWrapBoxChat');
            });

            $(document).bind('chat/removeBoxChat', function (event, data) {
                $('.wrap-items-chat .item-box-chat').remove();
            });

            $(document).bind('chat/showBoxChat', function (event, user) {
                if (user) {
                    var chatBoxExist = chatUI.getBoxChat('.item-box-chat', '<?=Yii::$app->user->identity->username?>', user);
                    if(!chatBoxExist){
                        $(document).trigger('chat/addBoxChat', [user]);
                    }else{
                        $(document).trigger('chat/activeBoxChat', [chatBoxExist]);
                    }
                }
            });

            $(document).bind('chat/activeBoxChat', function (event, chatBoxExist) {
                $('.item-box-chat .title-top').css({"background-color": "#00a769"});
                $('.item-box-chat').removeClass('active');
                if(chatBoxExist){
                    chatBoxExist.find('input').focus();
                    chatBoxExist.addClass('active');
                    chatBoxExist.find('.title-top').css({"background-color": "#008A57"});
                }
                $(document).trigger('chat/showHideMultiBox');
            });

            $(document).bind('chat/addBoxChat', function (event, user) {
                var template = Handlebars.compile($(".item-box-chat-template").html());
                var itemBoxChatTemplate = template({});
                html = $(itemBoxChatTemplate).attr('chat-to', user);
                $('.wrap-items-chat').append(html);
                var chatBoxExist = chatUI.getBoxChat('.item-box-chat', '<?=Yii::$app->user->identity->username?>', user);
                if(chatBoxExist) {
                    $('.box-chat-footer').loading({full: false});
                    $.ajax({
                        type: "get",
                        dataType: 'html',
                        url: '/chat/with/' + user,
                        success: function (data) {
                            Chat.historyMessage(user + '@<?=Chat::DOMAIN?>');
                            chatBoxExist.find('.box-chat-footer').append(data);
                            $(document).trigger('chat/activeBoxChat', [chatBoxExist]);
                            $('body').loading({done:true});
                        }
                    });
                    $('.wrap-items-chat').removeClass('hide');
                }

            });

            $(document).bind('chat/addWrapBoxChat', function (event, user) {
                var template = Handlebars.compile($(".wrap-items-chat-template").html());
                var html = template({});
                $('body').append(html);
            });

            $(document).bind('chat/readNotify', function (event, type) {
                if(type == chatUI.NOTIFY_CHAT){
                    $('#notifyChat').remove();
                    $('#notifyTotal').remove();
                }
            });

            $(document).bind('chat/showHideMultiBox', function (event, type) {
                var totalBox = 0, widthMoreEl = 80, heightMoreEl = 60, widthBox = 260;
                $('.wrap-items-chat .item-box-chat').removeClass('hide');
                totalBox = $('.wrap-items-chat .item-box-chat').length;
                var widthWindow = $(window).width();
                var totalBoxOnWindow = parseInt(widthWindow / widthBox) - 1;
                console.log('totalBoxOnWindow: '+totalBoxOnWindow,
                    'totalBox: '+totalBox
                );
                if(totalBoxOnWindow < totalBox){
                    var totalHide = 0, htmlHide = '';
                    $.each($('.wrap-items-chat .item-box-chat'),function(index, val){
                        if(!$(val).hasClass('active') && totalHide < (totalBox - totalBoxOnWindow)){
                            $(val).addClass('hide');
                            totalHide += 1;
                            htmlHide += '<li><a class="chat-now" data-chat-user="'+$(val).attr('chat-to')+'" href="javascript:;">'+$(val).attr('chat-to')+'</a></li>';
                        }
                    });
                    console.log('totalHide: '+totalHide);
                    $('.more-box-chat').removeClass('hide');
                    $('.more-box-chat').find('.dropdown-menu').html(htmlHide);
                    $('.more-box-chat').find('.dropdown-menu').css({"margin-top": "-"+($('.more-box-chat').find('.dropdown-menu').height() + heightMoreEl)+"px"});
                    $('.more-box-chat').find('.box-hide').html(totalHide);
                }else{
                    $('.more-box-chat').addClass('hide');
                }
            });

            $(document).on('click', '.chat-now', function (e) {
                user = $(this).attr('data-chat-user');
                if (user) {
                    $(document).trigger('chat/showBoxChat', [user]);
                }
                return false;
            });
            $(document).on('click', '.item-box-chat .close-box', function (e) {
                $(document).trigger('chat/showHideMultiBox');
            });
            $(document).on('click', '.typingMsg', function (e) {
                var chatBoxExist = $(this).closest('.item-box-chat');
                if (chatBoxExist) {
                    $(document).trigger('chat/activeBoxChat', [chatBoxExist]);
                }
                return false;
            });

            $( window ).resize(function() {
                $(document).trigger('chat/showHideMultiBox');
            });
        });
    </script>
    <script class="wrap-items-chat-template" type="text/x-handlebars-template">
        <div class="wrap-items-chat hide">
            <div class="more-box-chat hide">
                <div class="dropdown">
                    <a href="#" id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="icon-mv"><span class="icon-bubbles-icon"></span></span><span class="box-hide"></span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dLabel">
                    </ul>
                </div>
            </div>
        </div>
    </script>

    <script class="item-box-chat-template" type="text/x-handlebars-template">
        <div class="item-box-chat" chat-from="<?=Yii::$app->user->identity->username?>" chat-to="">
            <div class="box-chat-footer">
                <div class="option-chat-box">
                    <div class="box-dropdown guest-dropdown">
                        <a href="#" class="icon-guest val-selected wrap-img">
                            <span class="icon-mv"><span class="icon-caret-down"></span></span>
                        </a>
                        <div class="item-dropdown hide-dropdown">
                            <ul class="clearfix">
                                <li><a href="#">About</a></li>
                                <li><a href="#">Listings</a></li>
                                <li><a href="#">Reviews</a></li>
                            </ul>
                        </div>
                    </div>
                    <a href="#" class="close-box">
                        <span class="icon-mv"><span class="icon-close-icon"></span></span>
                    </a>
                </div>
            </div>
        </div>
    </script>
    <?php
}
?>