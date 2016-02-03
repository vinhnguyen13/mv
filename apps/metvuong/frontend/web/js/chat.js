(function(){
    var connection = null, timer = 0;
    var chatUI = {
        appendMsg: function(msg, typeMsg) {
            chatUI.showTyping();
            if(typeMsg == 1){
                var msgAppend = $('#chat-send-template').html().replace("{{msg}}", msg);
                $('.wrap-chat').append(msgAppend);
            }else if(typeMsg == 2){
                var msgAppend = $('#chat-receive-template').html().replace("{{msg}}", msg);
                $('.wrap-chat').append(msgAppend);
            }else{
                $('.wrap-chat').append('<div></div>').append(document.createTextNode(msg));
            }
            $(".wrap-chat").scrollTop(99999999999);
        },
        sendMessage: function(){
            var msg = $('#typingMsg').val();
            var to = chatFunction.getJid($('.chat-group').attr('chat-to'));
            chatFunction.sendMessage(to, msg);
            $('#typingMsg').val('');
        },
        showChatBox: function(from, to){
            from = chatFunction.parseJid(from);
            to = chatFunction.parseJid(to);
            var chatBoxExist = $(".chat-group[chat-from='"+from+"'][chat-to='"+to+"']");
            if(chatBoxExist.length > 0){
                chatBoxExist.show();
            }else{
                $('#chat-container').append($('#chat-box-template').html().replace("{{from}}", from).replace("{{to}}", to).replace("{{to}}", to));
            }
        },
        showTyping: function(from){
            if($('.loading-chat')){
                $('.loading-chat').remove();
            }
            if(from){
                var msgAppend = $('#chat-typing-template').html().replace("{{from}}", from);
                $('.wrap-chat').append(msgAppend);
            }
        },
        showHideChatBox: function(){
            if ( $('.title-chat').hasClass('active') ) {
                $('.title-chat').parent().css('height','auto');
                $('.title-chat').removeClass('active');
            }else {
                $('.title-chat').parent().css('height','34px');
                $('.title-chat').addClass('active');
            }
        },
        rawInput: function(data) {
            //chatUI.appendMsg('RECV: ' + data);
        },
        rawOutput: function(data) {
            //chatUI.appendMsg('SENT: ' + data);
        },
    };

    var chatFunction = {
        connect: function(xmpp_jid, xmpp_dm, xmpp_key) {
            var BOSH_SERVICE = 'http://'+xmpp_dm+':5280/wating';
            connection = new Strophe.Connection(BOSH_SERVICE);
            connection.rawInput = chatUI.rawInput;
            connection.rawOutput = chatUI.rawOutput;
            connection.connect(chatFunction.getJid(xmpp_jid), xmpp_key, chatFunction.onConnect);
            /*connection.disconnect();*/
        },
        getJid: function(jid) {
            return jid+'@'+xmpp_dm;
        },
        parseJid: function(jid_full) {
            if(jid_full.indexOf('@'+xmpp_dm)){
                return jid_full.split('@')[0];
            }
            return jid_full;
        },
        onConnect: function(status) {
            if (status == Strophe.Status.CONNECTING) {
                chatUI.appendMsg('Strophe is connecting.');
            } else if (status == Strophe.Status.CONNFAIL) {
                chatUI.appendMsg('Strophe failed to connect.');
            } else if (status == Strophe.Status.DISCONNECTING) {
                chatUI.appendMsg('Strophe is disconnecting.');
            } else if (status == Strophe.Status.DISCONNECTED) {
                chatUI.appendMsg('Strophe is disconnected.');
            } else if (status == Strophe.Status.CONNECTED) {
                chatUI.appendMsg('Strophe is connected.');
                $('.chat-group #typingMsg').prop( "disabled", false );
                connection.addHandler(chatFunction.onMessage, null, 'message', 'chat', null, null);
                connection.addHandler(chatFunction.onHeadline, null, 'message', 'headline');
                connection.addHandler(chatFunction.onOwnMessage, null, 'iq', 'set', null, null);
                connection.send($pres().tree());
                /**
                 * auto save to database
                 */
                connection.send($iq({type: 'set', id: 'autoSave'}).c('auto', {
                    save: 'true',
                    xmlns: 'urn:xmpp:archive'
                }));
            }
        },
        onOwnMessage: function(msg) {
            var elems = msg.getElementsByTagName('own-message');
            if (elems.length > 0) {
                var own = elems[0];
                var to = msg.getAttribute('to');
                var from = msg.getAttribute('from');
                var iq = $iq({
                    to: from,
                    type: 'error',
                    id: msg.getAttribute('id')
                }).cnode(own).up().c('error', {type: 'cancel', code: '501'})
                    .c('feature-not-implemented', {xmlns: 'urn:ietf:params:xml:ns:xmpp-stanzas'});
                connection.sendIQ(iq);
            }
            return true;
        },
        onMessage: function(msg) {
            var to = msg.getAttribute('to');
            var from = msg.getAttribute('from');
            var type = msg.getAttribute('type');
            chatUI.showChatBox(to, from);
            var typing = msg.getElementsByTagName('typing');
            if(typing.length > 0){
                var from = typing[0].getAttribute('from');
                var length = typing[0].getAttribute('length');
                if(length > 0){
                    chatUI.showTyping(from);
                }else{
                    chatUI.showTyping();
                }
            }
            var elems = msg.getElementsByTagName('body');
            if (type == "chat" && elems.length > 0) {
                var body = elems[0];
                //var reply = $msg({to: from, from: to, type: 'chat', id: 'purple4dac25e4'}).c('active', {xmlns: "http://jabber.org/protocol/chatstates"}).up().cnode(body);
                //.cnode(Strophe.copyElement(body));
                //connection.send(reply.tree());
                chatUI.appendMsg(Strophe.getText(body), 2);
            }
            return true;
        },
        onHeadline: function(msg) {
            console.log(5);
        },
        sendNotifiTyping: function(from, to, msg){
            clearTimeout(timer);
            timer = setTimeout(function() {
                connection.send($msg({
                    to: to,
                    type: 'chat'
                }).c('typing', {xmlns: "http://jabber.org/protocol/chatstates", length: msg.length, from: from}));
            }, 500);
        },
        sendMessage: function(to, message){
            if (message && to) {
                var reply = $msg({
                    to: to,
                    type: 'chat'
                }).cnode(Strophe.xmlElement('body', message)).up().c('active', {xmlns: "http://jabber.org/protocol/chatstates"});
                connection.send(reply);
                chatUI.appendMsg(message, 1);
            }
        },
    };


    $(document).ready(function () {
        $('.chat-group #typingMsg').prop( "disabled", true );
        $(this).trigger('chat/connect');
    });
	/**---ACTION---**/
	$(document).on('click','.title-chat .fa-close', function () {
        $(this).parent().parent().hide();
    });
	$(document).on('click','.title-chat', function () {
        chatUI.showHideChatBox();
	});
    $(document).on('click','.chatNow', function () {
        chatUI.showChatBox(xmpp_jid, $(this).attr('chat-to'));
    });
	$(document).on('keyup', '.chat-group #typingMsg', function (e) {
        var key = e.which;
        var msg = $('#typingMsg').val();
        var to = chatFunction.getJid($('.chat-group').attr('chat-to'));
        var from = xmpp_jid;
 		if(key == 13){
            chatUI.sendMessage();
			return false;
		}else{
            chatFunction.sendNotifiTyping(from, to, msg);
            return false;
        }
	});
	$(document).on('click', '.chat-group .sm-chat', function (e) {
        chatUI.sendMessage();
		return false;
	});
	/**---END ACTION---**/

	/**---REGISTER EVENT---**/
	$(document).bind('chat/connect', function (event, data) {
        chatFunction.connect(xmpp_jid, xmpp_dm, xmpp_key);
	});

	$(document).bind('chat/msgSend', function (event, data) {

	});
	/**---END REGISTER EVENT---**/
})();