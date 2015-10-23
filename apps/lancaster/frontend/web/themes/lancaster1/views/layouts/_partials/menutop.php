<?php
use yii\helpers\Url;
use yii\web\View;
$array = [
    '/site/index',
    Url::toRoute(['/site/page', 'view'=>'apartments']),
    Url::toRoute(['/site/page', 'view'=>'amenities']),
    Url::toRoute(['/site/page', 'view'=>'views']),
    Url::toRoute(['/site/page', 'view'=>'neighborhood']),
    Url::toRoute(['/site/page', 'view'=>'pricing']),
    Url::toRoute(['/site/page', 'view'=>'location']),
];
if(in_array(Url::current(), $array)){
?>
<style>
    .carousel-indicators div{
        padding: 0;
        margin: 0;
    }
</style>
<?php
    Yii::$app->getView()->registerJsFile('http://www.netcu.de/templates/netcu/js/jquery.touchwipe.1.1.1.js', ['position'=>View::POS_BEGIN]);
?>
<script>
    $(function(){
        $('.layoutapartments').css({backgroundColor: '#537989'});
        $('.carousel-indicators li').popover({
            html : true,
            container : 'body',
            trigger: "hover",
            placement: 'auto top',
            content: function() {
                var dataSlideTo = $(this).attr('data-slide-to');
                return $('.item:eq('+dataSlideTo+') .caption').html();
            }
        });

//        $('.carousel').bcSwipe({ threshold: 50 });
        $(".carousel").touchwipe({
            wipeLeft: function() {
                $(".carousel").carousel('next');
            },
            wipeRight: function() {
                $(".carousel").carousel('prev');
            },
            wipeUp: function() { console.log("up"); },
            wipeDown: function() { console.log("down"); },
            min_move_x: 20,
            min_move_y: 20,
            preventDefaultEvents: true
        });
    });
</script>
<div class="container-fluid menutop">
    <?php
    echo \yii\widgets\Menu::widget([
        'activeCssClass' => 'active',
        'items' => [
            ['label' => 'THE BUILDING', 'url' => Url::to(['/frontend/web/themes/lancaster2/resources/']), 'active' => (Url::current()==Url::toRoute('/site/index')) ? true : false],
            ['label' => 'APARTMENTS', 'url' => Url::toRoute(['/site/page', 'view'=>'apartments']), 'active' => (Url::current()==Url::toRoute(['/site/page', 'view'=>'apartments'])) ? true : false],
            ['label' => 'AMENITIES', 'url' => Url::toRoute(['/site/page', 'view'=>'amenities']), 'active' => (Url::current()==Url::toRoute(['/site/page', 'view'=>'amenities'])) ? true : false],
            ['label' => 'VIEWS', 'url' => Url::toRoute(['/site/page', 'view'=>'views']), 'active' => (Url::current()==Url::toRoute(['/site/page', 'view'=>'views'])) ? true : false],
            ['label' => 'NEIGHBORHOOD', 'url' => Url::toRoute(['/site/page', 'view'=>'neighborhood']), 'active' => (Url::current()==Url::toRoute(['/site/page', 'view'=>'neighborhood'])) ? true : false],
            ['label' => 'PRICING', 'url' => Url::toRoute(['/site/page', 'view'=>'pricing']), 'active' => (Url::current()==Url::toRoute(['/site/page', 'view'=>'pricing'])) ? true : false],
            ['label' => 'LOCATION', 'url' => Url::toRoute(['/site/page', 'view'=>'location']), 'active' => (Url::current()==Url::toRoute(['/site/page', 'view'=>'location'])) ? true : false],
        ],
    ]);
    ?>
</div>
<?php }?>