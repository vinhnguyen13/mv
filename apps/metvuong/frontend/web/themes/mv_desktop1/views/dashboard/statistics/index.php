<?php
use vsoft\ad\models\AdProduct;
use yii\web\View;
use yii\helpers\Url;

Yii::$app->getView()->registerJsFile('http://code.highcharts.com/highcharts.js', ['position' => View::POS_HEAD]);

$id = $product->id;
$address = $product->getAddress();
$urlDetail = $product->urlDetail(true);

$finderFrom = (!empty($finders) && isset($finders["from"])) ? $finders["from"] : 0;
$finderTo = (!empty($finders) && isset($finders["to"])) ? $finders["to"] : 0;

?>

<div class="title-fixed-wrap">
    <div class="container">
        <div class="statis">
            <div class="title-top">
                <?=Yii::t('statistic','Statistic')?>
            </div>
        	<section class="clearfix mgB-40">
                <div class="pull-right fs-13 mgB-15">
                    <div class="clearfix d-ib ver-c">
                        <a href="<?= Url::to(['/dashboard/statistics', 'id' => $product->id, 'filter'=>'week']) ?>" class="show-view-chart<?=($filter=='week' ? ' active' : '')?>"><?=Yii::t('statistic','Week')?></a>
                        <a href="<?= Url::to(['/dashboard/statistics', 'id' => $product->id, 'filter'=>'2week']) ?>" class="show-view-chart<?=($filter=='2week' ? ' active' : '')?>"><?=Yii::t('statistic','Two weeks')?></a>
                        <a href="<?= Url::to(['/dashboard/statistics', 'id' => $product->id, 'filter'=>'month']) ?>" class="show-view-chart<?=($filter=='month' ? ' active' : '')?>"><?=Yii::t('statistic','Month')?></a>
                    </div>
                </div>
                <div class="clearfix"></div>
        		<div class="summary clearfix">
                    <div class="wrap-chart clearfix">
        				<div class="wrap-img">
                            <div class="wrapChart">
                                <?=$this->render('/dashboard/chart/'.$view, ['id' => $id, 'from' => $finderFrom, 'to' => $finderTo, 'address' => $address, 'urlDetail' => $urlDetail]);?>
                            </div>
                        </div>
        			</div>
                    <ul class="option-view-stats clearfix">
                        <li>
                            <a href="#" class="btn-finder">
                                <span class="icon-mv fs-19"><span class="icon-icons-search"></span></span> 
                                <?=Yii::t('statistic','Search')?>
                                <span><?=$search_count ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="btn-visitor radio-ui active">
                                <span class="icon-mv"><span class="icon-eye-copy"></span></span>
                                <?=Yii::t('statistic','Visit')?>
                                <span><?=$click_count ?></span>
                                <input type="radio" name="toggle-chart" value="1000000">
                                <span class="icon-mv toggle-ui-check"><span class="icon-checkbox"></span></span> 
                            </a>
                        </li>
                        <li>
                            <a href="#" class="btn-favourite radio-ui">
                                <span class="icon-mv"><span class="icon-heart-icon-listing"></span></span>
                                <?=Yii::t('statistic','Favourite')?>
                                <span><?=$fav_count ?></span>
                                <input type="radio" name="toggle-chart" value="1000000">
                                <span class="icon-mv toggle-ui-check"><span class="icon-checkbox"></span></span> 
                            </a>
                        </li>
                        <li>
                            <a href="#" class="btn-share radio-ui">
                                <span class="icon-mv"><span class="icon-share-social"></span></span> 
                                <?=Yii::t('statistic','Share')?> 
                                <span><?=$share_count?></span>
                                <input type="radio" name="toggle-chart" value="1000000">
                                <span class="icon-mv toggle-ui-check"><span class="icon-checkbox"></span></span>    
                            </a>
                        </li>
                    </ul>
        		</div>
        	</section>
        </div>
    </div>
</div>


<div class="modal fade popup-common" id="frmListVisit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header title-popup clearfix">
                <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
            </div>
            <div class="modal-body">
                <div class="wrap-modal clearfix">
                </div>
            </div>
            <div class="bottom-popup">
                <p class="push-price fs-13 text-right font-600"><span class="d-ib lh-100"><?=Yii::t('chart', 'Để tin đạt hiệu quả hơn vui lòng click vào đây')?></span> <a href="#" data-toggle="modal" data-target="#upgrade-time" data-product="79110" class="btn-nang-cap btn-up mgL-10"><?= Yii::t('statistic', 'Up') ?></a></p>
            </div>
        </div>
    </div>
</div>

<?php
$user = Yii::$app->user->identity;
$email = empty($user) ? "" : (empty($user->profile->public_email) ? $user->email : $user->profile->public_email);
$id = empty($id) ? AdProduct::find()->select(['id'])->asArray()->one()['id'] : $id;
echo $this->renderAjax('/ad/_partials/shareEmail',[
    'popup_email_name' => 'popup_email_contact',
    'pid' => $id,
    'yourEmail' => $email,
    'recipientEmail' => null,
    'params' => ['your_email' => false, 'recipient_email' => false] ])?>
<script>
    $(document).ready(function () {
        var chart = $('#chartAds').highcharts();
        var favorite_line = chart.series[2],
            share_line = chart.series[3];
            favorite_line.hide();
            share_line.hide();

        $('.option-view-stats .radio-ui').radio({
            done: function (item) {
                var index = item.parent().parent().index();

//                var series = chart.series[index];
//                if (series.visible) {
//                    series.hide();
//                } else {
//                    series.show();
//                }

                chart.series[index].show();
                for ( var i = 0; i < chart.series.length; i++ ) {
                    if ( i != index && i > 0) {
                        chart.series[i].hide();
                    }
                }
            }
        });

        $('.user-inter').slimscroll({
            alwaysVisible: true,
            height: '225px'
        });

        $(document).on('click','.btn-email-item', function () {
            var email = $(this).data('email');
            if(email) {
                $('#share_form #shareform-recipient_email').attr('value', email);
            }
        });


        $('#filterChart').val('<?=$filter?>');

        $('#filterChart').change(function(){
            var val = $(this).val();
            if (val != '') {
                var goto = '<?=Url::to(['/dashboard/statistics', 'id' => $id], true)?>'+'&filter='+val;
                window.location = goto;
            }
        });
    });

</script>