<?php
use yii\web\View;
use yii\helpers\Url;
use frontend\models\Chat;
if(!Yii::$app->user->isGuest){
$script = "var xmpp_jid = '".Yii::$app->user->identity->username."';var xmpp_dm = '".Chat::find()->getDomain()."';var xmpp_key = '".Chat::find()->getKey()."';";
Yii::$app->getView()->registerJs($script, View::POS_HEAD);

Yii::$app->getView()->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.5/handlebars.min.js', ['position'=>View::POS_BEGIN]);

Yii::$app->getView()->registerJsFile('/js/strophe.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.chatstates.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.disco.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.muc.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.ping.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.pubsub.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.register.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('/js/lib/strophe.roster.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('/js/lib/chat.ui.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile('/js/lib/chat.js', ['position'=>View::POS_BEGIN]);
?>
<script>
    $(document).ready(function () {
        $(this).trigger('chat/connect');
        $(document).bind('chat/receiveMessage', function (event, data) {
            if(data.type != chatUI.MSG_SEND_ME){
                chatUI.notify(data.from, data.to, chatUI.NOTIFY_CHAT);
            }
        });
    });
</script>
<?php }?>