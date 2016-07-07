<?php
use frontend\models\User;
use yii\helpers\Url;

Yii::t('chart','finders');
Yii::t('chart','visitors');
Yii::t('chart','saved');
Yii::t('chart','shares');
?>
<div class="clearfix mgB-15 pdL-15 pdR-15">
    <h3><?=Yii::t('chart', $view) ?> : <?=$dateParam?></h3>
    <p style="color: #4a933a;" class="desTotal"> <?=Yii::t('chart','Total user')?>: <?=$totalUser?></p>
</div>
<ul class="clearfix listContact">
<?php
echo $this->render('listContact_item',['view' => $view, 'data' => $data]);
?>
</ul>
<input type="button" class="btn-common pull-right _loadmore" value="<?=Yii::t('statistic', 'Load more')?>" data-url="<?=Url::to(['dashboard/clickchart-load-more', 'view' => $view, 'from' => $from, 'to' => $to, 'pid' => $pid], true)?>">
<script>
    $(document).ready(function () {
        var countLi = $('.listContact li').length;
        var totalUser = <?=$totalUser?>;
        if(countLi == totalUser)
            $('._loadmore').remove();

        $('._loadmore').click(function () {
            $('body').loading();
            var last_id = $('.listContact>li:last').prop('class');
            l(last_id);
            var url = $(this).data('url');
            $.ajax({
                type: "get",
                dataType: 'html',
                url: url + '&last_id=' + last_id,
                success: function (data) {
                    $('.listContact').append(data);
                    countLi = $('.listContact li').length;
                    if (countLi == totalUser)
                        $('._loadmore').remove();
                    $('body').loading({done: true});
                }
            });
        });

    });
</script>