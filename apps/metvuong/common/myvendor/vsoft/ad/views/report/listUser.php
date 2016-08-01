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
<table class="clearfix listUser">
    <thead>
        <tr>
            <td class="col-md-4"><?=Yii::t('report', 'Username')?></td>
            <td class="col-md-7"><?=Yii::t('report', 'Content')?></td>
            <td class="col-md-4"><?=Yii::t('report', 'Date')?></td>
        </tr>
    </thead>
<?php
echo $this->render('listUser_item',['list_user' => $list_user]);
?>
</table>
<div class="clearfix">
    <div class="col-md-12 text-center">
        <input type="button" class="btn btn-success _loadmore" value="<?=Yii::t('statistic', 'Load more')?>" data-url="<?=Url::to(['report/get-user-report-load-more', 'product_id' => $product_id])?>">
        <input type="button" class="btn btn-info pull-right _backtop hide" value="top">
    </div>
</div>
<script>
    $(document).ready(function () {
        var countLi = $('.listUser tr').length;
        var totalUser = <?=$totalUser?>;
        if(countLi >= totalUser)
            $('._loadmore').remove();

        $('._backtop').click(function(){
            $('#popup-user-report').animate({scrollTop: 0}, 500);
        });

        $('._loadmore').click(function () {
            var last_time = $('.listUser tr:last').attr('class');
            var url = $(this).data('url');
            $.ajax({
                type: "get",
                dataType: 'html',
                url: url + '&last_time=' + last_time,
                success: function (data) {
                    $('.listUser').append(data);
                    $('._backtop').removeClass('hide');
                    countLi = $('.listUser tr').length;
                    if (countLi >= totalUser) {
                        $('._loadmore').remove();
                    }
                }
            });
        });

    });
</script>