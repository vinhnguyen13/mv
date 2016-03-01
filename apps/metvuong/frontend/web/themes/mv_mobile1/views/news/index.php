<div class="title-fixed-wrap">
	<div class="page-news">
		<div class="title-top">TIN TỨC</div>
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
					<a href="<?=\yii\helpers\Url::to(['news/view', 'id' => $n["id"], 'slug' => $n["slug"], 'cat_id' => $n["catalog_id"], 'cat_slug' => $n["cat_slug"]], true)?>" class="rippler rippler-default">
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