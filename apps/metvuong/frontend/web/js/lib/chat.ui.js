//(function(){
    var chatUI = {
        NOTIFY_CHAT: 1,
        NOTIFY_OTHER: 2,
        MSG_SEND_ME: 1,
        MSG_SEND_YOU: 2,
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
        loadMessageHistoryToBox: function (iq) {
            console.log('_________________________________', iq);
            var objHis = $(iq);
            var child = objHis.children().children();
            var length = child.length - 1;
            var chatList = '';
            chatBoxExist = chatUI.getBoxChat(objHis.attr('id'), objHis.children().attr('with'));
            if(!chatBoxExist){
                return false;
            }
            for(i=0;i<length;i++) {
                var item = child.eq(i);
                var body = item.find('body').text();
                if(item.is('to')) {
                    var chatItem = chatUI.buildMessageToBox(chatUI.usrFromJid(objHis.attr('id')), body, 1);
                } else {
                    var chatItem = chatUI.buildMessageToBox(chatUI.usrFromJid(objHis.children().attr('with')), body, 2);
                }
                chatList += chatItem;
            }
            if(chatBoxExist.find('.loading-chat').length > 0){
                $(html).insertBefore( chatBoxExist.find('.wrap-chat .loading-chat') );
            }else{
                chatBoxExist.find('.wrap-chat').append(chatList);
            }
            $('.container-chat').scrollTop($('.wrap-chat').height());
        },
        buildMessageToBox: function (username, msg, type) {
            msg = chatUI.decodeEntities(msg);
            if(type == 1){
                var template = Handlebars.compile($("#chat-send-template").html());
                var html = template({msg: msg, avatarUrl: '/member/'+username+'/avatar'});
            }else if(type == 2){
                var template = Handlebars.compile($("#chat-receive-template").html());
                var html = template({msg: msg, avatarUrl: '/member/'+username+'/avatar'});
            }
            return html;
        },
        loadMessageToBox: function (from, to, msg, type) {
            chatBoxExist = chatUI.getBoxChat(from, to);
            if(!chatBoxExist){
                return false;
            }
            var from = chatUI.usrFromJid(from);
            var to = chatUI.usrFromJid(to);
            if(type == 1){
                var html = chatUI.buildMessageToBox(chatUI.usrFromJid(xmpp_jid), msg, type);
            }else if(type == 2){
                var html = chatUI.buildMessageToBox(chatUI.usrFromJid(from), msg, type);
            }else{
                var html = document.createTextNode(msg);
            }
            if(chatBoxExist.find('.loading-chat').length > 0){
                $(html).insertBefore( chatBoxExist.find('.wrap-chat .loading-chat') );
            }else{
                chatBoxExist.find('.wrap-chat').append(html);
            }
            $('.container-chat').scrollTop($('.wrap-chat').height());
            $(document).trigger('chat/readNotify', [chatUI.NOTIFY_CHAT]);
        },
        loadMessageToList: function (from, to, msg, type, fromName, toName) {
            msg = chatUI.decodeEntities(msg);
            var chatBoxExist = $('.chat-history');
            var template = Handlebars.compile($("#chat-receive-template").html());
            var html = template({msg: msg, avatarUrl: '/member/'+chatUI.usrFromJid(from)+'/avatar', time: $.now(), fromName: fromName, chatUrl: '/chat/'+chatUI.usrFromJid(from), to: chatUI.usrFromJid(from)});
            if($(".item[chat-to='" + chatUI.usrFromJid(to) + "']")){
                $(".item[chat-to='" + chatUI.usrFromJid(to) + "']").remove();
            }
            if($(".item[chat-to='" + chatUI.usrFromJid(from) + "']")){
                $(".item[chat-to='" + chatUI.usrFromJid(from) + "']").remove();
            }
            chatBoxExist.find('.chat-list').prepend(html);
            $(document).trigger('chat/readNotify', [chatUI.NOTIFY_CHAT]);
        },
        notify: function (from, to, type) {
            var sumChat = 0, sumOther = 0, sumTotal = 0;
            if(type == chatUI.NOTIFY_CHAT){
                sumChat = (($('#notifyChat').length > 0) ? parseInt($('#notifyChat').html()) : 0) + 1;
                var objNotifyChat = this.findObjectNotify('wrapNotifyChat', 'notifyChat');
                objNotifyChat.html(sumChat);
            }else if(type == chatUI.NOTIFY_OTHER){
                sumOther = (($('#notifyOther').length > 0) ? parseInt($('#notifyOther').html()) : 0) + 1;
                var objNotifyOther = this.findObjectNotify('wrapNotifyOther', 'notifyOther');
                objNotifyOther.html(sumOther);
            }
            sumTotal = (($('#notifyChat').length > 0) ? parseInt($('#notifyChat').html()) : 0) + (($('#notifyOther').length > 0) ? parseInt($('#notifyOther').html()) : 0);
            var objNotifyTotal = this.findObjectNotify('avatar-user', 'notifyTotal');
            objNotifyTotal.html(sumTotal);
        },
        findObjectNotify: function (parent, child) {
            if($('#'+parent).find('#'+child).length == 0){
                $('#'+parent).append($('<span id="'+child+'"></span>'));
            }
            return $('#'+parent).find('#'+child);
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
        },
        decodeEntities: function (encodedString) {
            var textArea = document.createElement('textarea');
            textArea.innerHTML = encodedString;
            return textArea.value;
        },
        formatOutPut: function (encodedString) {

        }
    };

//})();