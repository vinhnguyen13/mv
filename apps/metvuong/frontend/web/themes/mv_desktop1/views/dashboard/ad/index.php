<?php
use yii\web\View;
use yii\helpers\Url;
use yii\widgets\LinkPager;

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
                <li role="presentation" class="active"><a href="#list-all" aria-controls="list-all" role="tab" data-toggle="tab">Tất cả (<?=$total > 0 ? $total : 0?>)</a></li>
                <li role="presentation"><a data-url="<?=Url::to(['/dashboard/ad-list', 'type'=> 1], true)?>" href="#list-sell" aria-controls="list-sell" role="tab" data-toggle="tab">Bán (<?=$sell?>)</a></li>
                <li role="presentation"><a data-url="<?=Url::to(['/dashboard/ad-list', 'type'=> 2], true)?>" href="#list-rent" aria-controls="list-rent" role="tab" data-toggle="tab">Cho thuê (<?=$rent?>)</a></li>
                <li class="pull-right">
                    <div class="clearfix fs-13">
                        <div class="search-history">
                            <div>
                                <input type="text" id="" class="form-control" placeholder="Search listing...">
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

                </div>
                <div role="tabpanel" class="tab-pane fade" id="list-sell">
                    <ul class="clearfix list-item"></ul>
                </div>
                <div role="tabpanel" class="tab-pane fade" id="list-rent">
                    <ul class="clearfix list-item"></ul>
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
        </div>
        <?php } ?>
    </div>
</div>

<script>
	$(document).ready(function () {
        /*$('#nang-cap').popupMobi({
         btnClickShow: '.btn-nang-cap',
         styleShow: 'center',
         closeBtn: '#nang-cap .btn-cancel, #nang-cap .btn-ok',
         });*/

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            if ($(this).hasClass('loaded') || $(this).data('url') == undefined) return;
            var url = $(this).data('url');
            var tabName = $(this).attr('aria-controls');
            $('body').loading();
            $.ajax({
                type: "get",
                dataType: 'html',
                url: url,
                success: function (data) {
                    $('#' + tabName+ ' .list-item').html('');
                    $('#' + tabName+ ' .list-item').html(data);
                    $(this).addClass('loaded');
                    $('body').loading({done: true});
                }
            });
        });

    });
</script>