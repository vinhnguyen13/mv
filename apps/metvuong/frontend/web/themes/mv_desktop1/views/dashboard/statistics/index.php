<?php
use vsoft\ad\models\AdProduct;
use yii\helpers\Url;


$id = $product->id;
$address = $product->getAddress();
$urlDetail = $product->urlDetail(true);

$finderFrom = (!empty($from) && isset($from)) ? $from : 0;
$finderTo = (!empty($to) && isset($to)) ? $to : 0;

$data = [$finders, $visitors, $favorites, $shares];
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
                        <a href="<?= Url::to(['dashboard/statistics', 'id' => $product->id, 'filter'=>'week'], true) ?>" class="show-view-chart<?=($filter=='week' ? ' active' : '')?>"><?=Yii::t('statistic','Week')?></a>
                        <a href="<?= Url::to(['dashboard/statistics', 'id' => $product->id, 'filter'=>'2week'], true) ?>" class="show-view-chart<?=($filter=='2week' ? ' active' : '')?>"><?=Yii::t('statistic','Two weeks')?></a>
                        <a href="<?= Url::to(['dashboard/statistics', 'id' => $product->id, 'filter'=>'month'], true) ?>" class="show-view-chart<?=($filter=='month' ? ' active' : '')?>"><?=Yii::t('statistic','Month')?></a>
                    </div>
                </div>
                <div class="clearfix"></div>
        		<div class="summary clearfix">
                    <div class="wrap-chart clearfix">
        				<div class="wrap-img">
                            <div class="wrapChart">
                                <?=$this->render('/dashboard/chart/_partials/finder', ['id' => $id, 'from' => $finderFrom, 'to' => $finderTo, 'address' => $address, 'urlDetail' => $urlDetail, 'filter' => $filter, 'dataChart' => $data, 'categories' => $categories]);?>
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
                <?=Yii::t('statistic','Statistic')?>
                <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
            </div>
            <div class="modal-body">
                <div class="wrap-modal clearfix">
                </div>
            </div>
            <div class="bottom-popup">
                <p class="push-price fs-13 text-right font-600"><span class="d-ib lh-100"><?=Yii::t('chart', 'Để tin đạt hiệu quả hơn vui lòng click vào đây')?></span> <a href="#" data-toggle="modal" data-target="#update-boost" data-product="<?=$id?>" class="btn-nang-cap mgL-10 btn-boost"><?= Yii::t('statistic', 'Up') ?></a></p>
            </div>
        </div>
    </div>
</div>

