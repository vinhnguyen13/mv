<?php
use vsoft\news\models\CmsShow;
use yii\helpers\Url;
use yii\helpers\StringHelper;
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 5/9/2016
 * Time: 11:22 AM
 */
$news = CmsShow::getLatestNews(4);
?>
<?php if(!empty($news)) {?>
    <ul class="clearfix">
        <?php
        foreach($news as $n){
            ?>
            <li class="col-xs-12 col-sm-6 col-lg-3">
                <div class="item clearfix">
                    <a class="rippler rippler-default" href="<?= Url::to(['news/view', 'id' => $n['id'], 'slug' => $n['slug']], true) ?>" title="<?=$n['title']?>">
                        <div class="img-show">
                            <div><img src="<?=Url::to('/store/news/show/' . $n['banner']) ?>" alt="<?=$n['title']?>"></div>
                        </div>
                        <span class="txt-short-news">
                            <span class="title-news color-30a868" title="<?=$n['title']?>"><?=StringHelper::truncate($n['title'], 60)?></span>
                            <span class="date-news"><?=date('d/m/Y, H:i', $n['created_at'])?></span>
                            <span><?=StringHelper::truncate($n['brief'], 120)?></span>
                        </span>
                    </a>
                </div>
            </li>
        <?php } ?>
    </ul>
<?php } ?>