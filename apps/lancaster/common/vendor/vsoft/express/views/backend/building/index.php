<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use lajax\translatemanager\models\Language;

/* @var $this yii\web\View */
/* @var $searchModel vsoft\express\models\LcBuildingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Buildings';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(Yii::getAlias('@web') . '/js/building.js', ['depends' => ['yii\web\YiiAsset']]);

$languages = Language::getLanguageNames(true);
$languagesUrl = [];

foreach ($languages as $languageCode => $languageName) {
	$languagesUrl[Url::to(['index', 'l' => $languageCode])] = $languageName;
}

$l = Yii::$app->request->get('l') ? Url::to(['index', 'l' => Yii::$app->request->get('l')]) : Url::to(['index', 'l' => Yii::$app->sourceLanguage]);
?>
<div class="lc-building-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Building', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

	<div class="form-group">
		<label class="control-label" for="lcbuilding-building_name">Hiển thị danh sách bằng ngôn ngữ</label>
		<?= Html::dropDownList('language', $l, $languagesUrl, ['class' => 'form-control', 'id' => 'language-switch-view']) ?>
	</div>
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'building_name',
            'floor',
            'address',
            'phone',
            'fax',
            'email:email',
            'hotline',
            'description',
//            'created_at',
//            'updated_at',
//            'created_by',
//            'updated_by',
            'isbooking:boolean',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
