<?php
$this->title = Yii::t('express/about', 'About Us');
/* @var $this yii\web\View */
use yii\helpers\Html;
?>

<div class="container-fluid abaout">
    <div class="row main_content">
        <span class="btn_back"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/IMG/btn_back.png"><a href="<?=\yii\helpers\Url::home()?>"><?= Yii::t('express/about', 'Back to Lancaster Legacy') ?></a></span>
        <h1 class="title"><?=Yii::t('express/about','About Us') ?></h1>

        <?php foreach ($news as $key=>$new) {?>
            <?php
                echo Html::tag('div',
                    Html::tag('div',
                        Html::a(Html::img($new->getUrlBanner($new->banner)), \yii\helpers\Url::toRoute(['/express/about/detail', 'id' => $new->id, 'slug' => $new->slug])),
                        ['class'=>'mainleft']
                    ).
                    Html::tag('div',
                        Html::tag('ul',
                            Html::tag('li',Html::a($new->title, \yii\helpers\Url::toRoute(['/express/about/detail', 'id' => $new->id, 'slug' => $new->slug])), ['class'=>'title']).
                            Html::tag('li',$new->content, [])
                        ),
                        ['class'=>'mainright']
                    )
                    ,
                    ['class'=>'forximg flush-col'.((1) ? ' big' : '')]
                );
            ?>
        <?php } ?>
    </div>
</div>
