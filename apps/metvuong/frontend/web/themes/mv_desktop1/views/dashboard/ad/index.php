<?php
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$listSearch = json_encode(array_values($search));

?>
<div class="title-fixed-wrap">
	<div class="u-allduan">
        <?php if($total <= 0){?>
            <div class="no-duan">
                <div>
                    <p>Hiện tại, bạn không có tin đăng nào.</p>
                    <a href="<?= Url::to(['/ad/post']) ?>" class="btn-000">Đăng Dự Án</a>
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
                                <input type="text" id="tags" class="form-control" placeholder="Search listing...">
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
                    <div class="text-center">
                        <a href="#" data-url="<?=Url::to(['/dashboard/ad-list', 'type'=> 0])?>" class="load_listing btn-common"><?=Yii::t('listing','More listing')?>...</a>
                    </div>
                    <span class="that_all pull-right hide">That's all listing.</span>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="list-sell">
                    <ul class="clearfix list-item"></ul>
                    <input type="hidden" class="last_id" value="0">
                    <?php if($sell > 6){?>
                    <div class="text-center">
                        <a href="#" data-url="<?=Url::to(['/dashboard/ad-list', 'type'=> 1])?>" class="load_listing btn-common"><?=Yii::t('listing','More listing')?>...</a>
                    </div>
                    <span class="that_all pull-right hide">That's all listing.</span>
                    <?php } ?>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="list-rent">
                    <ul class="clearfix list-item"></ul>
                    <input type="hidden" class="last_id" value="0">
                    <?php if($rent > 6){?>
                    <div class="text-center">
                        <a href="#" data-url="<?=Url::to(['/dashboard/ad-list', 'type'=> 2])?>" class="load_listing btn-common"><?=Yii::t('listing','More listing')?>...</a>
                    </div>
                    <span class="that_all pull-right hide">That's all listing.</span>
                    <?php } ?>
                </div>
            </div>
            <div id="nang-cap" class="popup-common hide-popup">
                <div class="wrap-popup">
                    <div class="inner-popup">
                        <a href="#" class="btn-close btn-cancel"><span class="icon icon-close"></span></a>
                        <p class="alert-num-date">Tin đăng còn <span>0 ngày</span></p>
                        <p>Nâng cấp tin đăng thêm 30 ngày?  </p>
                        <div class="text-center">
                            <a href="#" class="btn-common btn-cancel">Từ chối</a>
                            <a href="#" class="btn-common btn-ok">Đồng ý</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="ui-widget">
                <input id="tags">
            </div> -->
        </div>
        <?php } ?>
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
        var list = <?=$listSearch?>;
        $( "#tags" ).autocomplete({
            source: list,
            open: function() { $('.ui-menu').width(300) }
        }).autocomplete( "instance" )._renderItem = function( ul, item ) {
            return $( "<li>" )
                .append( "<a href='"+item.url+"'>" + item.label + "</a>" )
                .appendTo( ul );
        };

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            if ($(this).hasClass('loaded') || $(this).data('url') == undefined) return;
            var url = $(this).data('url');
            var tabName = $(this).attr('aria-controls');
            var number = parseInt($(this).data('number'));
            var count = $('#'+tabName+' .list-item>li').length;
            if(count < number) {
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