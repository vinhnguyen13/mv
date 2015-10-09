<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 10/9/2015 10:13 AM
 *
 * @var $news is a cms_show
 */
?>

<div class="container-fluid">

    <!--row01-->
    <div class="row">
        <input id="current_id" type="hidden" value="<?=$news->id?>">
        <input id="cat_id" type="hidden" value="<?=$news->catalog_id?>">
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 rowleft" id="postResult">
            <h2 class="titleditail"><?= $news->title ?></h2>
            <div class="contentdeitail">
                <?= $news->content ?>
            </div>
            <!--            <div id="loader" style="display:none;"><center><img src="preview.gif" /></center></div>-->
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 rgtcol">
            <?= \vsoft\news\widgets\NewsWidget::widget(['view' => 'quantam']) ?>
        </div>
    </div>


</div>

<script type="text/javascript">
    $(window).scroll(function () {
        var currentID = $('#current_id').val();
        var catID = $('#cat_id').val();
        if ($(window).scrollTop() == $(document).height() - $(window).height()) {
            $.ajax({
                url: '<?php echo Yii::$app->getUrlManager()->createUrl(["news/getone?current_id="]); ?>' + currentID + '&cat_id=' + catID,
                type: 'POST',
                success: function (data) {
                    if (data) {
                        $('#current_id').val(data.id);
                        $('#postResult').append('<h2 class="titleditail">' + data.title + '</h2>' +   '<div class="contentdeitail">' + data.content + '</div>');
                        console.log(data);
                    } else {
                        $('#loader').html('<center>No more posts to show.</center>');
                    }
                }
            });
        }
    });

</script>