<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 1/28/2016 10:15 AM
 */
use yii\helpers\Url;
use yii\widgets\LinkPager;

?>
<div class="title-fixed-wrap container">
    <div class="infor-duan">
        <div class="clearfix">
            <div class="title-top"><?=Yii::t('general', 'New Project')?></div>
            <div class="search-duan">
                <form id="search-duan-form" action="<?= \yii\helpers\Url::to(['building-project/search'], true) ?>">
                    <input autocomplete="off" id="findProject" name="v" class="project_name" type="text" placeholder="<?=Yii::t('general', 'Find by name...')?>">
                    <button type="submit" id="btn-search-duan"><span class="icon-mv"><span class="icon-icons-search"></span></span></button>
                    <div class="suggest-search hide">
                        <div class="content-suggest">
                            <a class="btn-close"><span class="icon icon-close"></span></a>
                            <ul></ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="wrap-infor-duan">
            <div class="list-duan clearfix row">
                <div class="col-xs-12 col-md-9 col-left">
                    <ul class="clearfix">
                        <?php if(count($models) > 0) {
                            foreach ($models as $key => $model) {
                                $tongquan = $model->description;
                                $tabProject = null;
                                if(!empty($model->data_html)){
                                    $tabProject = json_decode($model->data_html, true);
                                    if (count($tabProject) > 0) {
                                        $key_index = key($tabProject);
                                        $tongquan = html_entity_decode($tabProject[$key_index], ENT_HTML5, 'utf-8');
                                    }
                                }
                                $tongquan = trim(strip_tags($tongquan));

//                                $src_img = \vsoft\ad\models\AdImages::defaultImage();
//                                $checkThumbFile = file_exists(Yii::getAlias('@store'). "/building-project-images/thumb_" . $model->logo);
//                                if($checkThumbFile) {
//                                    $src_img = Url::to('/store/building-project-images/thumb_' . $model->logo);
//                                }
//                                else {
//                                    $logo = Yii::getAlias('@store'). "/building-project-images/" . $model->logo;
//                                    $checkFile = file_exists($logo);
//                                    if ($checkFile) {
//                                        $src_img = Url::to('/store/building-project-images/' . $model->logo);
//                                    }
//                                }
                                ?>
                                <li>
                                    <div class="wrap-item">
                                        <a href="<?= Url::to(["building-project/view", 'slug'=>$model->slug]); ?>" class="pic-intro rippler rippler-default">
                                            <img src="<?=$model->logoUrl ?>" alt="<?= $model->name ?>">
                                        </a>

                                        <div class="info-item">
                                            <div class="address-feat">
                                                <!-- <p><?= $model->investment_type ?></p> -->
                                                <a class="color-cd" href="<?= Url::to(["building-project/view", 'slug'=>$model->slug]); ?>" title="<?= $model->name ?>">
                                                    <strong><?= $model->name ?></strong>
                                                </a>
                                                <p class="address-duan"><?= empty($model->location) ? Yii::t('general', 'Updating') : $model->location ?></p>
                                                <p class="date-post"><?=date('d/m/Y', $model->created_at)?></p>
                                            </div>
                                            <div class="bottom-feat-box clearfix">
                                                <input type="hidden" value="<?=$model->id?>">
                                                <p><?=\yii\helpers\StringHelper::truncate($tongquan, 500)?></p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                            <nav class="text-center">
                                <?php
                                echo LinkPager::widget([
                                    'pagination' => $pagination
                                ]);
                                ?>
                            </nav>
                        <?php } else {?>
                            <div>Không tìm thấy dự án "<b class="found-name"><?=$project_name?></b>"</div>
                        <?php } ?>
                    </ul>
                </div>
                <div class="col-xs-12 col-md-3 col-right sidebar-col">

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .content-suggest ul li:hover{
        background-color: antiquewhite;
    }
    .content-suggest ul li a{
        text-decoration: none;
    }
</style>

<script>
    $(document).ready(function () {
        $('.sidebar-col').loading({full: false});
        $.ajax({
            type: "get",
            dataType: 'html',
            url: '<?=Url::to(['building-project/load-sidebar'])?>',
            success: function (data) {
                $(".sidebar-col").html(data);
                $('.sidebar-col').loading({done: true});
            }
        });

        var searchForm = $('#search-duan-form');
        var result  = 0;
        $('#findProject').keyup(function () {
            var self = $(this);
            var val = self.val().trim();
            var url = searchForm.attr('action');
            var ss = $('#search-duan-form .suggest-search');

            if (val.length >= 2) {
                $.get(url, searchForm.serialize(), function (response) {
                    console.log(response);
                    result = response.length;
                    if (result > 0) {
                        ss.removeClass('hide');
                        var html = '';
                        for (var i in response) {
                            html += '<li><a href="'+response[i]['link']+'">' + response[i]['full_name'] + '</a></li>';
                        }
                        $('#search-duan-form .content-suggest ul').html(html);

                    } else {
                        ss.addClass('hide');
                    }
                });
            } else {
                $('#v').val('');
                ss.addClass('hide');
            }
        });

        $('#search-duan-form .btn-close').click(function(){
            $('#search-duan-form .suggest-search').addClass('hide');
        });

        $('#findProject').mouseenter(function(){
            if(result > 0)
                $('#search-duan-form .suggest-search').removeClass('hide');
        });
    });
</script>