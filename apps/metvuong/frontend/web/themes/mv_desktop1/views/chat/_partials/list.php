<?php
use yii\helpers\Url;
use frontend\models\Chat;
use yii\web\View;
$params = [':jid' => Chat::find()->getJid(Yii::$app->user->identity->username)];
$jid_id = Yii::$app->dbChat->createCommand('SELECT jid_id FROM tig_ma_jids tmj WHERE jid=:jid')->bindValues($params)->queryOne();
if(!empty($jid_id)){
	$sql = 'SELECT tbl.* '.
		'FROM (SELECT owner_id, buddy_id, ts, body, direction, IF(owner_id = :jid_id, buddy_id, owner_id) AS withuser FROM tig_ma_msgs tmm WHERE :jid_id IN (owner_id, buddy_id) AND (owner_id != buddy_id) ORDER BY ts DESC) as tbl '.
		'GROUP BY tbl.withuser ORDER BY tbl.ts DESC LIMIT 5';
	$msgs = Yii::$app->dbChat->createCommand($sql)->bindValues([':jid_id'=>$jid_id['jid_id']])->queryAll();
}
?>
<ul class="clearfix head-list-message">
<?php
if(!empty($msgs)) {
	foreach($msgs as $msg){
		$jid_user = Yii::$app->get('dbChat')->cache(function ($db) use ($msg) {
			return Yii::$app->get('dbChat')->createCommand('SELECT jid FROM tig_ma_jids tmj WHERE jid_id=:jid_id')->bindValues([':jid_id'=>$msg['withuser']])->queryOne();
		});
		if(!empty($jid_user['jid'])){
			$username = Chat::find()->getUsername($jid_user['jid']);
			$user = \frontend\models\User::find()->where(['username' => $username])->one();
		}
		if(!empty($user->profile)){
	?>
			<li chat-with="<?=$user->username;?>">
				<a href="<?=Url::to(['/chat/index', 'username'=> Yii::$app->user->identity->getUsername()])?>#<?=$user->username;?>">
					<span class="avatar"><img src="<?=$user->profile->getAvatarUrl();?>" alt="" /></span>
					<div>
						<span class="name-user"><?=$user->profile->getDisplayName();?></span>
						<span class="txt-message"><?=$msg['body'];?></span>
						<span class="time-message"><?=date('H:i:s d-m-Y', strtotime($msg['ts']));?></span>
					</div>
				</a>
			</li>
	<?php
		}
	}
}else{
	?>
	<li>
		<p><?=Yii::t('common', '{object} no data', ['object' => Yii::t('chat', 'Chat')])?></p>
	</li>
	<?php
}
?>
</ul>
<script>
	$(document).ready(function () {
		$(document).on('click', '.head-list-message li a', function (e) {
			$(document).trigger('click');
			var hash = $(this).attr('href').split('#')[1];
			if(hash.length > 0){
				console.log(hash, '______________');
				$('.wrap-history .chat-list .item[chat-with="' + hash + '"] a').trigger( "click" );
			}
		});
	});
</script>