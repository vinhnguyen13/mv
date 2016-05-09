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
    <?php
    foreach($news as $n){
        ?>
        <div class="item clearfix">
            <a class="rippler rippler-default" href="<?= Url::to(['news/view', 'id' => $n['id'], 'slug' => $n['slug']], true) ?>" title="<?=$n['title']?>">
                <div class="img-show"><div><img src="<?=Url::to('/store/news/show/' . $n['banner']) ?>" alt="<?=$n['title']?>"></div></div>
                        <span class="txt-short-news">
                            <span class="title-news color-30a868" title="<?=$n['title']?>"><?=StringHelper::truncate($n['title'], 60)?></span>
                            <span class="date-news"><?=date('d/m/Y, H:i', $n['created_at'])?></span>
                            <span title="<?=$n['brief']?>"><?=StringHelper::truncate($n['brief'], 130)?></span>
                        </span>
            </a>
        </div>
    <?php } ?>
<?php } ?>