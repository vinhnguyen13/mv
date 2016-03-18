<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 1/28/2016 10:15 AM
 */
use yii\helpers\Url;

?>
<div class="title-fixed-wrap">
    <div class="container">
        <div class="infor-duan">
            <div class="title-top">DỰ ÁN MỚI</div>
            <div class="wrap-infor-duan">
                <div class="search-duan">
                    <form id="search-duan-form" action="<?= \yii\helpers\Url::to(['building-project/find'], true) ?>" method="post" onsubmit="javascript: return false;">
                        <input name="project_name" class="project_name" type="text" placeholder="Gõ tên dự án cần tìm…">
                        <button type="submit" id="btn-search-duan"><span class="icon icon-search-small-1"></span></button>
                    </form>
                </div>
                <div class="list-duan">
                    <ul class="clearfix row">
                        <?= $this->render('_partials/result', ['models' => $models]) ?>        
                    </ul>
                </div>
                <nav class="text-center">
                    <ul class="pagination">
                        <li>
                            <a href="#" aria-label="Previous">Back</a>
                        </li>
                        <li><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li>
                        <a href="#" aria-label="Next">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#btn-search-duan').click(function(){
            var textSearch = $('.project_name').val();
            if(textSearch !== '') {
                $('body').loading();
                $.ajax({
                    type: "post",
                    dataType: 'html',
                    url: $('#search-duan-form').attr('action'),
                    data: $('#search-duan-form').serializeArray(),
                    success: function (data) {
                        if (data) {
                            $('.list-duan').html('');
                            $('.list-duan').html(data);
                            $('body').loading({done: true});
                        }
                    }
                });
            }
            return false;
        });
    });
</script>