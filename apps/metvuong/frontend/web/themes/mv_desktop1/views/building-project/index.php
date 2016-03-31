<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 1/28/2016 10:15 AM
 */
use yii\helpers\Url;
use yii\widgets\LinkPager;

?>
<div class="title-fixed-wrap">
    <div class="container">
        <div class="infor-duan">
            <div class="clearfix">
                <div class="title-top"><?=Yii::t('general', 'New Project')?></div>
                <div class="search-duan">
                    <form id="search-duan-form" action="<?= \yii\helpers\Url::to(['building-project/search'], true) ?>">
                        <input autocomplete="off" id="findProject" name="v" class="project_name" type="text" placeholder="<?=Yii::t('general', 'Find by name...')?>">
                        <button type="submit" id="btn-search-duan"><span class="icon icon-search-small-1"></span></button>
                        <div class="suggest-search hide">
                            <div class="content-suggest">
                                <ul></ul>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="wrap-infor-duan">
                <div class="list-duan clearfix row">
                    <div class="col-xs-12 col-md-8 col-left">
                        <ul class="clearfix">
                            <?php if(count($models) > 0) {
                                foreach ($models as $model) { ?>
                                    <li>
                                        <div class="wrap-item">
                                            <a href="<?= Url::to(["building/$model->slug"]); ?>" class="pic-intro rippler rippler-default">
                                                <div class="img-show">
                                                    <div><img src="<?=$model->logoUrl?>"
                                                              data-original="<?=$model->logoUrl?>"
                                                              style="display: block;"></div>
                                                </div>
                                            </a>

                                            <div class="info-item">
                                                <div class="address-feat">
                                                    <p class="infor-by-up">Căn hộ chung cư Bán</p>
                                                    <p><?= $model->investment_type ?></p>
                                                    <a class="color-cd" href="<?= Url::to(["building/$model->slug"]); ?>" title="<?= $model->name ?>">
                                                        <strong><?= $model->name ?></strong>
                                                    </a>
                                                    <p class="address-duan"><?= empty($model->location) ? Yii::t('general', 'Updating') : $model->location ?></p>
                                                    <p class="date-post"><?=date('d/m/Y, H:i', $model->created_at)?></p>
                                                </div>
                                                <div class="bottom-feat-box clearfix">
                                                    <input type="hidden" value="<?=$model->id?>">
                                                    <p><?=\yii\helpers\StringHelper::truncate($model->description, 180)?></p>
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
                    <div class="col-xs-12 col-md-4 col-right sidebar-col">
                        <div class="item-sidebar">
                            <div class="title-text">DỰ ÁN NỔI BẬT</div>
                            <ul class="clearfix list-post">
                                <li>
                                    <div class="wrap-item-post">
                                        <a href="#" class="rippler rippler-default">
                                            <div class="img-show"><div><img src="http://file4.batdongsan.com.vn/resize/350x280/2016/01/21/20160121171906-9f37.jpg"></div></div>
                                        </a>
                                        <p class="infor-by-up">Căn hộ chung cư Bán</p>
                                        <p class="name-post"><a href="#">LOREM IPSUM DOLORIT </a></p>
                                        <p class="fs-15 font-400">21 Nguyễn Trung Ngạn, P. Bến Nghé, Q1</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="wrap-item-post">
                                        <a href="#" class="rippler rippler-default">
                                            <div class="img-show"><div><img src="http://file4.batdongsan.com.vn/resize/350x280/2016/01/21/20160121171906-9f37.jpg"></div></div>
                                        </a>
                                        <p class="infor-by-up">Căn hộ chung cư Bán</p>
                                        <p class="name-post"><a href="#">LOREM IPSUM DOLORIT </a></p>
                                        <p class="fs-15 font-400">21 Nguyễn Trung Ngạn, P. Bến Nghé, Q1</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="wrap-item-post">
                                        <a href="#" class="rippler rippler-default">
                                            <div class="img-show"><div><img src="http://file4.batdongsan.com.vn/resize/350x280/2016/01/21/20160121171906-9f37.jpg"></div></div>
                                        </a>
                                        <p class="infor-by-up">Căn hộ chung cư Bán</p>
                                        <p class="name-post"><a href="#">LOREM IPSUM DOLORIT </a></p>
                                        <p class="fs-15 font-400">21 Nguyễn Trung Ngạn, P. Bến Nghé, Q1</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="item-sidebar">
                            <div class="title-text">DỰ ÁN NHIỀU NGƯỜI XEM</div>
                            <ul class="clearfix list-post">
                                <li>
                                    <div class="wrap-item-post">
                                        <a href="#" class="rippler rippler-default">
                                            <div class="img-show"><div><img src="http://file4.batdongsan.com.vn/resize/350x280/2016/01/21/20160121171906-9f37.jpg"></div></div>
                                        </a>
                                        <p class="infor-by-up">Căn hộ chung cư Bán</p>
                                        <p class="name-post"><a href="#">LOREM IPSUM DOLORIT </a></p>
                                        <p class="fs-15 font-400">21 Nguyễn Trung Ngạn, P. Bến Nghé, Q1</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="wrap-item-post">
                                        <a href="#" class="rippler rippler-default">
                                            <div class="img-show"><div><img src="http://file4.batdongsan.com.vn/resize/350x280/2016/01/21/20160121171906-9f37.jpg"></div></div>
                                        </a>
                                        <p class="infor-by-up">Căn hộ chung cư Bán</p>
                                        <p class="name-post"><a href="#">LOREM IPSUM DOLORIT </a></p>
                                        <p class="fs-15 font-400">21 Nguyễn Trung Ngạn, P. Bến Nghé, Q1</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="wrap-item-post">
                                        <a href="#" class="rippler rippler-default">
                                            <div class="img-show"><div><img src="http://file4.batdongsan.com.vn/resize/350x280/2016/01/21/20160121171906-9f37.jpg"></div></div>
                                        </a>
                                        <p class="infor-by-up">Căn hộ chung cư Bán</p>
                                        <p class="name-post"><a href="#">LOREM IPSUM DOLORIT </a></p>
                                        <p class="fs-15 font-400">21 Nguyễn Trung Ngạn, P. Bến Nghé, Q1</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var searchForm = $('#search-duan-form');
        $('#findProject').keyup(function () {
            var self = $(this);
            var val = self.val().trim();
            var url = searchForm.attr('action');
            var ss = $('.suggest-search');

            if (val.length >= 2) {
                $.get(url, searchForm.serialize(), function (response) {
                    if (response.length > 0) {
                        ss.removeClass('hide');

                        var html = '';
                        for (var i in response) {
                            html += '<li><a href="/building-project/redirect-view?id='+response[i][1]+'">' + response[i][0].full_name + '</a></li>';
                        }
                        $('.content-suggest ul').html(html);
                    } else {
                        ss.addClass('hide');
                    }
                });
            } else {
                $('#v').val('');
                ss.addClass('hide');
            }
        });
    });
</script>