<?php
use yii\helpers\Url;
?>
<?php if(!Yii::$app->user->isGuest) { ?>
    <div class="menuUser">
        <ul class="clearfix">
            <li>
                <a href="<?= Url::to(['/dashboard/ad', 'username' => Yii::$app->user->identity->username]) ?>"
                   class="<?= !empty($this->params['menuDashboard']) ? 'active' : ''; ?>">
                    <div>
                        <span class="icon-mv"><span class="icon-fill-2-fill-6-fill-8"></span></span>
                    </div>
                    <?= Yii::t('ad', 'Listings') ?>
                </a>
            </li>
            <li>
                <a href="<?= Url::to(['/notification/index', 'username' => Yii::$app->user->identity->username]) ?>"
                   class="wrapNotifyOther <?= !empty($this->params['menuNotification']) ? 'active' : ''; ?>">
                    <div>
                        <span class="icon-mv"><span class="icon-icons-bell"></span></span>
                        <?php if (!empty($this->params['notify_other'])) { ?>
                            <span id="notifyOther" class="notifi"><?= $this->params['notify_other']; ?></span>
                        <?php } ?>
                    </div>
                    <?= Yii::t('activity', 'Notification') ?>
                </a>
            </li>
            <li>
                <a href="<?= Url::to(['/chat/index', 'username' => Yii::$app->user->identity->username]) ?>"
                   class="wrapNotifyChat  <?= !empty($this->params['menuChat']) ? 'active' : ''; ?>">
                    <div>
                        <span class="icon-mv"><span class="icon-bubbles-icon"></span></span>
                        <?php if (!empty($this->params['notify_chat'])) { ?>
                            <span id="notifyChat" class="notifi"><?= $this->params['notify_chat']; ?></span>
                        <?php } ?>
                    </div><?= Yii::t('chat', 'Chat') ?>
                </a>
            </li>
            <li><a href="<?= Url::to(['member/update-profile', 'username' => Yii::$app->user->identity->username]) ?>"
                   class="<?= !empty($this->params['menuUpdateProfile']) ? 'active' : ''; ?>">
                    <div>
                        <span class="icon-mv"><span class="icon-settings"></span></span>
                    </div>
                    <?= Yii::t('user', 'Setting') ?>
                </a>
            </li>
        </ul>
    </div>

    <script>
        $(document).ready(function () {
            $('.menuUser li a').click(function (e) {
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
                    }
                });
                return false;
            });
        });
    </script>
<?php }?>