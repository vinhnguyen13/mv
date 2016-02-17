<?php
use yii\web\View;
use yii\helpers\Url;
use frontend\models\Chat;
$username = Yii::$app->user->identity->username;
$to = Yii::$app->request->get('username');
$script = "var xmpp_jid = '".$username."';var xmpp_dm = '".Chat::find()->getDomain()."';var xmpp_key = '".Chat::find()->getKey()."';";
Yii::$app->getView()->registerJs($script, View::POS_HEAD);

Yii::$app->getView()->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js', ['position'=>View::POS_BEGIN]);

Yii::$app->getView()->registerJsFile('/js/strophe.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.chatstates.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.disco.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.muc.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.ping.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.pubsub.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.register.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.roster.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile('/js/lib/chat.ui.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('/js/lib/chat.js', ['position'=>View::POS_BEGIN]);

//Yii::$app->getView()->registerCssFile('/css/chat.css');
?>

<div id="chat-container">

</div>






<!--chat script-->
<script id="chat-send-template" type="text/x-handlebars-template">
	<div class="item box-me">
		<a href="#" class="avatar wrap-img"><img src="{{avatarUrl}}" alt=""></a>
		<div class="chat-txt">
			<div class="txt-detail">
				<p>{{msg}}</p>
			</div>
			<div class="time-detail">today, 14:35</div>
		</div>
	</div>
</script>
<script id="chat-receive-template" type="text/x-handlebars-template">
	<div class="item box-you">
		<a href="#" class="avatar wrap-img"><img src="{{avatarUrl}}" alt=""></a>
		<div class="chat-txt">
			<div class="txt-detail">
				<p>{{msg}}</p>
			</div>
			<div class="time-detail">today, 14:35</div>
		</div>
	</div>
</script>
<script id="chat-typing-template" type="text/x-handlebars-template">
	<div class="loading-chat">{{from}} is typing<span class="one">.</span><span class="two">.</span><span class="three">.</span></div>
</script>
<script id="chat-box-template" type="text/x-handlebars-template">
	<div class="chat-real chat-group" chat-from="{{from}}" chat-to="{{to}}">
		<div class="title-top">{{to}}</div>
		<div class="wrap-chat-item">
			<div class="container-chat wrap-chat">

			</div>
		</div>
		<div class="type-input-chat">
			<input type="text" id="typingMsg" placeholder="Tin nhắn của bạn...">
			<button class="sm-chat"><em class="fa fa-location-arrow"></em></button>
		</div>
	</div>
</script>
<script>
	$(document).ready(function () {
		$(this).trigger('chat/connect');

		timer = setTimeout(function() {
			/*
			 First: Chat.mucJoin('chatroom2@muc.metvuong.com', chatUI.genJid(xmpp_jid), xmpp_key);
			 Next typing: Chat.sendMessage(to+'@muc.metvuong.com' , msg,"groupchat");
			 */
			console.log('===========================================');
			var onlines = chatUI.onlineList();
			console.log('===========================================', onlines);
			clearTimeout(timer);
		}, 5000);
		chatUI.showBoxChat(xmpp_jid, '<?=$to;?>');

		$(document).on('click', '.chatNow', function (e) {

		});
		$(document).on('click','.title-chat .fa-close', function () {
			$(this).closest('.chat-group').hide();
		});
		$(document).on('click','.title-chat', function () {
			if ( $('.title-chat').hasClass('active') ) {
				$('.title-chat').parent().css('height','auto');
				$('.title-chat').removeClass('active');
			}else {
				$('.title-chat').parent().css('height','34px');
				$('.title-chat').addClass('active');
			}
		});
		var timer = 0;
		$(document).on('keyup', '.chat-group #typingMsg', function (e) {
			var key = e.which;
			var chatBoxExist = $(this).closest('.chat-group');
			var to = chatBoxExist.attr('chat-to');
			var to_jid = chatUI.genJid(to);
			var msg = chatBoxExist.find('#typingMsg').val();
			if(key == 13){
				Chat.sendMessage(to_jid , msg);
				Chat.sendMessage(chatUI.genJid(xmpp_jid), msg);
				chatBoxExist.find('#typingMsg').val('');
			}else{
				Chat.sendChatState(to_jid, 'composing');
				clearTimeout(timer);
				timer = setTimeout(function() {
				}, 100);
			}
			return false;
		});
		$(document).on('click', '.chat-group .sm-chat', function (e) {
			var key = e.which;
			var chatBoxExist = $(this).closest('.chat-group');
			var to = chatBoxExist.attr('chat-to');
			var to_jid = chatUI.genJid(to);
			var msg = chatBoxExist.find('#typingMsg').val();
			if(msg){
				Chat.sendMessage(to_jid , msg);
				Chat.sendMessage(chatUI.genJid(xmpp_jid), msg);
				chatBoxExist.find('#typingMsg').val('');
			}
			return false;
		});
	});
</script>