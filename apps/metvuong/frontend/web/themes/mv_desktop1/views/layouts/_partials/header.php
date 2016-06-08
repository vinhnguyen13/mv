<?php
use yii\helpers\Url;
use vsoft\ad\models\AdProduct;
use frontend\models\AdProductSearch;
?>
<header class="clearfix header">
    <div class="container wrap-header">
        <div class="m-header">
            <a href="#menu-header" id="menu-toggle" class="pull-left">
                <span class="icon-mv"><span class="icon-menu"></span></span>
            </a>
            <a href="#settings-user" id="avatar-user" class="wrap-img wrapNotifyTotal">
                <div>
                    <?php if(Yii::$app->user->isGuest){?>
                        <img src="/images/default-avatar.jpg" alt="">
                    <?php } else{?>
                        <img id="headAvatar" src="<?=Yii::$app->user->identity->profile->getAvatarUrl();?>" alt="">
                        <?php if(!empty($this->params['notify_total'])){?>
                            <span id="notifyTotal"><?=$this->params['notify_total'];?></span>
                        <?php }?>
                    <?php }?>
                </div>
            </a>
            <div class="logo">
                <a href="<?=Url::home()?>">metvuong</a>
            </div>
            <div id="menu-header" class="menu-header">
                <div class="wrap-menu">
                    <a href="#" id="hide-menu" class="icon"></a>
                    <ul class="clearfix">
                        <li class="<?=!empty($this->params['menuBuy']) ? 'active' : '' ;?>"><a href="<?= Url::to(['/ad/index1']) ?>"><span class="icon-mv"><span class="icon-icons-search"></span></span><?=Yii::t('general', 'Buy')?></a></li>
                        <li class="<?=!empty($this->params['menuRent']) ? 'active' : '' ;?>"><a href="<?= Url::to(['/ad/index2']) ?>"><span class="icon-mv"><span class="icon-icons-search"></span></span><?=Yii::t('general', 'Rent')?></a></li>
                        <li class="<?=!empty($this->params['menuSell']) ? 'active' : '' ;?>"><a href="<?= Url::to(['/ad/post']) ?>"><span class="icon-mv"><span class="icon-key"></span></span><?=Yii::t('general', 'Sell')?></a></li>
                        <li class="<?=!empty($this->params['menuProject']) ? 'active' : '' ;?>"><a href="<?=Url::to(['building-project/index']);?>"><span class="icon-mv"><span class="icon-fill-1"></span></span><?=Yii::t('general', 'New Project')?></a></li>
                        <li class="<?=!empty($this->params['menuNews']) ? 'active' : '' ;?>"><a href="<?=Url::to(['news/index']);?>"><span class="icon-mv"><span class="icon-group"></span></span><?=Yii::t('general', 'News')?></a></li>
                        <li class="<?=!empty($this->params['menuPricing']) ? 'active' : '' ;?>"><a href="<?=Url::to(['/payment/package'])?>"><span class="icon-mv"><span class="icon-pricing-icon"></span></span><?=Yii::t('general', 'Pricing')?></a></li>
                    </ul>
                </div>
            </div>

            <div id="settings-user" class="settings-user">
                <a href="#" id="hide-settings" class="icon"></a>
                <?php if(Yii::$app->user->isGuest){?>
                    <ul class="clearfix">
                        <li><a href="<?=Url::to(['member/login'])?>" class="user-login-link" ><?=Yii::t('user', 'Sign In')?></a></li>
                        <li><a href="<?=Url::to(['member/signup'])?>" class="user-signup-link"><?=Yii::t('user', 'Sign Up')?></a></li>
                        <li class="flag-lang">
                            <p class="pull-right">
                                <a href="<?=Url::current(['language-change'=>'en-US'])?>"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/flag-en.png' ?>" alt=""></a>
                                <a href="<?=Url::current(['language-change'=>'vi-VN'])?>"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/flag-vn.png' ?>" alt=""></a>
                            </p>
                            <?=Yii::t('general', 'Language')?>
                        </li>
                    </ul>
                <?php } else{?>
                <ul class="clearfix">
                    <li class="user-edit">
                        <a href="<?=Url::to(['member/profile', 'username'=>Yii::$app->user->identity->getUsername()])?>">
                            <span class="wrap-img"><img src="<?=Yii::$app->user->identity->profile->getAvatarUrl();?>" alt="" width="40" height="40"></span>
                            <div>
                                <p><span class="name-user"><?=Yii::$app->user->identity->profile->getDisplayName();?></span>
                                <span class="address"><?=empty(Yii::$app->user->identity->location) ? "" : Yii::$app->user->identity->location->city?></span></p>
                            </div>
                        </a>
                    </li>
                    <li class="num-gold">
                        <a href="<?=Url::to(['/dashboard/payment', 'username'=> Yii::$app->user->identity->getUsername()])?>" class="fs-14"><span class="icon-mv mgR-5 color-gold"><span class="icon-coin-dollar"></span></span>10 Keys</a>
                    </li>
                    <!-- <li><a href="<?= Url::to(['/ad/post']) ?>"><em class="icon-plus"></em>Đăng tin mới</a></li> -->
                    <li><a href="<?=Url::to(['/dashboard/ad', 'username'=> Yii::$app->user->identity->getUsername()])?>"><span class="icon-mv"><span class="icon-barometer"></span></span><?=Yii::t('ad', 'Dashboard')?></a></li>
                    <li><a href="<?=Url::to(['/notification/index', 'username'=> Yii::$app->user->identity->getUsername()])?>" class="wrapNotifyOther"><span class="icon-mv"><span class="icon-icons-bell"></span></span><?=Yii::t('activity', 'Notification')?>
                            <?php if(!empty($this->params['notify_other'])){?>
                                <span id="notifyOther" class="notifi"><?=$this->params['notify_other'];?></span>
                            <?php }?>
                        </a></li>
                    <li><a href="<?=Url::to(['/chat/index', 'username'=> Yii::$app->user->identity->getUsername()])?>" class="wrapNotifyChat"><span class="icon-mv"><span class="icon-bubbles-icon"></span></span><?=Yii::t('chat', 'Chat')?>
                            <?php if(!empty($this->params['notify_chat'])){?>
                                <span id="notifyChat" class="notifi"><?=$this->params['notify_chat'];?></span>
                            <?php }?>
                    </a></li>
                    <li><a data-method="post" href="<?=Url::to(['member/update-profile', 'username'=>Yii::$app->user->identity->getUsername()])?>"><span class="icon-mv"><span class="icon-settings"></span></span><?=Yii::t('user', 'Setting')?></a></li>
                    <li><a data-method="post" href="<?=Url::to(['/member/logout'])?>"><span class="icon icon-logout"></span><?=Yii::t('user', 'Log Out')?></a></li>
                    <li class="flag-lang">
                        <p class="pull-right">
                            <a href="<?=Url::current(['language-change'=>'en-US'])?>"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/flag-en.png' ?>" alt=""></a>
                            <a href="<?=Url::current(['language-change'=>'vi-VN'])?>"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/flag-vn.png' ?>" alt=""></a>
                        </p>
                        <?=Yii::t('general', 'Language')?>
                    </li>
                </ul>
                <?php } ?>
            </div>
        </div>

        <div class="dt-header clearfix">
            <div class="user-login pull-right">
                <?php if(Yii::$app->user->isGuest){?>
                <div class="box-dropdown guest-dropdown">
                    <a href="#" class="icon-guest val-selected wrap-img">
                        <div><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/default-avatar.jpg' ?>" alt=""></div>
                        <?= Yii::t('general', 'Guest') ?>
                    </a>
                    <div class="item-dropdown hide-dropdown">
                        <ul class="clearfix">
                            <li><a href="<?=Url::to(['/member/login'])?>" class="user-login-link"><?=Yii::t('user', 'Sign In')?></a></li>
                            <li><a href="<?=Url::to(['/member/signup'])?>" class="user-signup-link"><?=Yii::t('user', 'Sign Up')?></a></li>
                            <li class="flag-lang">
                                <div class="pull-right">
                                    <a href="<?=Url::current(['language-change'=>'en-US'])?>"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/flag-en.png' ?>" alt=""></a>
                                    <a href="<?=Url::current(['language-change'=>'vi-VN'])?>"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/flag-vn.png' ?>" alt=""></a>
                                </div>
                                <?=Yii::t('general', 'Language')?>
                            </li>
                        </ul>
                    </div>
                </div>
                <?php } else{?>
                    <ul class="pull-left list-redire">
                        <li class="num-gold">
                            <a href="<?=Url::to(['/dashboard/payment', 'username'=> Yii::$app->user->identity->getUsername()])?>" class="fs-14"><span class="icon-mv mgR-5 color-gold"><span class="icon-coin-dollar"></span></span>10 Keys</a>
                        </li>
                        <li class="dropdown-message box-dropdown">
                            <a class="val-selected wrapNotifyChat" href="<?=Url::to(['/chat/index', 'username'=> Yii::$app->user->identity->getUsername()])?>" data-toggle="tooltip" data-placement="bottom" title="<?=Yii::t('chat', 'Chat')?>">
                                <span class="icon-mv"><span class="icon-bubbles-icon"></span></span>
                                <?php if(!empty($this->params['notify_chat'])){?>
                                    <span id="notifyChat" class="notifi"><?=$this->params['notify_chat'];?></span>
                                <?php }?>
                            </a>
                            <div class="item-dropdown hide-dropdown">
                                <div class="list-message">
                                    <div class="wrap-item-messa-head">
                                        <ul class="clearfix">
                                        </ul>
                                    </div>
                                    <a href="<?=Url::to(['/chat/index', 'username'=> Yii::$app->user->identity->getUsername()])?>" class="view-more"><?=Yii::t('general', 'View more')?></a>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-noti box-dropdown">
                            <a class="val-selected wrapNotifyOther" href="<?=Url::to(['/notification/index', 'username'=> Yii::$app->user->identity->getUsername()])?>" data-toggle="tooltip" data-placement="bottom" title="<?=Yii::t('activity', 'Notification')?>">
                                <span class="icon-mv"><span class="icon-icons-bell"></span></span>
                                <?php if(!empty($this->params['notify_other'])){?>
                                    <span id="notifyOther" class="notifi"><?=$this->params['notify_other'];?></span>
                                <?php }?>
                            </a>
                            <div class="item-dropdown hide-dropdown">
                                <div class="list-notify">
                                    <div class="wrap-item-noti-head">
                                        <ul class="clearfix"></ul>
                                    </div>
                                    <a href="<?=Url::to(['/notification/index', 'username'=> Yii::$app->user->identity->getUsername()])?>" class="view-more"><?=Yii::t('general', 'View more')?></a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="" href="<?=Url::to(['/dashboard/ad', 'username'=> Yii::$app->user->identity->getUsername()])?>" data-toggle="tooltip" data-placement="bottom" title="Dashboard">
                                <span class="icon-mv"><span class="icon-barometer"></span></span>
                            </a>
                        </li>
                    </ul>
                    <div class="user-edit box-dropdown">
                        <a class="val-selected wrapNotifyTotal" data-toggle="tooltip" data-placement="bottom" href="#" title="<?=Yii::t('user', 'Profile')?>">
                            <span class="wrap-img"><img src="<?=Yii::$app->user->identity->profile->getAvatarUrl();?>" alt="" width="40" height="40"></span>
                            <div>
                                <p><span class="name-user"><?=Yii::$app->user->identity->profile->getDisplayName();?></span>
                                    <span class="address"><?=empty(Yii::$app->user->identity->location) ? "" : Yii::$app->user->identity->location->city?></span></p>
                            </div>
                        </a>
                        <div class="item-dropdown hide-dropdown">
                            <ul class="clearfix">
                                <li>
                                    <a href="<?=Url::to(['member/profile', 'username'=>Yii::$app->user->identity->getUsername()])?>">
                                        <div>
                                            <span class="icon-mv"><span class="icon-user-icon-profile1"></span></span>
                                        </div>
                                        <?=Yii::t('user', 'Profile')?>
                                    </a>
                                </li>
                                <li><a data-method="post" href="<?=Url::to(['member/update-profile', 'username'=>Yii::$app->user->identity->getUsername()])?>"><div><span class="icon-mv"><span class="icon-settings"></span></span></div><?=Yii::t('user', 'Setting')?></a></li>
                                <li><a data-method="post" href="<?=Url::to(['/member/logout'])?>"><div><span class="icon icon-logout"></span></div><?=Yii::t('user', 'Log Out')?></a></li>
                                <li class="flag-lang">
                                    <div class="pull-right">
                                        <a href="<?=Url::current(['language-change'=>'en-US'])?>"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/flag-en.png' ?>" alt=""></a>
                                        <a href="<?=Url::current(['language-change'=>'vi-VN'])?>"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/flag-vn.png' ?>" alt=""></a>
                                    </div>
                                    <?=Yii::t('general', 'Language')?>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <ul class="clearfix list-menu">
                <li class="dt-logo"><a href="/" class="wrap-img"><img src="<?= Yii::$app->view->theme->baseUrl . '/resources/images/logo.png' ?>" alt=""></a></li>
                <li class="<?=!empty($this->params['menuBuy']) ? 'active' : '' ;?>"><a href="<?= Url::to(['/ad/index1']) ?>"><?=Yii::t('general', 'Buy')?></a></li>
                <li class="<?=!empty($this->params['menuRent']) ? 'active' : '' ;?>"><a href="<?= Url::to(['/ad/index2']) ?>"><?=Yii::t('general', 'Rent')?></a></li>
                <li class="<?=!empty($this->params['menuSell']) ? 'active' : '' ;?>"><a href="<?= Url::to(['/ad/post']) ?>"><?=Yii::t('general', 'Sell')?></a></li>
                <li class="<?=!empty($this->params['menuProject']) ? 'active' : '' ;?>"><a href="<?=Url::to(['building-project/index']);?>"><?=Yii::t('general', 'New Project')?></a></li>
                <li class="<?=!empty($this->params['menuNews']) ? 'active' : '' ;?>"><a href="<?=Url::to(['news/index']);?>"><?=Yii::t('general', 'News')?></a></li>
                <li class="<?=!empty($this->params['menuPricing']) ? 'active' : '' ;?>"><a href="<?=Url::to(['/payment/package'])?>"><?=Yii::t('general', 'Pricing')?></a></li>
            </ul>
        </div>
    </div>
</header>
<div id="popup-login" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
                <div class="wrap-popup">
                    <div class="inner-popup">
                        <div class="wrap-body-popup">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="popup-signup" class="modal fade popup-common" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <a href="#" class="btn-close close" data-dismiss="modal" aria-label="Close"><span class="icon icon-close"></span></a>
                <div class="wrap-popup">
                    <div class="inner-popup">
                        <div class="wrap-body-popup">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(document).on('click', '.user-login-link', function (e) {
            /*if(checkMobile()){
                return true;
            }*/
            e.preventDefault();
            $('body').loading();
            $.ajax({
                type: "get",
                url: "<?=Url::to(['/member/login'])?>",
                success: function (data) {
                    $('body').loading({done: true});
                    $('#popup-login .wrap-body-popup').html(data);
                    $('#popup-login').modal('show');
                }
            });

        });

        $(document).on('click', '.user-signup-link', function (e) {
            /*if(checkMobile()){
                return true;
            }*/
            e.preventDefault();
            $('body').loading();
            $.ajax({
                type: "get",
                url: "<?=Url::to(['/member/signup'])?>",
                success: function (data) {
                    $('body').loading({done: true});
                    $('#popup-signup .wrap-body-popup').html(data);
                    $('#popup-signup').modal('show');
                }
            });
        });

        $('.guest-dropdown,.user-edit').dropdown({
            styleShow: 0,
            selectedValue: false
        });
        <?php if(!Yii::$app->user->isGuest){?>
        
        $('.dropdown-message').dropdown({
            styleShow: 0,
            selectedValue: false,
            ajaxSubmit: function () {
                $('.list-message').loading({full: true});
                $.ajax({
                    type: 'GET',
                    url: '<?=Url::to(['/chat/list', 'username'=> Yii::$app->user->identity->getUsername()])?>',
                    success: function (response) {
                        $('body').loading({done: true});
                        $('.wrap-item-messa-head').html(response);
                    }
                });
            }
        });

        $('.dropdown-noti').dropdown({
            styleShow: 0,
            selectedValue: false,
            ajaxSubmit: function () {
                $('.list-notify').loading({full: true});
                $.ajax({
                    type: 'GET',
                    url: '<?=Url::to(['/notification/list', 'username'=> Yii::$app->user->identity->getUsername()])?>',
                    success: function (response) {
                        $('body').loading({done: true});
                        $('.wrap-item-noti-head').html(response);
                    }
                });
            }
        });
        <?php }?>
    });
</script>