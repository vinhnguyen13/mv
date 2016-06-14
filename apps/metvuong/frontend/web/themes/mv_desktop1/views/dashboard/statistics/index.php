<?php
use vsoft\ad\models\AdProduct;
use yii\web\View;
use yii\helpers\Url;

Yii::$app->getView()->registerJsFile('http://code.highcharts.com/highcharts.js', ['position' => View::POS_BEGIN]);

$id = $product->id;
$address = $product->getAddress();
$urlDetail = $product->urlDetail(true);
$user = Yii::$app->user->identity;
$email = empty($user) ? "" : (empty($user->profile->public_email) ? $user->email : $user->profile->public_email);

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
<!--                    <span class="d-ib mgR-20">--><?//=Yii::t('statistic','Select')?><!--</span>-->
                    <!-- <select id="filterChart" class="mgL-10">
                        <option value="week"><?=Yii::t('statistic','Week')?></option>
                        <option value="month"><?=Yii::t('statistic','Month')?></option>
                        <option value="quarter"><?=Yii::t('statistic','Quarter')?></option>
                    </select> -->
                    <div class="clearfix d-ib ver-c">
                        <a href="<?= Url::to(['/dashboard/statistics', 'id' => $product->id, 'filter'=>'week']) ?>" class="show-view-chart<?=($filter=='week' ? ' active' : '')?>"><?=Yii::t('statistic','Week')?></a>
                        <a href="<?= Url::to(['/dashboard/statistics', 'id' => $product->id, 'filter'=>'month']) ?>" class="show-view-chart<?=($filter=='month' ? ' active' : '')?>"><?=Yii::t('statistic','Month')?></a>
                        <a href="<?= Url::to(['/dashboard/statistics', 'id' => $product->id, 'filter'=>'quarter']) ?>" class="show-view-chart<?=($filter=='quarter' ? ' active' : '')?>"><?=Yii::t('statistic','Quarter')?></a>
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
                            <a href="javascript:;" class="btn-finder"> 
                                <span class="icon-mv fs-19"><span class="icon-icons-search"></span></span> 
                                <?=Yii::t('statistic','Search')?>
                                <span><?=$search_count ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;" class="btn-visitor radio-ui">
                                <span class="icon-mv"><span class="icon-eye-copy"></span></span>
                                <?=Yii::t('statistic','Visit')?>
                                <span><?=$click_count ?></span>
                                <input type="checkbox" name="toggle-chart" value="1000000" checked="checked">  
                                <span class="icon-mv toggle-ui-check"><span class="icon-checkbox"></span></span> 
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;" class="btn-favourite radio-ui">
                                <span class="icon-mv"><span class="icon-heart-icon-listing"></span></span>
                                <?=Yii::t('statistic','Favourite')?>
                                <span><?=$fav_count ?></span>
                                <input type="checkbox" name="toggle-chart" value="1000000" checked="checked">  
                                <span class="icon-mv toggle-ui-check"><span class="icon-checkbox"></span></span> 
                            </a>
                        </li>
                        <li>
                            <a href="javascript:;" class="btn-share radio-ui"> 
                                <span class="icon-mv"><span class="icon-share-social"></span></span> 
                                <?=Yii::t('statistic','Share')?> 
                                <span><?=$share_count?></span>
                                <input type="checkbox" name="toggle-chart" value="1000000" checked="checked">  
                                <span class="icon-mv toggle-ui-check"><span class="icon-checkbox"></span></span>    
                            </a>
                        </li>
                    </ul>
        		</div>
        	</section>
