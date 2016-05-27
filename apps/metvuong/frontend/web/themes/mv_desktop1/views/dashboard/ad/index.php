<?php
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\helpers\Url;
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
        <div class="wrap-list-duan">
            <ul class="nav nav-tabs clearfix" role="tablist">
                <li role="presentation" class="active">
                    <a class="link-list-all" href="#list-all" aria-controls="list-all" role="tab" data-toggle="tab" data-number="<?=$total?>"><?=Yii::t('ad', 'Tất cả')?> (<?=$total > 0 ? $total : 0?>)</a>
                </li>
                <li role="presentation"><a class="link-list-sell" data-url="<?=Url::to(['/dashboard/ad-list', 'type'=> 1], true)?>" href="#list-sell" aria-controls="list-sell" role="tab" data-toggle="tab" data-number="<?=$sell?>"><?=Yii::t('ad', 'Bán')?> (<?=$sell?>)</a></li>
                <li role="presentation"><a class="link-list-rent" data-url="<?=Url::to(['/dashboard/ad-list', 'type'=> 2], true)?>" href="#list-rent" aria-controls="list-rent" role="tab" data-toggle="tab" data-number="<?=$rent?>"><?=Yii::t('ad', 'Cho thuê')?> (<?=$rent?>)</a></li>
                <li class="pull-right">
                    <div class="clearfix fs-13">
                        <div class="search-history">
                            <div>
                                <input type="text" id="tags" class="form-control" placeholder="<?= Yii::t('listing', 'Search listing...') ?>">
                                <button class="btn-search-hist" href="#"><span class="icon-mv"><span class="icon-icons-search"></span></span></button>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="list-all">
                    <ul class="clearfix list-item">
                        <?=$this->render('/dashboard/ad/list', ['products' => $products, 'type' => 0, 'last_id' => $last_id])?>
                    </ul>
                    <input type="hidden" class="last_id" value="<?=$last_id?>">
                    <?php if($total > 6){?>
                    <div class="text-center">
                        <a href="#" data-url="<?=Url::to(['/dashboard/ad-list', 'type'=> 0])?>" class="load_listing btn-common"><?=Yii::t('listing','More listing')?>...</a>
                    </div>
                    <span class="that_all pull-right hide fs-13"><?=Yii::t('ad', "That's all listing.")?></span>
                    <?php } ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="list-sell">
                    <ul class="clearfix list-item"></ul>
                    <input type="hidden" class="last_id" value="0">
                    <?php if($sell > 6){?>
                    <div class="text-center">
                        <a href="#" data-url="<?=Url::to(['/dashboard/ad-list', 'type'=> 1])?>" class="load_listing btn-common"><?=Yii::t('listing','More listing')?>...</a>
                    </div>
                    <span class="that_all pull-right hide fs-13"><?=Yii::t('ad', "That's all listing.")?></span>
                    <?php } ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="list-rent">
                    <ul class="clearfix list-item"></ul>
                    <input type="hidden" class="last_id" value="0">
                    <?php if($rent > 6){?>
                    <div class="text-center">
                        <a href="#" data-url="<?=Url::to(['/dashboard/ad-list', 'type'=> 2])?>" class="load_listing btn-common"><?=Yii::t('listing','More listing')?>...</a>
                    </div>
                    <span class="that_all pull-right hide fs-13"><?=Yii::t('ad', "That's all listing.")?></span>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<div id="upgrade-time" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                            <p class="font-600 mgB-10"><?=Yii::t('listing', 'Total budget')?> <span class="icon-mv mgL-5 tooltip-show" data-placement="right" title="<?=Yii::t('listing', 'The maximum you`ll spend on your promotion until you stop your ad')?>"><span class="icon-info-circle"></span></span></p>
                            <select name="total_budget" class="total_budget">
                                <option value="23000">₫23,000</option>
                                <option value="226000">₫226,000</option>
                            </select>
                            <p class="font-600 mgB-10"><?=Yii::t('listing', 'Duration')?> <span class="icon-mv mgL-5 tooltip-show" data-placement="right" title="<?=Yii::t('listing', 'Choose how long you want your promotion to run')?>"><span class="icon-info-circle"></span></span></p>
                            <div class="clearfix mgB-15">
                                <ul class="days-up">
                                    <li>
                                        <label class="radio-inline radio-ui active">
                                            <input type="radio" name="upgrade-time" id="" value="1" checked="checked"> 1 ngày
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-inline radio-ui">
                                            <input type="radio" name="upgrade-time" id="" value="7"> 7 ngày
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-inline radio-ui">
                                            <input type="radio" name="upgrade-time" id="" value="14"> 14 ngày
                                        </label>
                                    </li>
                                    <li>
                                        <label class="radio-inline radio-ui">
                                            <input type="radio" name="upgrade-time" id="" value="30"> 30 ngày
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            <div class="date-select mgB-15">
                                <?=Yii::t('listing', 'Run this ad until')?>
                                <div class="wrap-calen">
                                    <span class="icon-mv"><span class="icon-calendar"></span></span>
                                    <input type="text" class="date-picker" readonly='true' />
                                </div>
                            </div>
                            <div class="check-money">
                            </div>
                        </form>
                    </div>
                    <div class="bottom-popup">
                        <div class="text-right">
                            <a href="#" class="btn-cancel btn close" data-dismiss="modal" aria-label="Close"><?=Yii::t('listing', 'Cancel')?></a>
                            <a href="#" class="btn-common btn btn-boost"><?=Yii::t('listing', 'Boost')?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
    $this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/custom-datepicker.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'jquery-ui');
    Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/jquery-ui.js', ['position'=>View::POS_END]);
