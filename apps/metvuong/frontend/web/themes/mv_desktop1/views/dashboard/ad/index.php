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
                    <p>Hiện tại, bạn không có tin đăng nào.</p>
                    <a href="<?= Url::to(['/ad/post']) ?>" class="btn-common">Đăng Dự Án</a>
                </div>
            </div>
        <?php } else { ?>
        <div class="wrap-list-duan">
            <ul class="nav nav-tabs clearfix" role="tablist">
                <li role="presentation" class="active">
                    <a class="link-list-all" href="#list-all" aria-controls="list-all" role="tab" data-toggle="tab" data-number="<?=$total?>">Tất cả (<?=$total > 0 ? $total : 0?>)</a>
                </li>
                <li role="presentation"><a class="link-list-sell" data-url="<?=Url::to(['/dashboard/ad-list', 'type'=> 1], true)?>" href="#list-sell" aria-controls="list-sell" role="tab" data-toggle="tab" data-number="<?=$sell?>">Bán (<?=$sell?>)</a></li>
                <li role="presentation"><a class="link-list-rent" data-url="<?=Url::to(['/dashboard/ad-list', 'type'=> 2], true)?>" href="#list-rent" aria-controls="list-rent" role="tab" data-toggle="tab" data-number="<?=$rent?>">Cho thuê (<?=$rent?>)</a></li>
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
                    <span class="that_all pull-right hide fs-13">That's all listing.</span>
                    <?php } ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="list-sell">
                    <ul class="clearfix list-item"></ul>
                    <input type="hidden" class="last_id" value="0">
                    <?php if($sell > 6){?>
                    <div class="text-center">
                        <a href="#" data-url="<?=Url::to(['/dashboard/ad-list', 'type'=> 1])?>" class="load_listing btn-common"><?=Yii::t('listing','More listing')?>...</a>
                    </div>
                    <span class="that_all pull-right hide fs-13">That's all listing.</span>
                    <?php } ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="list-rent">
                    <ul class="clearfix list-item"></ul>
                    <input type="hidden" class="last_id" value="0">
                    <?php if($rent > 6){?>
                    <div class="text-center">
                        <a href="#" data-url="<?=Url::to(['/dashboard/ad-list', 'type'=> 2])?>" class="load_listing btn-common"><?=Yii::t('listing','More listing')?>...</a>
                    </div>
                    <span class="that_all pull-right hide fs-13">That's all listing.</span>
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
                    <div class="inner-popup">
                        <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
                        <div class="text-center fs-15">
                            <p class="mgB-5">Tin đăng của bạn còn <span class="font-600 color-cd">0 ngày</span></p>
                            <p class="mgB-25">Nâng cấp tin đăng thêm <span class="font-600 color-cd">30 ngày </span>?</p>
                            <a href="#" class="btn-common btn-cancel" data-dismiss="modal" aria-label="Close">Từ chối</a>
                            <a href="#" class="btn-common btn-ok" data-dismiss="modal" aria-label="Close">Đồng ý</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
	$(document).ready(function () {
        /*$('#nang-cap').popupMobi({
         btnClickShow: '.btn-nang-cap',
         styleShow: 'center',
         closeBtn: '#nang-cap .btn-cancel, #nang-cap .btn-ok',
         });*/
        $(document).on('click', '.btn-nang-cap', function (e) {
            var product = $(this).attr('data-product');
            $('#upgrade-time').find('.btn-ok').attr('data-product', product);
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