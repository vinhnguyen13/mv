<?php
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\helpers\Url;
use vsoft\ad\models\AdProduct;

$this->registerCss(".inner-popup {font-size: 14px;} .modal .hint {font-weight: 600;} .show-key {margin: 12px 0px 0px 12px;} .show-key li:first-child {margin-bottom: 6px;} .strong {font-size: 15px; font-weight: 700; color: #000;}");
?>
<div class="title-fixed-wrap container">
	<div class="u-allduan">
        <div class="title-top">Dashboard</div>
        <?php if($total <= 0){?>
            <div class="no-duan">
                <div>
                    <p><?= Yii::t('ad', 'Hiện tại, bạn không có tin đăng nào.') ?></p>
                    <a href="<?= Url::to(['/ad/post']) ?>" class="btn-common"><?= Yii::t('ad', 'Đăng Dự Án') ?></a>
                </div>
            </div>
        <?php } else { ?>
        <div class="wrap-list-duan" id="list-all" data-number="<?=$total?>">
            <ul class="clearfix list-item">
                <?=$this->render('/dashboard/ad/list', ['products' => $products, 'type' => 0, 'last_id' => $last_id])?>
            </ul>
            <input type="hidden" class="last_id" value="<?=$last_id?>">
            <?php if($total > 6){?>
            <div class="text-center">
                <a style="text-decoration: none; cursor: pointer;" data-url="<?=Url::to(['/dashboard/ad-list', 'type'=> 0])?>" class="load_listing btn-common"><?=Yii::t('listing','More listing')?></a>
            </div>
            <span class="that_all pull-right hide fs-13"><?=Yii::t('ad', "That's all listing.")?></span>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
</div>
<div id="notify" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="wrap-popup">
                    <div class="title-popup">
                        <?=Yii::t('listing', 'Thông báo')?>
                        <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
                    </div>
                    <div class="inner-popup">
                        <div class="hint" id="notify-text"></div>
                    </div>
                </div>
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
<div id="update-status" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-href="<?= Url::to(['/ad/update-status']) ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="wrap-popup">
                    <div class="title-popup">
                        <?=Yii::t('listing', 'Kích hoạt tin đăng')?>
                        <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
                    </div>
                    <div class="inner-popup">
                    	<div class="hint"><?= sprintf(Yii::t("ad", "Phí đăng tin là %s keys. Sau khi đăng tin, tin sẽ có thời hạn là %s ngày."), '<strong class="charge strong">' . AdProduct::CHARGE_POST . '</strong>', '<strong class="strong">' . AdProduct::EXPIRED / 86400 . '</strong>') ?></div>
                        <ul class="show-key">
							<li><?= sprintf(Yii::t("ad", "Số keys hiện tại: %s keys"), '<strong class="current-key strong"></strong>') ?></li>
							<li><?= sprintf(Yii::t("ad", "Số keys sau khi đăng tin: %s keys"), '<strong class="after-key strong"></strong>') ?></li>
						</ul>
                    </div>
                    <div class="bottom-popup">
                        <div class="text-right">
                            <a href="#" class="btn-cancel btn close" data-dismiss="modal" aria-label="Close"><?=Yii::t('listing', 'Cancel')?></a>
                            <a href="<?= Url::to(['/ad/update-status']) ?>" class="btn-common btn btn-active-listing"><?=Yii::t('listing', 'Kích hoạt')?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="update-expired" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-href="<?= Url::to(['/dashboard/update-expired']) ?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="wrap-popup">
                    <div class="title-popup">
                        <?=Yii::t('listing', 'Gia hạn tin đăng')?>
                        <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
                    </div>
                    <div class="inner-popup">
                    	<div class="hint"><?= sprintf(Yii::t("ad", "Phí gia hạn tin đăng là %s keys. Sau khi gia hạn tin đăng sẽ có thời hạn là %s ngày."), '<strong class="charge strong">' . AdProduct::CHARGE_POST . '</strong>', '<strong class="strong">30</strong>') ?></div>
                        <ul class="show-key">
							<li><?= sprintf(Yii::t("ad", "Số keys hiện tại: %s keys"), '<strong class="current-key strong"></strong>') ?></li>
							<li><?= sprintf(Yii::t("ad", "Số keys sau khi gia hạn: %s keys"), '<strong class="after-key strong"></strong>') ?></li>
						</ul>
                    </div>                    
                    <div class="bottom-popup">
                        <div class="text-right">
                            <a href="#" class="btn-cancel btn close" data-dismiss="modal" aria-label="Close"><?=Yii::t('listing', 'Cancel')?></a>
                            <a href="<?= Url::to(['/dashboard/update-expired']) ?>" class="btn-common btn btn-update-expired"><?=Yii::t('listing', 'Gia hạn')?></a>
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
<script>
	$(document).ready(function () {

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
		
		$('.wrap-list-duan').on('click', '.btn-active, .btn-expired, .btn-boost', function(e){
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

        /*$(document).bind('boost/form_process', function (event, days, rebuildPicker) {
            var checkMoney = days + ' ' + ((days > 1) ? lajax.t('days') :  lajax.t('day'));
            $('.check-money').find('.day').html(checkMoney);
            var total_budget = $('.total_budget').val();
            $('.check-money').find('.money').html(Math.floor(total_budget/days));
            $('.check-money').find('.money').text(function () {
                var str = $(this).html() + '';
                x = str.split('.');
                x1 = x[0]; x2 = x.length > 1 ? '.' + x[1] : '';
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + ',' + '$2');
                }
                $(this).html(x1);
            });
            if(rebuildPicker == true){
                $(".date-picker").datepicker("setDate", '+'+days);
            }
            var _date = $.datepicker.formatDate( "M dd, yy", $(".date-picker").datepicker("getDate") );
            $('.check-money').find('.date').html(_date);
        });

        var dateToday = new Date();
        $(".date-picker").datepicker({
            minDate: dateToday,
            dayNamesMin: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
            monthNames: [ "Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4","Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9","Tháng 10", "Tháng 11", "Tháng 12" ],
            dateFormat: 'dd-mm-yy',
            onSelect: function(dateText) {
                var end = $(this).datepicker('getDate');
                var days = Math.floor((end - dateToday) / (1000 * 60 * 60 * 24)) + 1;
                var _date = $.datepicker.formatDate( "M dd, yy", end );
                var checkMoney = days + ' ' + ((days > 1) ? lajax.t('days') :  lajax.t('day'));
                $(document).trigger('boost/form_process', [days]);
                $('.days-up input').parent().removeClass('active');
                $('.days-up input[name="upgrade-time"][value="'+days+'"]').prop( "checked" );
                $('.days-up input[name="upgrade-time"][value="'+days+'"]').parent().addClass('active');
            }
        }).datepicker("setDate", '+1');

        $('.check-money').html(lajax.t('You will spend {money} per day. This ad will run for {day}, ending on {end}', {
                money: '₫<strong class="money">'+$('.total_budget').val()+'</strong>',
                day: '<strong class="day">1 '+lajax.t('day')+'</strong>',
                end: '<strong class="date">'+$.datepicker.formatDate( "M dd, yy", $(".date-picker").datepicker("getDate") )+'</strong>'
        }));*/

        $(document).trigger('boost/form_process', [1, true]);

        $('.days-up li .radio-ui').radio({
            done: function (item) {
                var charge = Number($(item).closest('.radio-ui').find('.key').text());
                var boostL = $('#boostListing');
                
                boostL.find('.charge').text(charge);
                boostL.find('.after-key').text(balance.get() - charge);
            }
        });

        $(document).on('click', '.btn-nang-cap', function (e) {
            var btn = $(this);
            var product = btn.attr('data-product');
            $('#upgrade-time').find('.btn-boost').attr('data-product', product);
        });

        $(document).on('click', '#upgrade-time .btn-boost', function (e) {
            var btn = $(this);
            var product = $(this).attr('data-product');
            if(product){
                btn.html(lajax.t('Loading'));
                $.ajax({
                    type: "get",
                    dataType: 'json',
                    url: '<?=Url::to(['dashboard/upgrade'])?>?id=' + product,
                    data: $('#boostListing').serialize(),
                    success: function (data) {
                        location.reload();
                    }
                });
            }
            return false;
        });

        $(document).on('click', '#upgrade-time .btn-ok', function (e) {
            var product = $(this).attr('data-product');
            if(product){
                $.ajax({
                    type: "get",
                    dataType: 'json',
                    url: '<?=Url::to(['dashboard/upgrade'])?>?id=' + product,
                    success: function (data) {
                        $('body').alertBox({
                            txt: lajax.t('Upgrade success'),
                            duration: 4000
                        });
//                        location.reload();
                        if(data.expired > 0) {
                            if($('.p' + product + ' .intro-detail p.expired').length > 0){
                                $('.p' + product + ' .intro-detail p.expired strong').text(data.expired + " <?=Yii::t('statistic', 'days')?>");
                            } else {
                                $('.p' + product + ' .intro-detail .status-duan .status-get-point span').removeClass('icon-inactive-pro').addClass('icon-active-pro');
                                $('.p' + product + ' .intro-detail .status-duan .status-get-point strong').text('<?= Yii::t('statistic', 'Active Project') ?>');
                                $('.p' + product + ' .intro-detail .status-duan').after(function () {
                                    return "<p class=\"expired\"><?= Yii::t('statistic', 'Expired in the last') ?><strong> " + data.expired + " <?=Yii::t('statistic', 'days')?></strong></p>";
                                });
                            }
                        }
                    }
                });
            }
        });

        $("#tags").click(function() {
            var list = <?=json_encode(array_values($search))?>;
            $("#tags").autocomplete({
                source: list,
                open: function () {
                    $('.ui-menu').width(300)
                },
                autoFocus:true
            }).autocomplete("instance")._renderItem = function (ul, item) {
                return $("<li>")
                    .append("<a href='" + item.url + "'>" + item.label + "</a>")
                    .appendTo(ul);
            };
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            if ($(this).hasClass('loaded') || $(this).data('url') == undefined) return;
            var url = $(this).data('url');
            var tabName = $(this).attr('aria-controls');
            var number = parseInt($(this).data('number'));
            var count = $('#'+tabName+' .list-item>li').length;
            if(count < 1) {
                $('body').loading();
                $.ajax({
                    type: "get",
                    dataType: 'html',
                    url: url,
                    success: function (data) {
                        $('#' + tabName + ' .list-item').html('');
                        $('#' + tabName + ' .list-item').html(data);
                        var last_id = $('#' + tabName + ' .list-item>li:last .id-duan span').text();
                        last_id = last_id.replace("MV", "");
                        $('#' + tabName + ' .last_id').val(last_id);
                        $(this).addClass('loaded');
                        $('body').loading({done: true});
                        return true;
                    }
                });
            }
            return false;
        });

        $('#list-all .load_listing').click(function(){
            $('body').loading();
            var last_id = $('#list-all .last_id').val();
            var url = $(this).data('url')+"&last_id="+last_id;
            $.ajax({
                type: "get",
                dataType: 'html',
                url: url,
                success: function (data) {
                    $('#list-all .list-item').append(data);
                    last_id = $('#list-all .list-item>li:last .id-duan span').text();
                    last_id = last_id.replace("MV", "");
                    $('#list-all .last_id').val(last_id);

                    var number = parseInt($('#list-all').data('number'));
                    var count = $('#list-all .list-item>li').length;
                    if(count >= number) {
                        $('#list-all .that_all').removeClass('hide');
                        $('#list-all .load_listing').remove();
                    }
                    $('body').loading({done: true});
                }
            });
        });

    });
</script>