<div id="charge" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="wrap-popup">
                    <div class="title-popup">
                        <?=Yii::t('listing', 'Thông báo')?>
                        <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
                    </div>
                    <div class="inner-popup">
                        <div><?= Yii::t('listing', 'Bạn không đủ keys để thực hiện thao tác này, vui lòng nạp thêm keys.') ?></div>
                    </div>
                    <div class="bottom-popup">
                        <div class="text-right">
                            <a href="#" class="btn-cancel btn close" data-dismiss="modal" aria-label="Close"><?=Yii::t('listing', 'Cancel')?></a>
                            <a href="<?= Url::to(['/payment/index', 'redirect' => Url::current()]) ?>" class="btn-common btn"><?=Yii::t('listing', 'Nạp keys')?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="update-boost" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-href="<?= Url::to(['/ad/boost']) ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="wrap-popup">
                    <div class="title-popup">
                        <?=Yii::t('listing', 'Boost listing')?>
                        <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
                    </div>
                    <div class="inner-popup">
                        <form id="boostListing">
                            <span class="charge hide"><?= AdProduct::CHARGE_BOOST_3 ?></span>
                            <style>.inner-popup {font-size: 14px;} .modal .hint {font-weight: 600;} .show-key {margin: 12px 0px 0px 12px;} .show-key li:first-child {margin-bottom: 6px;} .strong {font-size: 15px; font-weight: 700; color: #000;}</style>
                            <div class="hint" style="margin-bottom: 8px;"><?= Yii::t('listing', 'Choose how long you want your promotion to run')?></div>
                            <div class="clearfix mgB-15">
                                <ul class="days-up">
                                    <li>
                                        <label class="radio-inline radio-ui active">
                                            <input type="radio" name="upgrade-time" id="" value="7" checked="checked"> <?= sprintf(Yii::t("", "%s ngày (%s keys)"), '<span class="day">3</span>', '<span class="key">' . AdProduct::CHARGE_BOOST_3 . '</span>') ?>
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-inline radio-ui">
                                            <input type="radio" name="upgrade-time" id="" value="1"> <?= sprintf(Yii::t("", "%s ngày (%s keys)"), '<span class="day">1</span>', '<span class="key">' . AdProduct::CHARGE_BOOST_1 . '</span>') ?>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            <div class="date-select mgB-15" style="display: none;">
                                <?=Yii::t('listing', 'Run this ad until')?>
                                <div class="wrap-calen">
                                    <span class="icon-mv"><span class="icon-calendar"></span></span>
                                    <input type="text" class="date-picker" readonly='true' />
                                </div>
                            </div>
                            <ul class="show-key">
                                <li><?= sprintf(Yii::t("ad", "Số keys hiện tại: %s keys"), '<strong class="current-key strong"></strong>') ?></li>
                                <li><?= sprintf(Yii::t("ad", "Số keys sau khi boost: %s keys"), '<strong class="after-key strong"></strong>') ?></li>
                            </ul>
                        </form>
                    </div>
                    <div class="bottom-popup">
                        <div class="text-right">
                            <a href="#" class="btn-cancel btn close" data-dismiss="modal" aria-label="Close"><?=Yii::t('listing', 'Cancel')?></a>
                            <a href="<?= Url::to(['/ad/boost']) ?>" class="btn-common btn btn-boost-listing"><?=Yii::t('listing', 'Boost')?></a>
                        </div>
                    </div>
                </div>
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


        $('.popup-common').appendTo('body');

        function showKey(popup, e, self) {
            var currentKey = balance.get();
            var charge = Number(popup.find('.charge').text());

            if(currentKey < charge) {
                $('#charge').modal('toggle');

                e.preventDefault();
                e.stopPropagation();
            } else {
                popup.find('.current-key').text(currentKey);
                popup.find('.after-key').text(currentKey - charge);

                popup.find('.btn-common').data('id', self.data('product'));
            }
        }

        $(document).on('click', '.btn-active, .btn-expired, .btn-boost', function(e){
            var self = $(this);
            showKey($(self.data('target')), e, self);
        });

        $('.btn-active-listing, .btn-update-expired, .btn-boost-listing').click(function(e){
            e.preventDefault();

            $('body').loading();

            var self = $(this);
            var id = self.data('id');
            var get = {id: id};

            if(self.hasClass('btn-boost-listing')) {
                var closest = $('.days-up').find('input:checked').closest('.radio-ui');
                var day = Number(closest.find('.day').text());

                get.day = day;
            }

            $.get(self.attr('href'), get, function(r){
                $('body').loading({done: true});

                if(r.success) {
                    $('#p-' + id).replaceWith(r.template);

                    balance.update(r.amount);
                } else {
                    $('#notify-text').text(r.message);
                    $('#notify').modal().show();
                }

                $('#' + self.closest('.popup-common').attr('id')).modal('hide');
            });
        });

        $('#update-boost').on('hidden.bs.modal', function () {
            $(this).find('.radio-ui').eq(0).trigger('click');
        });

        $(document).trigger('boost/form_process', [1, true]);

        $('.days-up li .radio-ui').radio({
            done: function (item) {
                var charge = Number($(item).closest('.radio-ui').find('.key').text());
                var boostL = $('#boostListing');

                boostL.find('.charge').text(charge);
                boostL.find('.after-key').text(balance.get() - charge);
            }
        });

    });

</script>