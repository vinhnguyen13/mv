<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model vsoft\news\models\CmsShow */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Investor'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-show-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('app', 'Back'), 'index', ['class' => 'btn btn-success pull-right']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
			[
				'attribute' => 'logo',
				'value' => '/store/building-project-images/' . $model->logo,
				'format' => ['image',['width'=>'100','height'=>'100']],
			],
			'address',
			'phone',
			'fax',
			'website',
			'email',
        ],
    ]) ?>
    <a id="back-to-top" href="#" class="btn btn-default btn-sm back-to-top pull-right"
       role="button" title="Back to Top" data-toggle="tooltip" data-placement="top">
        <span class="glyphicon glyphicon-chevron-up"></span>
    </a>

</div>