?>    
<script>
	$(document).ready(function () {

        $(document).bind('boost/form_process', function (event, days) {
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
            $(".date-picker").datepicker("setDate", '+'+days);
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
        }));
        $(document).trigger('boost/form_process', [1]);

        $('.days-up li .radio-ui').radio({
            done: function (item) {
                $(document).trigger('boost/form_process', [$(item).val()]);
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
                    var number = parseInt($('.link-list-all').data('number'));
                    var count = $('#list-all .list-item>li').length;
                    if(count >= number) {
                        $('#list-all .that_all').removeClass('hide');
                        $('#list-all .load_listing').remove();
                    }
                    $('body').loading({done: true});
                }
            });
        });
        $('#list-sell .load_listing').click(function(){
            var number = parseInt($('.link-list-sell').data('number'));
            var count = $('#list-sell .list-item>li').length;
            if(count < number) {
                $('body').loading();
                var last_id = $('#list-sell .last_id').val();
                var url = $(this).data('url')+"&last_id="+last_id;
                $.ajax({
                    type: "get",
                    dataType: 'html',
                    url: url,
                    success: function (data) {
                        $('#list-sell .list-item').append(data);
                        last_id = $('#list-sell .list-item>li:last .id-duan span').text();
                        last_id = last_id.replace("MV", "");
                        $('#list-sell .last_id').val(last_id);
                        var count = $('#list-sell .list-item>li').length;
                        if(count >= number) {
                            $('#list-sell .that_all').removeClass('hide');
                            $('#list-sell .load_listing').remove();
                        }
                        $('body').loading({done: true});
                        return true;
                    }
                });
            }
            return false;
        });
        $('#list-rent .load_listing').click(function(){
            var number = parseInt($('.link-list-rent').data('number'));
            var count = $('#list-rent .list-item>li').length;
            if(count < number) {
                $('body').loading();
                var last_id = $('#list-rent .last_id').val();
                var url = $(this).data('url')+"&last_id="+last_id;
                $.ajax({
                    type: "get",
                    dataType: 'html',
                    url: $(this).data('url'),
                    success: function (data) {
                        $('#list-rent .list-item').append(data);
                        last_id = $('#list-rent .list-item>li:last .id-duan span').text();
                        last_id = last_id.replace("MV", "");
                        $('#list-rent .last_id').val(last_id);
                        var count = $('#list-rent .list-item>li').length;
                        if(count >= number) {
                            $('#list-rent .that_all').removeClass('hide');
                            $('#list-rent .load_listing').remove();
                        }
                        $('body').loading({done: true});
                        return true;
                    }
                });
            }
            return false;
        });


    });
</script>