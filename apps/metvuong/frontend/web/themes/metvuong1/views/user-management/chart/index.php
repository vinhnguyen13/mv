<div class="col-xs-9 right-profile managechart">
    <div class="wrap-quanly-profile">
        <div class="title-frm">Quản lý thống kê</div>
        <ul class="nav nav-tabs">
            <li class="active"><a class="tab" href="javascript:void(0);" data-url="<?=\yii\helpers\Url::to(['/user-management/chart', 'view'=>'_partials/visitor'])?>">Người theo dõi</a></li>
            <li><a class="tab" href="javascript:void(0);" data-url="<?=\yii\helpers\Url::to(['/user-management/chart', 'view'=>'_partials/finder'])?>">Người tìm kiếm</a></li>
        </ul>
        <div class="wrapChart">
            <?php if(!Yii::$app->request->isAjax){?>
            <?=$this->render('/user-management/chart/_partials/visitor');?>
            <?php }?>
        </div>
    </div>
</div>

<div class="modal fade" id="frmListVisit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="wrap-modal clearfix">
                    <h3>Người theo dõi</h3>
                    <p style="color: #4a933a;">
                        Có <span class="total">5</span> người tìm nhà xung quanh khu vực Phường 3, Quận 1, TpHCM.
                    </p>

                    <?php
                    $yourArray = [
                        0 => [
                            'title' => 'Nguyễn Quang Vinh',
                            'phone' => '0909030605',
                            'time' => date('H:i:s d-m-Y', strtotime('-1days')),
                        ],
                        1 => [
                            'title' => 'Nguyễn Trung Ngạn',
                            'phone' => '0909030605',
                            'time' => date('H:i:s d-m-Y', strtotime('-2days')),
                        ],
                        2 => [
                            'title' => 'Quách Tuấn Lệnh',
                            'phone' => '0909030605',
                            'time' => date('H:i:s d-m-Y', strtotime('-3days')),
                        ],
                        3 => [
                            'title' => 'Quách Tuấn Du',
                            'phone' => '0909030605',
                            'time' => date('H:i:s d-m-Y', strtotime('-5days')),
                        ],

                    ];
                    $provider = new \yii\data\ArrayDataProvider([
                        'allModels' => $yourArray,
                        'sort' => [
                            'attributes' => ['title','net_accumulative_cashflow'],
                        ],
                        'pagination' => [
                            'pageSize' => 15,
                        ],
                    ]);
                    echo \yii\grid\GridView::widget([
                        'dataProvider' => $provider,
                        'summary'=>"",
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'title',
                            'phone',
                            'time',
                        ],
                    ]);?>
                    <p>Và 500 người nữa đang theo dõi tin của bạn. Bạn vui lòng <a href="javascript:alert('Coming soon !');">nạp thêm tiền</a> để có thể xem thêm</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        <?php if(Yii::$app->request->isAjax){?>
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
        });
    });
</script>
