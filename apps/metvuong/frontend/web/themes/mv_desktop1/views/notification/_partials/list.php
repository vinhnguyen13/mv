<?php
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Json;
use vsoft\ad\models\AdProduct;
use frontend\models\UserActivity;
use yii\helpers\Html;

$owner = Yii::$app->user->identity;
?>
<ul class="clearfix">
		<?php
		$query = \frontend\models\UserActivity::find();
//		$query->andWhere(['params.owner'=>[1]]);
//		$query->andWhere(['NOT IN', 'objects', [5070]]);
		$query->andWhere(['buddy_id' => Yii::$app->user->id]);
		$query->orderBy('updated DESC');
		$query->limit(5);
		$activities = $query->all();
		if(!empty($activities)) {
			foreach($activities as $activity) {
				$id = (string) $activity->_id ;
				$owner = $activity->getOwner();
				$buddy = $activity->getBuddy();
				$params = $activity->params;
				if($activity->isAction(UserActivity::ACTION_AD_FAVORITE) || $activity->isAction(UserActivity::ACTION_AD_CLICK)) {
					$product = AdProduct::findOne(['id' => $params['product']]);
					if (!empty($product)) {
						$params['owner'] = '';
						$params['product'] = $product->getAddress();
						$params['buddy'] = '';
						Html::a($activity->getBuddy()->profile->getDisplayName(), $activity->getBuddy()->urlProfile());
						$message = Yii::t('activity', $activity->message, $params);
						?>

					<li>
						<a href="<?= Url::to(['dashboard/statistics', 'id'=>$product->id]) ?>">
							<div class="wrap-alert">
								<span class="icon-mv"><span class="icon-heart-icon-listing"></span></span> <strong><?= $owner->profile->getDisplayName(); ?></strong><?= $message; ?>
							</div>
						</a>
					</li>
					<?php
					}
				}
			}
		}else{
			?>
			<li>
				<p><?=Yii::t('common', '{object} no data', ['object' => Yii::t('activity', 'Notification')])?></p>
			</li>
			<?php
		}
		?>
</ul>