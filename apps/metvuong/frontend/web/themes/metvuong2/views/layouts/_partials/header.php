<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$value = \Yii::$app->getRequest()->getCookies()->getValue('searchParams');
$searchParams = json_decode($value);
?>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).bind( 'submit_search', function(event, json, string, callback){
            setTimeout(function(){callback();},1000);
            setTimeout(function() {
                if (typeof ga !== "undefined") {
                    ga('send', {hitType: 'event',eventCategory: 'Listing',eventAction: 'click',eventLabel: 'SearchForm'});
                }
                setTimeout(function() {$('#search-kind, #map-search-form').submit();},100);
            },100);
            return false;
        });

        $(document).bind( 'real-estate/news', function(event, json, string, callback){
            setTimeout(function(){callback();},1000);
            setTimeout(function() {
                if (typeof ga !== "undefined") {
                    ga('send', {hitType: 'event',eventCategory: 'PostListing',eventAction: 'click',eventLabel: 'SearchForm'});
                }
                setTimeout(function() {$('#search-kind').submit();},100);
            },100);
        });
        
        $(document).bind( 'real-estate/post', function(event, json, string, callback){
            setTimeout(function(){callback();},1000);
            setTimeout(function() {
                if (typeof ga !== "undefined") {
                    ga('send', {hitType: 'event',eventCategory: 'News',eventAction: 'click',eventLabel: 'SearchForm'});
                }
                setTimeout(function() {$('#search-kind').submit();},100);
            },100);
        });

    });
</script>
<header class="clearfix">
	<input type="hidden" id="search-value" value="<?= htmlentities(json_encode(Yii::$app->request->get())) ?>" />
    <div class="container inner-header">
        <ul class="pull-right list-menu">
            <li><a href="<?=Url::to(['ad/index', 'type'=>1]);?>">Buy</a></li>
            <li><a href="<?=Url::to(['ad/index', 'type'=>1]);?>">Rent</a></li>
            <!-- <li><a href="<?=Url::to(['ad/post']);?>">Sell</a></li> -->
            <li><a href="#">Sell</a></li>
            <li><a href="javascript:alert('Comming Soon !');">Market Insights</a></li>
            <?php if(Yii::$app->user->isGuest){?>
                <li class="link-signup"><a href="#" data-toggle="modal" data-target="#frmRegister">Sign up</a></li>
                <li class="link-login"><a href="#" data-toggle="modal" data-target="#frmLogin">Login</a></li>
                <li class="change-lang"><a href="<?=Url::current(['language-change'=>'en-US'])?>" class="lang-icon icon-en"></a></li>
                <li class="change-lang"><a href="<?=Url::current(['language-change'=>'vi-VN'])?>" class="lang-icon icon-vi"></a></li>
            <?php }else{?>
                <li class="user-loggin"><a href="<?=Url::to(['user-management/index'])?>">
                    <span class="avatar-user"><img src="<?=Yii::$app->user->identity->profile->getAvatarUrl();?>" alt="" width="40" height="40"></span>
                    <span class="name-user"><?=!empty(Yii::$app->user->identity->profile->name) ? Yii::$app->user->identity->profile->name : Yii::$app->user->identity->email;?></span></a>
                </li>
                <li class="icon-notifi">
                    <a href="#"><em class="fa fa-bell"></em><span>5</span></a>
                </li>
                <li>
                    <a href="#" class="sub-setting-user"></a>
                    <div class="settings container-effect hidden-effect">
                        <ul class="sub-setting wrap-effect">
                            <li>
                                <a data-method="post" href="<?=\yii\helpers\Url::to(['/member/logout'])?>"><em class="icon-logout pull-right"></em><?=Yii::t('user', 'Logout')?></a>
                            </li>
                            <li>
                                <a href="<?=Url::to(['user-management/index'])?>"><em class="fa fa-cog pull-right"></em>Settings</a>
                            </li>
                            <li class="line-option">
                                <div class="select-lang">
                                    <a href="<?=Url::current(['language-change'=>'vi-VN'])?>" class="lang-icon icon-vi pull-right"></a>
                                    <a href="<?=Url::current(['language-change'=>'en-US'])?>" class="lang-icon icon-en pull-right"></a>
                                    Language
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
            <?php }?>
        </ul>
        <a href="<?=Url::home()?>" class="logo-header"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/logo.png" alt=""></a>
    </div>
</header>

<?php $this->beginContent('@app/views/layouts/_partials/popup.php'); ?><?php $this->endContent();?>