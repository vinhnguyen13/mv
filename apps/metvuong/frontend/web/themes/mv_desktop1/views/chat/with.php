<?php
use yii\web\View;
use yii\helpers\Url;
use frontend\models\Chat;
$to = $username;

$userTo = \frontend\models\User::find()->where(['username' => $to])->one();
$nameUserTo = $userTo->profile->getDisplayName();
$nameUserFrom = Yii::$app->user->identity->profile->getDisplayName();

?>
<div class="chat-container" chat-from="<?=Yii::$app->user->identity->username?>" chat-to="<?=$to?>">

</div>
<!--chat script-->
<script class="chat-send-template" type="text/x-handlebars-template">
	<div class="item box-me">
		<div class="chat-txt">
			<div class="txt-detail">
				<p>{{msg}}</p>
			</div>
			<div class="time-detail">{{time}}</div>
		</div>
	</div>
</script>
<script class="chat-receive-template" type="text/x-handlebars-template">
	<div class="item box-you">
		<a href="#" class="avatar wrap-img"><img src="{{avatarUrl}}" alt=""></a>
		<div class="chat-txt">
			<div class="txt-detail">
				<p>{{msg}}</p>
			</div>
			<div class="time-detail">{{time}}</div>
		</div>
	</div>
</script>
<script class="chat-typing-template" type="text/x-handlebars-template">
	<div class="loading-chat">{{from}} is typing<span class="one">.</span><span class="two">.</span><span class="three">.</span></div>
</script>
<script class="chat-box-template" type="text/x-handlebars-template">
	<div class="container">
		<div class="chat-real chat-group" chat-from="{{from}}" chat-to="{{to}}">
			<div class="title-top"><a href="/{{to}}" title="<?=Yii::t('chat', 'View profile')?>" target="_blank">{{toName}}</a></div>
			<div class="wrap-chat-item container">
				<div class="container-chat">
					<div class="wrap-chat clearfix">

					</div>
				</div>
			</div>
			<div class="type-input-chat container">
				<div>
					<input type="text" class="typingMsg" placeholder="<?=Yii::t('chat', 'Type a message...')?>">
					<button class="sm-chat"><span class="icon icon-chat-sub"></span></button>
				</div>
			</div>
		</div>
	</div>
</script>
<script>
	$(document).ready(function () {
		var timer = 0;
		clearTimeout(timer);
		timer = setTimeout(function() {
			/*
			 First: Chat.mucJoin('chatroom2@muc.metvuong.com', chatUI.genJid(xmpp_jid), xmpp_key);
			 Next typing: Chat.sendMessage('chatroom2@muc.metvuong.com' , msg,"groupchat");
			 */
			var onlines = chatUI.onlineList();
		}, 5000);
		chatUI.showBoxChat(xmpp_jid, '<?=$to;?>', {fromName: '<?=$nameUserFrom;?>', toName: '<?=$nameUserTo;?>'});
		$(document).on('keyup', '.chat-group .typingMsg', function (e) {
			var key = e.which;
			var chatBoxExist = $(this).closest('.chat-group');
			var to = chatBoxExist.attr('chat-to');
			var to_jid = chatUI.genJid(to);
			var msg = chatBoxExist.find('.typingMsg').val();
			msg = $.trim(msg);
			if(key == 13 && msg.length > 0){
				Chat.sendMessage(to_jid , msg, 'chat', {fromName: '<?=$nameUserFrom;?>', toName: '<?=$nameUserTo;?>'});
				Chat.sendMessage(chatUI.genJid(xmpp_jid), msg, 'chatme', {from: chatUI.genJid(xmpp_jid), to: to_jid, fromName: '<?=$nameUserFrom;?>', toName: '<?=$nameUserTo;?>'});
				chatBoxExist.find('.typingMsg').val('');
			}else{
				Chat.sendChatState(to_jid, 'composing');
			}
			return false;
		});
		$(document).on('click', '.chat-group .sm-chat', function (e) {
			var key = e.which;
			var chatBoxExist = $(this).closest('.chat-group');
			var to = chatBoxExist.attr('chat-to');
			var to_jid = chatUI.genJid(to);
			var msg = chatBoxExist.find('.typingMsg').val();
			msg = $.trim(msg);
			if(msg && msg.length > 0){
				Chat.sendMessage(to_jid , msg, 'chat', {fromName: '<?=$nameUserFrom;?>', toName: '<?=$nameUserTo;?>'});
				Chat.sendMessage(chatUI.genJid(xmpp_jid), msg, 'chatme', {from: chatUI.genJid(xmpp_jid), to: to_jid, fromName: '<?=$nameUserFrom;?>', toName: '<?=$nameUserTo;?>'});
				chatBoxExist.find('.typingMsg').val('');
			}
			return false;
		});

	});
</script>