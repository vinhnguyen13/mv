<?php
use yii\web\View;
use yii\helpers\Url;
use frontend\models\Chat;
$to = Yii::$app->request->get('username');
$user = \frontend\models\User::find()->where(['username' => $to])->one();
?>
<?php $this->beginContent('@app/views/chat/_partials/connect.php'); ?><?php $this->endContent();?>
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
		<div class="title-top"><?=$user->profile->getDisplayName();?></div>
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
		var timer = 0;
		timer = setTimeout(function() {
			/*
			 First: Chat.mucJoin('chatroom2@muc.metvuong.com', chatUI.genJid(xmpp_jid), xmpp_key);
			 Next typing: Chat.sendMessage(to+'@muc.metvuong.com' , msg,"groupchat");
			 */
			var onlines = chatUI.onlineList();
			clearTimeout(timer);
		}, 5000);
		chatUI.showBoxChat(xmpp_jid, '<?=$to;?>');
		$(document).on('keyup', '.chat-group #typingMsg', function (e) {
			var key = e.which;
			var chatBoxExist = $(this).closest('.chat-group');
			var to = chatBoxExist.attr('chat-to');
			var to_jid = chatUI.genJid(to);
			var msg = chatBoxExist.find('#typingMsg').val();
			if(key == 13){
				Chat.sendMessage(to_jid , msg);
				Chat.sendMessage(chatUI.genJid(xmpp_jid), msg, 'chatme', {from: chatUI.genJid(xmpp_jid), to: to_jid});
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
				Chat.sendMessage(chatUI.genJid(xmpp_jid), msg, 'chatme', {from: chatUI.genJid(xmpp_jid), to: to_jid});
				chatBoxExist.find('#typingMsg').val('');
			}
			return false;
		});

		$(document).bind('chat/receiveMessage', function (event, data) {
			chatUI.appendMessageToBox(data.from, data.to, data.msg, data.type);
		});
	});
</script>