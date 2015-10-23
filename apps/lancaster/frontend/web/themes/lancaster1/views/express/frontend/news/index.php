<?php
$this->title = Yii::t('express/news', 'News');
/* @var $this yii\web\View */
use yii\helpers\Html;
?>
<style>
    .pagination .disabled{
        display: none;
    }
</style>
<div class="container-fluid news">
    <div class="row main_content">
        <span class="btn_back"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/IMG/btn_back.png"><a href="<?=\yii\helpers\Url::home()?>"><?=Yii::t('express/news', 'Back to Lancaster Legacy')?></a></span>
        <h1 class="title"><?=Yii::t('express/news', 'News')?></h1>
        <?php if($pagination && $pagination->totalCount > $pagination->defaultPageSize){?>
        <div class="btn_paging">
            <?=\yii\widgets\LinkPager::widget([
                'pagination' => $pagination,
                'nextPageLabel' => 'next',
                'prevPageLabel' => 'prev',
            ]) ?>
            <span aria-hidden="true"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/IMG/btn_next.png"></span>
        </div>
        <?php }?>
        <?php foreach ($news as $key=>$new) {?>

            <?php
                echo Html::tag('div',
                    Html::tag('div',
                        Html::a(Html::img($new->getUrlBanner($new->banner)), \yii\helpers\Url::toRoute(['/express/news/detail', 'id' => $new->id, 'slug' => $new->slug])).
                        Html::tag('div',
                            Html::tag('ul',
                                Html::tag('li',date('d', $new->created_at)).
                                Html::tag('li',strtoupper(date('M', $new->created_at)))
                            ),
                            ['class'=>'marks']
                        ).
                        Html::tag('div',
                            Html::tag('ul',
                                Html::tag('li',Html::a($new->title, \yii\helpers\Url::toRoute(['/express/news/detail', 'id' => $new->id, 'slug' => $new->slug])), ['class'=>'title']).
                                Html::tag('li',\yii\helpers\StringHelper::truncate(strip_tags($new->content), (($key == 0) ? 700 : 300), "", false, true), [])
                            ),
                            ['class'=>'lockcontentleft']
                        ),
                        ['class'=>'block']
                    ),
                    ['class'=>'mainblockitem'.(($key == 0) ? ' mainblockbig' : '')]
                );
            ?>
        <?php }?>

    </div>
</div>







