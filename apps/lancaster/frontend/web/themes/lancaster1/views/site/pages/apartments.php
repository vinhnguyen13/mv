<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 9/23/2015
 * Time: 2:10 PM
 */
use vsoft\express\models\LcHomeGallery;
use yii\web\View;
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/express.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJs('
    (function($) {
        Express.HomeToogleInfomation();
        Express.cropResize();
        $( window ).resize(function() {
            Express.cropResize();
        });
        $( "body" ).css({overflow: "hidden"});
    })(jQuery);
', View::POS_END);

$slideHome = LcHomeGallery::find()->where(['code'=>'APARTMENTS'])->one();
if($slideHome->getGallery()->one()){
    $photos = $slideHome->getGallery()->one()->getPhotos()->all();
}
$inner = $indicator = '';
if(!empty($photos)){
    foreach($photos as $key=>$photo):
        $inner .= $this->render('_inner', ['key'=>$key, 'photo'=>$photo]);
        $indicator .= $this->render('_indicator', ['key'=>$key, 'photo'=>$photo, 'target'=>'apartments']);
    endforeach;
}
?>
<div class="container-fluid layoutapartments">
    <div id="apartments" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <?php if(!empty($indicator)):?>
        <div class="ckeckitem">
            <div class="livercenter">
                <ul class="carousel-indicators">
                    <?=$indicator;?>
                </ul>
            </div>
        </div>
        <?php endif;?>
        <div class="carousel-inner" role="listbox">
            <?=$inner;?>
        </div>
        <a class="left carousel-control" href="#apartments" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#apartments" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div><!-- /.carousel -->
</div>
