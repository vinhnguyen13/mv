<?php
use yii\helpers\Url;
?>
<?php if(!Yii::$app->user->isGuest) { ?>
    <div class="container">
        <div class="menuUser menuReport">
            <ul class="clearfix">
                <li>
                    <a class="dashboard-item <?= !empty($this->params['menuDashboard']) ? 'active' : ''; ?>" href="<?= Url::to(['/dashboard/ad', 'username' => Yii::$app->user->identity->getUsername()]) ?>">
                        <div><span class="icon-mv"><span class="icon-barometer"></span></span></div>
                        <?= Yii::t('report', 'User') ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('.menuReport li a').click(function (e) {
                $('body').loading();
                $('.menuUser li a').removeClass('active');
                $(this).addClass('active');
                window.history.pushState("object or string", "Title", $(this).attr('href'));
                if($(this).hasClass('wrapNotifyChat')){
                    $(document).trigger('chat/removeBoxChat');
                }
                $.ajax({
                    type: "get",
                    dataType: 'html',
                    url: $(this).attr('href'),
                    success: function (data) {
                        $('body').loading({done: true});
                        $('.contentContainer').html(data);
                        $(document).trigger('chat/listLoadDefault');
                        $(window).trigger('resize');
                    }
                });

                return false;
            });
        });
    </script>
<?php }?>