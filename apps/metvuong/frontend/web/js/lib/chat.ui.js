//(function(){
    var chatUI = {
        NOTIFY_CHAT: 1,
        NOTIFY_OTHER: 2,
        MSG_SEND_ME: 1,
        MSG_SEND_YOU: 2,
        //BOSH_SERVICE: 'http://metvuong.com:5280/wating',
        BOSH_SERVICE: 'ws://metvuong.com:5290/wating',
        connect: function() {
            Chat.connect(chatUI.genJid(xmpp_jid), xmpp_key, chatUI.BOSH_SERVICE, xmpp_debug);
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
            this.from = chatUI.usrFromJid(from);
            this.to = chatUI.usrFromJid(to);
        },
        showBoxChat: function (from, to, params) {
            var from = chatUI.usrFromJid(from);
            var to = chatUI.usrFromJid(to);
            var template = Handlebars.compile($(".chat-box-template").html());
            if(params){
                var html = template({from: from, to: to, fromName: params.fromName, toName: params.toName});
            }else{
                var html = template({from: from, to: to});
            }
            chatBoxExist = chatUI.getBoxChat('.chat-group', from, to);
            if(chatBoxExist){
                chatBoxExist.css({height: 'auto'}).show();
            }else{
                $(".chat-container"+"[chat-from='" + from + "'][chat-to='" + to + "']").append(html);
            }
            $('.chat-group').find('.typingMsg').focus();
        },
        getBoxChat: function (_class, from, to) {
            var from = chatUI.usrFromJid(from);
            var to = chatUI.usrFromJid(to);
            var chatBoxExist1 = $(_class + "[chat-from='" + from + "'][chat-to='" + to + "']");
            var chatBoxExist2 = $(_class + "[chat-from='" + to + "'][chat-to='" + from + "']");
            if (chatBoxExist1.length > 0) {
                return chatBoxExist1;
            }
            if (chatBoxExist2.length > 0) {
                return chatBoxExist2;
            }
        },
        loadMessageHistoryToBox: function (iq) {
            $('body').loading({done:true});
            var objHis = $(iq);
            var child = objHis.children().children();
            var length = child.length - 1;
            chatBoxExist = chatUI.getBoxChat('.chat-group', objHis.attr('id'), objHis.children().attr('with'));
            if(!chatBoxExist){
                return false;
            }
            for(i=0;i<length;i++) {
                var item = child.eq(i);
                var body = item.find('body').text();
                /**
                 * replace message
                 */
                body = stringHelper.replaceURLWithHTMLLinks(body);
                if(item.is('to')) {
                    if(chatBoxExist.find('.wrap-chat .item:last').hasClass('box-me') == true){
                        chatBoxExist.find('.wrap-chat .item:last').find('.txt-detail p').append("<br/>"+body);
                    }else{
                        var chatItem = chatUI.buildMessageToBox(chatUI.usrFromJid(objHis.attr('id')), body, chatUI.MSG_SEND_ME, {ts: item.find('body').attr('ts')});
                        chatBoxExist.find('.wrap-chat').append(chatItem);
                    }
                } else {
                    if(chatBoxExist.find('.wrap-chat .item:last').hasClass('box-you') == true){
                        chatBoxExist.find('.wrap-chat .item:last').find('.txt-detail p').append("<br/>"+body);
                    }else {
                        var chatItem = chatUI.buildMessageToBox(chatUI.usrFromJid(objHis.children().attr('with')), body, chatUI.MSG_SEND_YOU, {ts: item.find('body').attr('ts')});
                        chatBoxExist.find('.wrap-chat').append(chatItem);
                    }
                }
            }

            chatBoxExist.find('.container-chat').scrollTop(chatBoxExist.find('.wrap-chat').height());
            chatBoxExist.find('.wrap-chat-item .container-chat').slimscroll({
                alwaysVisible: true,
                height: '100%',
                start : 'bottom'
            });
            $('.chat-group').find('.typingMsg').focus();
        },
        buildMessageToBox: function (username, msg, type, params) {
            msg = chatUI.decodeEntities(msg);
            var timestamp = (params.ts) ? params.ts : 0;
            var _time = formatTime(timestamp);
            if(type == chatUI.MSG_SEND_ME){
                var template = Handlebars.compile($(".chat-send-template").html());
                var html = template({msg: msg, avatarUrl: '/member/'+username+'/avatar', time: _time});
            }else if(type == chatUI.MSG_SEND_YOU){
                var template = Handlebars.compile($(".chat-receive-template").html());
                var html = template({msg: msg, avatarUrl: '/member/'+username+'/avatar', time: _time});
            }
            return html;
        },
        loadMessageToBox: function (msg, params) {
            var type = params.chatType;
            chatBoxExist = chatUI.getBoxChat('.chat-group', this.from, this.to);
            if(!chatBoxExist){
                return false;
            }
            if(type == chatUI.MSG_SEND_ME){
                if(chatBoxExist.find('.wrap-chat .item:last').hasClass('box-me') == true){
                    chatBoxExist.find('.wrap-chat .item:last').find('.txt-detail p').append("<br/>"+msg);
                }else {
                    var html = chatUI.buildMessageToBox(chatUI.usrFromJid(this.to), msg, type, params);
                    if(chatBoxExist.find('.loading-chat').length > 0){
                        $(html).insertBefore( chatBoxExist.find('.wrap-chat .loading-chat') );
                    }else{
                        chatBoxExist.find('.wrap-chat').append(html);
                    }
                }
            }else if(type == chatUI.MSG_SEND_YOU){
                if(chatBoxExist.find('.wrap-chat .item:last').hasClass('box-you') == true){
                    chatBoxExist.find('.wrap-chat .item:last').find('.txt-detail p').append("<br/>"+msg);
                }else {
                    var html = chatUI.buildMessageToBox(chatUI.usrFromJid(this.from), msg, type, params);
                    if(chatBoxExist.find('.loading-chat').length > 0){
                        $(html).insertBefore( chatBoxExist.find('.wrap-chat .loading-chat') );
                    }else{
                        chatBoxExist.find('.wrap-chat').append(html);
                    }
                }
            }

            chatBoxExist.find('.container-chat').scrollTop(chatBoxExist.find('.wrap-chat').height());
            chatBoxExist.find('.wrap-chat-item .container-chat').slimscroll({
                alwaysVisible: true,
                height: '100%',
                start : 'bottom'
            });
            $(document).trigger('chat/readNotify', [chatUI.NOTIFY_CHAT]);
        },
        loadMessageToList: function (msg, params) {
            msg = chatUI.decodeEntities(msg);
            var timestamp = (params.ts) ? params.ts : 0;
            var _time = formatTime(timestamp);
            var chatBoxExist = $('.chat-history');
            var template = Handlebars.compile($(".chat-receive-template").html());
            var html = template({msg: msg, avatarUrl: '/member/'+chatUI.usrFromJid(this.from)+'/avatar', time: _time, fromName: params.fromName, chatUrl: '/chat/with/'+chatUI.usrFromJid(this.from), from: chatUI.usrFromJid(this.from)});
            if($(".item[chat-with='" + chatUI.usrFromJid(this.to) + "']")){
                $(".item[chat-with='" + chatUI.usrFromJid(this.to) + "']").remove();
            }
            if($(".item[chat-with='" + chatUI.usrFromJid(this.from) + "']")){
                $(".item[chat-with='" + chatUI.usrFromJid(this.from) + "']").remove();
            }
            chatBoxExist.find('.chat-list').prepend(html);
            $(document).trigger('chat/readNotify', [chatUI.NOTIFY_CHAT]);
        },
        notify: function (type, total) {
            var sumChat = 0, sumOther = 0, sumTotal = 0;
            if(type == chatUI.NOTIFY_CHAT){
                sumChat = ((parseInt(total) > 0) ? parseInt(total) : 0);
                var objNotifyChat = this.findObjectNotify('wrapNotifyChat', 'notifyChat');
                objNotifyChat.html(sumChat);
            }else if(type == chatUI.NOTIFY_OTHER){
                sumOther = ((parseInt(total) > 0) ? parseInt(total) : 0);
                var objNotifyOther = this.findObjectNotify('wrapNotifyOther', 'notifyOther');
                objNotifyOther.html(sumOther);
            }
            sumTotal = (($('#notifyChat').length > 0) ? parseInt($('#notifyChat').html()) : 0) + (($('#notifyOther').length > 0) ? parseInt($('#notifyOther').html()) : 0);
            var objNotifyTotal = this.findObjectNotify('wrapNotifyTotal', 'notifyTotal');
            objNotifyTotal.html(sumTotal);
        },
        findObjectNotify: function (parent, child) {
            if($('.'+parent).find('#'+child).length == 0){
                $('.'+parent).append($('<span class="notifi" id="'+child+'"></span>'));
            }
            return $('.'+parent).find('#'+child);
        },
        typingMessage: function (from, close) {
            chatBoxExist = chatUI.getBoxChat('.chat-group', Chat.connection.jid, from);
            if(!chatBoxExist){
                return false;
            }
            if($('.loading-chat') || close){
                $('.loading-chat').remove();
            }
            if(from && !close) {
                from = chatUI.usrFromJid(from);
                var template = Handlebars.compile($(".chat-typing-template").html());
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

var formatTime = function(unixTimestamp) {
    var date = new Date(parseInt(unixTimestamp));
    var year    = date.getFullYear();
    var month   = date.getMonth() + 1;
    var day     = date.getDate();
    var hour    = date.getHours();
    var minute  = date.getMinutes();
    var seconds = date.getSeconds();
    return hour + ":" + minute + ":" + seconds + " " + day + "-" + month + "-" + year ;
}


var stringHelper = {
    replaceURLWithHTMLLinks: function (text) {
        var urls = stringHelper.findUrls(text);
        if(urls.length > 0){
            $.each(urls, function( index, value ) {
                text = text.replace($.trim(value), "<a href='" + value + "'>" + value + "</a>");
            });
            return text;
        }else{
            return text;
        }
    },
    findUrls: function findUrls( text )
    {
        var source = (text || '').toString();
        var urlArray = [];
        var url;
        var matchArray;

        // Regular expression to find FTP, HTTP(S) and email URLs.
        var regexToken = /(((ftp|https?):\/\/)[\-\w@:%_\+.~#?,&\/\/=]+)|((mailto:)?[_.\w-]+@([\w][\w\-]+\.)+[a-zA-Z]{2,3})/g;

        // Iterate through any URLs in the text.
        while( (matchArray = regexToken.exec( source )) !== null )
        {
            var token = matchArray[0];
            urlArray.push( token );
        }

        return urlArray;
    },
    onlineList: function () {

    }
}


//})();