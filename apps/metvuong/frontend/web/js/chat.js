(function(){
	var BOSH_SERVICE = 'http://metvuong.com:5280/wating';
    //    var BOSH_SERVICE = 'http://172.30.6.104:5280/wating';
    //    var BOSH_SERVICE = 'http://dev.metvuong.com:5222/wating';
    var connection = null;
    function log(msg, typeMsg) {
    	if(typeMsg == 1){
    		var msgAppend = $('#chat-send-template').html().replace("{{msg}}", msg);
			$('.wrap-chat').append(msgAppend);
    	}else if(typeMsg == 2){
    		var msgAppend = $('#chat-receive-template').html().replace("{{msg}}", msg);
			$('.wrap-chat').append(msgAppend);
        }else if(typeMsg == 3){
            var msgAppend = $('#chat-typing-template').html().replace("{{msg}}", msg);
            $('.wrap-chat').append(msgAppend);
        }else{
    		$('.wrap-chat').append('<div></div>').append(document.createTextNode(msg));	
    	}        
    	$(".wrap-chat").scrollTop(99999999999);
    }
    function rawInput(data) {
//      log('RECV: ' + data);
    }
    function rawOutput(data) {
//      log('SENT: ' + data);
    }
    function onConnect(status) {
        if (status == Strophe.Status.CONNECTING) {
            log('Strophe is connecting.');
        } else if (status == Strophe.Status.CONNFAIL) {
            log('Strophe failed to connect.');
            $('#connect').get(0).value = 'connect';
        } else if (status == Strophe.Status.DISCONNECTING) {
            log('Strophe is disconnecting.');
        } else if (status == Strophe.Status.DISCONNECTED) {
            log('Strophe is disconnected.');
            $('#connect').get(0).value = 'connect';
        } else if (status == Strophe.Status.CONNECTED) {
            log('Strophe is connected.');
            //connection.disconnect();
            log('ECHOBOT: Send a message to ' + connection.jid +
                ' to talk to me.');
            connection.addHandler(onMessage, null, 'message', 'chat', null, null);
            connection.addHandler(onMessage, null, "message", "headline");
            connection.addHandler(onOwnMessage, null, 'iq', 'set', null, null);
            connection.send($pres().tree());
            /**
             * auto save to database
             */
            connection.send($iq({type: 'set', id: 'autoSave'}).c('auto', {
                save: 'true',
                xmlns: 'urn:xmpp:archive'
            }));

        }
    }
    function onOwnMessage(msg) {
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
    }
    function onMessage(msg) {
        if($('.loading-chat')){
            $('.loading-chat').remove();
        }
        var to = msg.getAttribute('to');
        var from = msg.getAttribute('from');
        var type = msg.getAttribute('type');
        var elems = msg.getElementsByTagName('body');
        if (type == "chat" && elems.length > 0) {
            var body = elems[0];
            //var reply = $msg({to: from, from: to, type: 'chat', id: 'purple4dac25e4'}).c('active', {xmlns: "http://jabber.org/protocol/chatstates"}).up().cnode(body);
            //.cnode(Strophe.copyElement(body));
            //connection.send(reply.tree());
            log(Strophe.getText(body), 2);
        }
        if (type == "headline") {
            var prefer = msg.getElementsByTagName('prefer');
            var length = prefer[0].getAttribute('length');
            if(length > 0){
                log(to, 3);
            }
        }
        // we must return true to keep the handler alive.
        // returning false would remove it after it finishes.
        return true;
    }

    function sendNotification() {
        var to = $('#to').get(0).value;
        /**
         *
         */
        var message = $msg({
            to: to,
            "type": 'headline'
        }).c('prefer', {xmlns: "http://jabber.org/protocol/chatstates", type: 'gone', to: to, attribute: 'value'});
        /**
         *
         * @type {*|jQuery}
         */

        var noti = $msg({
            to: to,
            type: 'headline'
        }).cnode(Strophe.xmlElement('body', Math.random())).up().c('active', {xmlns: "http://jabber.org/protocol/chatstates"});
        connection.send(message);
    }

    $(document).ready(function () {        
		$(this).trigger('chat/connect');        
    });
	/**---ACTION---**/
	$(document).on('click','.title-chat', function () {
		console.log($('#entry-template').html());
		if ( $(this).hasClass('active') ) {
			$(this).parent().css('height','auto');
			$(this).removeClass('active');
		}else {
			$(this).parent().css('height','34px');
			$(this).addClass('active');
		}
		
	});
    var timer = 0;
	$(document).on('keyup', '.chat-group #typingMsg', function (e) {
		var key = e.which;
		var msg = $(this).val();
        var to = $('.chat-group').attr('to')+'@'+dm;

        clearTimeout(timer);
        timer = setTimeout(function() {
            connection.send($msg({
                to: to,
                "type": 'headline'
            }).c('prefer', {xmlns: "http://jabber.org/protocol/chatstates", type: 'typing', to: to, length: msg.length}));
        }, 500);
 		if(key == 13){
			$(this).trigger('chat/msgSend', [{'message': msg, 'to': to}]);
			$(this).val('');			
			return false;
		}
				
		
	});

	$(document).on('click', '.sm-chat', function (e) {
		var msg = $('#typingMsg').val();
		$(this).trigger('chat/msgSend', [{'message': msg, 'to': $('.chat-group').attr('to')+'@'+dm}]);
		$('#typingMsg').val('');			
		return false;
	});	
	/**---END ACTION---**/

	/**---REGISTER EVENT---**/
	$(document).bind('chat/connect', function (event, data) {
		connection = new Strophe.Connection(BOSH_SERVICE);
        connection.rawInput = rawInput;
        connection.rawOutput = rawOutput;
		connection.connect(usrname+'@'+dm,'123456', onConnect);		
        /*connection.disconnect();*/
	});
	
	$(document).bind('chat/msgSend', function (event, data) {
		var message = data.message;
        var to = data.to;
        if (message && to) {
            var reply = $msg({
                to: to,
                type: 'chat'
            }).cnode(Strophe.xmlElement('body', message)).up().c('active', {xmlns: "http://jabber.org/protocol/chatstates"});
            connection.send(reply);
            log(message, 1);
        }
	});
	/**---END REGISTER EVENT---**/
})();