<!--            <h2 class="text-uper fs-16 font-600 mgB-30 color-cd">--><?//=Yii::t('statistic','STATISTIC')?><!--</h2>-->
            <table class="tbl-review" style="display: none;">
                <tr>
                    <th class="text-uper fs-15 font-600"><span class="icon-mv color-cd mgR-10"><span class="icon-icons-search fs-19"></span></span><?=Yii::t('statistic','SEARCH')?></th>
                    <th class="text-uper fs-15 font-600"><span class="icon-mv color-cd mgR-10 fs-17"><span class="icon-eye-copy"></span></span><?=Yii::t('statistic','VISIT')?></th>
                </tr>
                <tr>
                    <td>
                        <div class="user-inter">
                            <?php if(isset($finders["finders"]) && count($finders["finders"]) > 0){
                                foreach($finders["finders"] as $key => $finder){?>
                                    <div class="clearfix">
                                        <a class="fs-13" href="<?=Url::to(['member/profile', 'username'=>$key])?>">
                                            <img class="pull-left" src="<?=Url::to($finder['avatar'], true)?>" alt="">
                                            <span class="name-user"><?=$key?></span>
                                        </a>
                                        <div class="crt-item">
                                            <a href="#" class="btn-email-item mgR-15 tooltip-show" data-placement="bottom" title="Send email" data-target="#popup_email" data-type="contact" data-toggle="modal" data-email="<?=$finder['email']?>">
                                                <span class="icon-mv fs-16">
                                                    <span class="icon-mail-profile"></span>
                                                </span>
                                            </a>
                                            <a href="#" class="chat-now tooltip-show" data-chat-user="<?=$key?>" data-placement="bottom" title="Send message">
                                                <span class="icon-mv fs-18">
                                                    <span class="icon-bubbles-icon"></span>
                                                </span>
                                            </a>
                                        </div>
                                        <span class="num-show pull-right color-cd font-600"><?=$finder['count']?></span>
                                    </div>
                                <?php }
                            } else {?>
                                <div class="clearfix fs-13 text-center">
                                    <span><?=Yii::t('statistic','Not found')?>.</span><a href="<?= Url::to(['/ad/update', 'id' => $id]) ?>"><?=Yii::t('statistic','Please, update your listing')?>.</a>
                                </div>
                            <?php }?>
                        </div>
                    </td>
                    <td>
                        <div class="user-inter">
                            <?php if(isset($visitors["visitors"]) && count($visitors["visitors"]) > 0){
                                foreach($visitors["visitors"] as $key => $value){?>
                                    <div class="clearfix">
                                        <a class="fs-13" href="<?=Url::to(['member/profile', 'username'=>$key])?>">
                                            <img class="pull-left" src="<?=Url::to($value['avatar'], true)?>" alt="">
                                            <span class="name-user"><?=$key?></span>
                                        </a>
                                        <div class="crt-item">
                                            <a href="#" class="btn-email-item mgR-15 tooltip-show" data-placement="bottom" title="Send email" data-target="#popup_email" data-type="contact" data-toggle="modal" data-email="<?=$value['email']?>">
                                                <span class="icon-mv fs-16">
                                                    <span class="icon-mail-profile"></span>
                                                </span>
                                            </a>
                                            <a href="#" class="chat-now tooltip-show" data-chat-user="<?=$key?>" data-placement="bottom" title="Send message">
                                                <span class="icon-mv fs-18">
                                                    <span class="icon-bubbles-icon"></span>
                                                </span>
                                            </a>
                                        </div>
                                        <span class="num-show pull-right color-cd font-600"><?=$value['count']?></span>
                                    </div>
                                <?php }
                            } else {?>
                                <div class="clearfix fs-13 text-center">
                                    <span><?=Yii::t('statistic','Not found')?>.</span><a href="<?= Url::to(['/ad/update', 'id' => $id]) ?>"><?=Yii::t('statistic','Please, update your listing')?>.</a>
                                </div>
                            <?php }?>
                        </div>
                    </td>

                </tr>
                <tr>
                    <th class="text-uper fs-15 font-600"><span class="icon-mv color-cd mgR-10"><span class="icon-heart-icon-listing fs-19"></span></span><?=Yii::t('statistic','FAVORITE')?></th>
                    <th class="text-uper fs-15 font-600"><span class="icon-mv color-cd mgR-10"><span class="icon-share-social fs-19"></span></span><?=Yii::t('statistic','SHARE')?></th>
                </tr>
                <tr>
                    <td>
                        <div class="user-inter">
                            <?php if(isset($favourites["saved"]) && count($favourites["saved"]) > 0){
                                foreach($favourites["saved"] as $key => $value){?>
                                    <div class="clearfix">
                                        <a class="fs-13" href="<?=Url::to(['member/profile', 'username'=>$key])?>">
                                            <img class="pull-left" src="<?=Url::to($value['avatar'], true)?>" alt="">
                                            <span class="name-user"><?=$key?></span>
                                        </a>
                                        <div class="crt-item">
                                            <a href="#" class="btn-email-item mgR-15 tooltip-show" data-placement="bottom" title="Send email" data-target="#popup_email" data-type="contact" data-toggle="modal" data-email="<?=$value['email']?>">
                                                <span class="icon-mv fs-16">
                                                    <span class="icon-mail-profile"></span>
                                                </span>
                                            </a>
                                            <a href="#" class="chat-now tooltip-show" data-chat-user="<?=$key?>" data-placement="bottom" title="Send message">
                                                <span class="icon-mv fs-18">
                                                    <span class="icon-bubbles-icon"></span>
                                                </span>
                                            </a>
                                        </div>
                                        <span class="num-show pull-right color-cd font-600"><?=$value['count']?></span>
                                    </div>
                                <?php }
                            } else {?>
                                <div class="clearfix fs-13 text-center">
                                    <span><?=Yii::t('statistic','Not found')?>.</span><a href="<?= Url::to(['/ad/update', 'id' => $id]) ?>"><?=Yii::t('statistic','Please, update your listing')?>.</a>
                                </div>
                            <?php }?>
                        </div>
                    </td>
                    <td>
                        <div class="user-inter">
                            <?php if(isset($shares["shares"]) && count($shares["shares"]) > 0){
                                foreach($shares["shares"] as $key => $value){?>
                                    <div class="clearfix">
                                        <a class="fs-13" href="<?=Url::to(['member/profile', 'username'=>$key])?>">
                                            <img class="pull-left" src="<?=Url::to($value['avatar'], true)?>" alt="">
                                            <span class="name-user"><?=$key?></span>
                                        </a>
                                        <div class="crt-item">
                                            <a href="#" class="btn-email-item mgR-15 tooltip-show" data-placement="bottom" title="Send email" data-target="#popup_email" data-type="contact" data-toggle="modal" data-email="<?=$value['email']?>">
                                                <span class="icon-mv fs-16">
                                                    <span class="icon-mail-profile"></span>
                                                </span>
                                            </a>
                                            <a href="#" title="Send message" class="chat-now tooltip-show" data-chat-user="<?=$key?>" data-placement="bottom">
                                                <span class="icon-mv fs-19">
                                                    <span class="icon-bubbles-icon"></span>
                                                </span>
                                            </a>
                                        </div>
                                        <span class="num-show pull-right color-cd font-600"><?=$value['count']?></span>
                                    </div>
                                <?php }
                            } else {?>
                                <div class="clearfix fs-13 text-center">
                                    <span><?=Yii::t('statistic','Not found')?>.</span><a href="<?= Url::to(['/ad/update', 'id' => $id]) ?>"><?=Yii::t('statistic','Please, update your listing')?>.</a>
                                </div>
                            <?php }?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>



<div class="modal fade popup-common" id="frmListVisit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header title-popup clearfix">
                <!-- <div class="text-center popup_title"></div> -->
                <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
            </div>
            <div class="modal-body">
                <div class="wrap-modal clearfix">
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$id = empty($id) ? AdProduct::find()->select(['id'])->asArray()->one()['id'] : $id;
echo $this->renderAjax('/ad/_partials/shareEmail',[
    'popup_email_name' => 'popup_email_contact',
    'pid' => $id,
    'yourEmail' => $email,
    'recipientEmail' => null,
    'params' => ['your_email' => false, 'recipient_email' => false] ])?>
<script>
    $(document).ready(function () {

        $('.option-view-stats .radio-ui').checkbox_ui({
            done: function (item) {
                var index = item.parent().parent().index();
                var chart = $('#chartAds').highcharts(),
                    series = chart.series[(index-1)];
                if (series.visible) {
                    series.hide();
                } else {
                    series.show();
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