//(function(){
    var chatUI = {
        BOSH_SERVICE: 'http://metvuong.com:5280/wating',
        connect: function() {
            Chat.connect(chatUI.genJid(xmpp_jid), xmpp_key, chatUI.BOSH_SERVICE, true);
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
        usrFromJidResource: function(jid_full_resource) {
            if(jid_full_resource.indexOf('/'+xmpp_dm)){
                return jid_full_resource.split('/')[0];
            }
            return jid_full_resource;
        },
        from: null,
        to: null,
        setConservation: function(from, to){
            chatUI.from = from;
            chatUI.to = to;
        },
        showBoxChat: function (from, to) {
            var from = chatUI.usrFromJid(from);
            var to = chatUI.usrFromJid(to);
            var template = Handlebars.compile($("#chat-box-template").html());
            var html = template({from: from, to: to});
            chatBoxExist = chatUI.getBoxChat(from, to);
            if(chatBoxExist){
                chatBoxExist.css({height: 'auto'}).show();
            }else{
                $('#chat-container').append(html);
            }
            $('.chat-group').find('#typingMsg').focus();
        },
        getBoxChat: function (from, to) {
            var from = chatUI.usrFromJid(from);
            var to = chatUI.usrFromJid(to);
            var chatBoxExist1 = $(".chat-group[chat-from='" + from + "'][chat-to='" + to + "']");
            var chatBoxExist2 = $(".chat-group[chat-from='" + to + "'][chat-to='" + from + "']");
            if (chatBoxExist1.length > 0) {
                return chatBoxExist1;
            }
            if (chatBoxExist2.length > 0) {
                return chatBoxExist2;
            }
        },
        appendMessageToBox: function (from, to, msg, type) {
            chatBoxExist = chatUI.getBoxChat(from, to);
            if(!chatBoxExist){
                return false;
            }
            var from = chatUI.usrFromJid(from);
            var to = chatUI.usrFromJid(to);
            if(type == 1){
                var template = Handlebars.compile($("#chat-send-template").html());
                var html = template({msg: msg, avatarUrl: '/member/'+chatUI.usrFromJid(xmpp_jid)+'/avatar'});
            }else if(type == 2){
                var template = Handlebars.compile($("#chat-receive-template").html());
                var html = template({msg: msg, avatarUrl: '/member/'+chatUI.usrFromJid(from)+'/avatar'});
            }else{
                var html = document.createTextNode(msg);
            }
            if(chatBoxExist.find('.loading-chat').length > 0){
                $(html).insertBefore( chatBoxExist.find('.wrap-chat .loading-chat') );
            }else{
                chatBoxExist.find('.wrap-chat').append(html);
            }
        },
        appendMessageToList: function (from, to, msg, type) {

        },
        typingMessage: function (from, close) {
            chatBoxExist = chatUI.getBoxChat(Chat.connection.jid, from);
            if(!chatBoxExist){
                return false;
            }
            if($('.loading-chat') || close){
                $('.loading-chat').remove();
            }
            if(from && !close) {
                from = chatUI.usrFromJid(from);
                var template = Handlebars.compile($("#chat-typing-template").html());
                var html = template({from: from});
                chatBoxExist.find('.wrap-chat').append(html);
            }
        },
        onlineList: function () {
            return Chat.presenceMessage;
        }
    };

    $(document).bind('chat/connect', function (event, data) {
        chatUI.connect();
    });

//})();