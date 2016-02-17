<?php
use yii\helpers\Url;
use vsoft\chat\models\base\TigMaMsgs;
use frontend\models\Chat;

$params = [':jid' => Chat::find()->getJid(Yii::$app->user->identity->username)];
$jid_id = Yii::$app->dbChat->createCommand('SELECT jid_id FROM tig_ma_jids tmj WHERE jid=:jid')->bindValues($params)->queryOne();
if(!empty($jid_id)){
	$sql = 'SELECT tbl.* '.
		'FROM (SELECT owner_id, buddy_id, ts, body, direction, CONCAT(owner_id, "_",buddy_id) as groupchat FROM tig_ma_msgs tmm WHERE owner_id=:jid_id OR buddy_id=:jid_id ORDER BY ts DESC) as tbl '.
		'GROUP BY tbl.groupchat';
	$msgs = Yii::$app->dbChat->createCommand($sql)->bindValues([':jid_id'=>$jid_id['jid_id']])->queryAll();
}
?>
<div class="chat-history">
	<div class="title-top">Chat history</div>
	<div class="chat-list clearfix">
		<?php
		if(!empty($msgs)) {
			foreach($msgs as $msg){
				if($msg['owner_id'] == $jid_id['jid_id']){
					$jid_user = Yii::$app->get('dbChat')->cache(function ($db) use ($msg) {
						return Yii::$app->get('dbChat')->createCommand('SELECT jid FROM tig_ma_jids tmj WHERE jid_id=:jid_id')->bindValues([':jid_id'=>$msg['buddy_id']])->queryOne();
					});
				}else{
					$jid_user = Yii::$app->get('dbChat')->cache(function ($db) use ($msg) {
						return Yii::$app->get('dbChat')->createCommand('SELECT jid FROM tig_ma_jids tmj WHERE jid_id=:jid_id')->bindValues([':jid_id'=>$msg['owner_id']])->queryOne();
					});
				}
				if(!empty($jid_user['jid'])){
					$username = Chat::find()->getUsername($jid_user['jid']);
					$user = \frontend\models\User::find()->where(['username' => $username])->one();
				}
				if(!empty($user->profile)){
			?>
					<div class="item">
						<a href="<?= Url::to(['/chat/with', 'username' => $user->username]) ?>">
							<span class="wrap-img"><img src="<?=$user->profile->getAvatarUrl();?>" alt=""></span>
							<div class="chat-detail">
								<span class="pull-right time-chat"><?=$msg['ts'];?></span>
								<span class="name"><?=$user->profile->getDisplayName();?></span>
								<span><?=$msg['body'];?></span>
							</div>
						</a>
					</div>
			<?php
				}
			}
		}
		?>
	</div>
</div>