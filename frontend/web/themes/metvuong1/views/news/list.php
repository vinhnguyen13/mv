<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/9/2015 10:17 AM
 *
 * @var $list_news by $cat_id catalog
 */
use yii\bootstrap\Html;

?>

<div class="container-fluid">

    <!--row01-->
    <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 rowleft">
            <div class="titlebg">
                <h2 class="title"><?= Html::a('Bất động sản', ['list', 'cat_id' => $cat_id], ['style' => ['text-decoration' => 'none']]) ?></h2>
            </div>
            <?php foreach ($list_news as $k => $news) {
                if ($k === 0) { ?>
                    <div class="banner">
                        <img src="/store/news/show/<?= $news->banner ?>" alt="<?= $news->title ?>">

                        <div class="hotnew">
                            <div class="block"></div>
                            <div class="text">
                                <h2 class="titlehotnew">
                                    <?= Html::a($news->title, ['view', 'id' => $news->id], ['style' => ['text-decoration' => 'none']]) ?>
                                </h2>
                                <span class="tgpost">by Steve</span>
                                <span class="showtextcontent">
                                    <?= strlen($news->brief) > 320 ? mb_substr($news->brief, 0, 320) . '...' : $news->brief ?>
                                </span>
                                <span class="btndeitail"><button><?= Html::a('Xem chi tiết', ['view', 'id' => $news->id], ['style' => ['text-decoration' => 'none']]) ?></button></span>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <!--banner -->
                    <div class="listitemcontent">
                        <div class="grd4">
                            <div class="newstbl">
                                <?= Html::a("<img src=\"/store/news/show/$news->banner \" alt=\"$news->title\">", ['view', 'id' => $news->id], ['style' => ['text-decoration' => 'none']]) ?>

                            </div>
                            <div class="frst pdl">
                                <h3 class="title rotobobold"><?= Html::a($news->title, ['view', 'id' => $news->id], ['style' => ['text-decoration' => 'none']]) ?></h3>

                                <p class="textfrst">
                                    <?= strlen($news->brief) > 300 ? mb_substr($news->brief, 0, 300) . '...' : $news->brief ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <!--listitemcontent-->
                <?php }
            } ?>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 rgtcol">
            <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'hotnews']) ?>
        </div>
    </div>

</div>
