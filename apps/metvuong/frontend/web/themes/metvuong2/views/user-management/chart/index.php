<div class="right-profile managechart">
    <div class="wrap-quanly-profile">
        <div class="title-frm">Quản lý thống kê</div>
        <ul class="nav nav-tabs">
            <li class="active"><a class="tab" href="javascript:void(0);" data-url="<?=\yii\helpers\Url::to(['/user-management/chart', 'view'=>'_partials/visitor'])?>">Người theo dõi</a></li>
            <li><a class="tab" href="javascript:void(0);" data-url="<?=\yii\helpers\Url::to(['/user-management/chart', 'view'=>'_partials/finder'])?>">Người tìm kiếm</a></li>
            <li><a class="tab" href="javascript:void(0);" data-url="<?=\yii\helpers\Url::to(['/user-management/chart', 'view'=>'_partials/saved'])?>">Người lưu</a></li>
        </ul>
        <div class="wrapChart">
            <?php if(Yii::$app->request->isAjax){?>
            <?=$this->render('/user-management/chart/_partials/visitor');?>
            <?php }?>
        </div>
    </div>
</div>

<div class="modal fade" id="frmListVisit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="icon"></span>
                </button>
                <h3>Thống kê</h3>
            </div>
            <div class="modal-body">
                <div class="wrap-modal clearfix">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        <?php if(!Yii::$app->request->isAjax){?>
        var timer = 0;
        clearTimeout(timer);
        timer = setTimeout(function () {
            var url = $('.nav-tabs .tab:first').attr('data-url');
            $.ajax({
                type: "get",
                dataType: 'html',
                url: url,
                success: function (data) {
                    $('.wrapChart').html(data);
                }
            });
        }, 500);
        <?php }?>
        $(document).on('click', '.tab', function () {
            var timer = 0;
            clearTimeout(timer);
            $('.tab').parent().removeClass('active');
            $(this).parent().addClass('active');
            var url = $(this).attr('data-url');
            timer = setTimeout(function () {
                $.ajax({
                    type: "get",
                    dataType: 'html',
                    url: url,
                    success: function (data) {
                        $('.wrapChart').html(data);
                    }
                });
            }, 500);
            return false;
        });
    });
</script>
