//(function(){
    var chatUI = {
        connect: function() {
            Chat.connect(chatUI.genJid(xmpp_jid), xmpp_key);
        },
        genJid: function(jid) {
            return jid+'@'+xmpp_dm;
        },
        usrFromJid: function(jid_full) {
            if(jid_full.indexOf('@'+xmpp_dm)){
                return jid_full.split('@')[0];
            }
            return jid_full;
        },
        showBoxChat: function (from, to) {
            var from = chatUI.usrFromJid(from);
            var to = chatUI.usrFromJid(to);
            var template = Handlebars.compile($("#chat-box-template").html());
            var html = template({from: from, to: to});
            var chatBoxExist = $(".chat-group[chat-from='"+from+"'][chat-to='"+to+"']");
            if(chatBoxExist.length > 0){
                chatBoxExist.show();
            }else{
                $('#chat-container').append(html);
            }
            $('.chat-group').find('#typingMsg').focus();
        },
        appendMessage: function (typeMsg, msg) {
            $(this).trigger('chat/typingMessage');
            if(typeMsg == 1){
                var template = Handlebars.compile($("#chat-send-template").html());
                var html = template({msg: msg});
                $('.wrap-chat').append(html);
            }else if(typeMsg == 2){
                var template = Handlebars.compile($("#chat-receive-template").html());
                var html = template({msg: msg});
                $('.wrap-chat').append(html);
            }else{
                $('.wrap-chat').append('<div></div>').append(document.createTextNode(msg));
            }
        },
        typingMessage: function (from) {
            if($('.loading-chat')){
                $('.loading-chat').remove();
            }
            if(from) {
                from = chatUI.usrFromJid(from);
                var template = Handlebars.compile($("#chat-typing-template").html());
                var html = template({from: from});
                $('.wrap-chat').append(html);
            }
        }
    };

    $(document).bind('chat/connect', function (event, data) {
        chatUI.connect();
    });

    $(document).bind('chat/showBoxChat', function (event, data) {
        if(!data){
            return false;
        }
        chatUI.showBoxChat(data.from, data.to);
        return false;
    });

    $(document).bind('chat/appendMessage', function (event, data) {
        if(!data){
            return false;
        }
        chatUI.appendMessage(data.typeMsg, data.msg);
        return false;
    });

    $(document).bind('chat/typingMessage', function (event, data) {
        if(data.from){
            chatUI.appendMessage(data.from)
        }
        return false;
    });

//})();