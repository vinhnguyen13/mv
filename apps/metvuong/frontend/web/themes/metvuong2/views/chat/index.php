<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src='../js/strophe.js'></script>
<script>
    var BOSH_SERVICE = 'http://metvuong.com:5280/wating';
    //    var BOSH_SERVICE = 'http://172.30.6.104:5280/wating';
    //    var BOSH_SERVICE = 'http://dev.metvuong.com:5222/wating';
    var connection = null;
    function log(msg) {
        $('#log').append('<div></div>').append(document.createTextNode(msg));
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
        var to = msg.getAttribute('to');
        var from = msg.getAttribute('from');
        var type = msg.getAttribute('type');
        var elems = msg.getElementsByTagName('body');
        if (type == "chat" && elems.length > 0) {
            var body = elems[0];
            log('ECHOBOT: I got a message from ' + from + ': ' +
                Strophe.getText(body));
            var text = Strophe.getText(body) + " (this is echo)";

            //var reply = $msg({to: from, from: to, type: 'chat', id: 'purple4dac25e4'}).c('active', {xmlns: "http://jabber.org/protocol/chatstates"}).up().cnode(body);
            //.cnode(Strophe.copyElement(body));
            //connection.send(reply.tree());
            log('ECHOBOT: I sent ' + from + ': ' + Strophe.getText(body));
        }

        if (type == "headline") {
            log('headline from: ' + from + ' send to: ' + to);
//        var body = elems[0];
//        log(Strophe.getText(body));
        }
        // we must return true to keep the handler alive.
        // returning false would remove it after it finishes.
        return true;
    }

    function sendMessage() {
        var message = $('#message').get(0).value;
        var to = $('#to').get(0).value;
        if (message && to) {
            var reply = $msg({
                to: to,
                type: 'chat'
            }).cnode(Strophe.xmlElement('body', message)).up().c('active', {xmlns: "http://jabber.org/protocol/chatstates"});
            connection.send(reply);
            log('I sent ' + to + ': ' + message);
        }
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
        $(document).on('click', '#send', function () {
            sendMessage();
            sendNotification();
        });

        connection = new Strophe.Connection(BOSH_SERVICE);
        connection.rawInput = rawInput;
        connection.rawOutput = rawOutput;

        $('#connect').bind('click', function () {
            var button = $('#connect').get(0);
            if (button.value == 'connect') {
                button.value = 'disconnect';
                connection.connect($('#jid').get(0).value,
                    $('#pass').get(0).value,
                    onConnect);
            } else {
                button.value = 'connect';
                connection.disconnect();
            }
        });

    });
</script>


<div class="chat-group">
    <div class="title-chat clearfix"><em class="fa fa-close pull-right"></em><em class="fa fa-comments"></em>Nhà mô giới
    </div>
    <div class="wrap-chat clearfix">
        <div class="wrap-me chat-infor">
            <div class="avatar-chat pull-left"><a href="#"><img
                        src="/frontend/web/themes/metvuong2/resources/images/2015 - dddd1.jpg" alt=""></a></div>
            <div class="wrap-txt-chat pull-left">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            </div>
        </div>
        <div class="wrap-you chat-infor">
            <div class="avatar-chat pull-right"><a href="#"><img
                        src="/frontend/web/themes/metvuong2/resources/images/621042015085736.jpg" alt=""></a></div>
            <div class="wrap-txt-chat pull-right">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            </div>
        </div>
        <div class="wrap-me chat-infor">
            <div class="avatar-chat pull-left"><a href="#"><img
                        src="/frontend/web/themes/metvuong2/resources/images/2015 - dddd1.jpg" alt=""></a></div>
            <div class="wrap-txt-chat pull-left">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            </div>
        </div>
        <div class="wrap-you chat-infor">
            <div class="avatar-chat pull-right"><a href="#"><img
                        src="/frontend/web/themes/metvuong2/resources/images/621042015085736.jpg" alt=""></a></div>
            <div class="wrap-txt-chat pull-right">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            </div>
        </div>
        <div class="wrap-me chat-infor">
            <div class="avatar-chat pull-left"><a href="#"><img
                        src="/frontend/web/themes/metvuong2/resources/images/2015 - dddd1.jpg" alt=""></a></div>
            <div class="wrap-txt-chat pull-left">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            </div>
        </div>
        <div class="wrap-you chat-infor">
            <div class="avatar-chat pull-right"><a href="#"><img
                        src="/frontend/web/themes/metvuong2/resources/images/621042015085736.jpg" alt=""></a></div>
            <div class="wrap-txt-chat pull-right">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
            </div>
        </div>
        <div class="loading-chat">
            Typing<span class="one">.</span><span class="two">.</span><span class="three">.</span></div>
    </div>
    <div class="type-input-chat"><input type="text" placeholder="Nhập tin nhắn...">
        <button class="sm-chat"><em class="fa fa-location-arrow"></em></button>
    </div>
</div>



<div id="fb-root"></div>
<div id='login' style='text-align: center'>
    <form name='cred'>
        <label for='jid'>JID:</label>
        <input type='text' id='jid' value="admin@metvuong.com">
        <label for='pass'>Password:</label>
        <input type='password' id='pass' value="123456">
        <input type='button' id='connect' value='connect'>
    </form>

    <label for='to'>
        to:
    </label>
    <input type='text' id='to' value="vinh2@chat.metvuong.com">
    <label for='message'>
        message:
    </label>
    <input type='text' id='message' value="xxxxxxxxxxxx">
    <input type='button' id='send' value='send'>

</div>
<hr>
<div id='log'></div>
