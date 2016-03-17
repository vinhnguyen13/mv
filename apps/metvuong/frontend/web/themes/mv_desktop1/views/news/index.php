<?php
use yii\helpers\Url;
    $newsCatID = isset(Yii::$app->params["newsCatID"]) ? Yii::$app->params["newsCatID"] : 0;
    $catalogs = \vsoft\news\models\CmsCatalog::findAll(['parent_id'=>$newsCatID, 'status' => \vsoft\news\models\Status::STATUS_ACTIVE]);
?>
<div class="title-fixed-wrap">
    <div class="container">
    	<div class="page-news">
    		<div class="title-top">
    			<div class="list-menu-news swiper-container">
                    <div class="container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <a class="active" href="<?=Url::to(['news/index'])?>"><?=Yii::t('news','All')?></a>
                            </div>
                            <?php if(!empty($catalogs)){?>
                            <?php foreach($catalogs as $catalog){?>
                                    <div class="swiper-slide">
                                        <a href="<?=\yii\helpers\Url::to(['news/list', 'cat_id'=>$catalog->id, 'cat_slug'=>$catalog->slug])?>"><?=Yii::t('news', $catalog->title)?></a>
                                    </div>
                            <?php }?>
                            <?php }?>
                        </div>
                    </div>
                </div>
    		</div>
            <?php if(count($news)){?>
    		<div class="wrap-news">
    			<ul class="clearfix">
                    <?php foreach($news as $n) {
                        $banner = "/store/news/show/".$n["banner"];
                        $checkBanner = file_exists(Yii::getAlias('@store')."/news/show/".$n["banner"]);
                        if($checkBanner == false)
                            $banner = '/themes/metvuong2/resources/images/default-ads.jpg';
                    ?>
    				<li>
    					<a href="<?=\yii\helpers\Url::to(['news/view', 'id' => $n["id"], 'slug' => $n["slug"]], true)?>" class="rippler rippler-default">
    						<div class="img-show"><div><img src="<?=$banner?>" alt="<?=$n["title"]?>"></div></div>
    					</a>
    					<p class="name-news"><a href="#"><?=$n["title"]?></a></p>
    					<p class="date-post"><?=date('d/m/Y, H:i', $n["created_at"])?></p>
    					<p class="short-txt">
    						<?=\yii\helpers\StringHelper::truncate($n["brief"], 200)?>
    					</p>
    				</li>
                    <?php } ?>
    			</ul>
    		</div>
            <?php } ?>
    	</div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var swiper = new Swiper('.list-menu-news', {
            paginationClickable: true,
            spaceBetween: 0,
            slidesPerView: 'auto'
        });
    });
</script>