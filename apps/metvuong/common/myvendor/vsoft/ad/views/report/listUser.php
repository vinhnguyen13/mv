<?php
use frontend\models\User;
use yii\helpers\Url;

Yii::t('chart','finders');
Yii::t('chart','visitors');
Yii::t('chart','saved');
Yii::t('chart','shares');

$totalUser = \vsoft\ad\models\AdProductReport::find()->where(['product_id' => $product_id])->count();
?>
<div class="clearfix mgB-15">
    <p class="desTotal"><strong><?=Yii::t('chart','Total user')?>: <?=$totalUser?></strong></p>
</div>
<ul class="clearfix listUser">
<?php
echo $this->render('listUser_item',['list_user' => $list_user]);
?>
</ul>
<div class="clearfix">
    <div class="col-md-12 text-center">
        <input type="button" class="btn btn-success _loadmore" value="<?=Yii::t('statistic', 'Load more')?>" data-url="<?=Url::to(['report/get-user-report-load-more', 'product_id' => $product_id])?>">
    </div>
</div>
<script>
    $(document).ready(function () {
        var countLi = $('.listUser li').length;
        var totalUser = <?=$totalUser?>;
        if(countLi == totalUser)
            $('._loadmore').remove();

        $('._loadmore').click(function () {
            var last_time = $('.listUser>li:last').prop('class');
            var url = $(this).data('url');
            $.ajax({
                type: "get",
                dataType: 'html',
                url: url + '&last_time=' + last_time,
                success: function (data) {
                    $('.listUser').append(data);
                    countLi = $('.listUser li').length;
                    if (countLi == totalUser) {
                        $('._loadmore').remove();
                        $('._backtop').removeClass('hide');
                    }
                }
            });
        });

    });
</script>