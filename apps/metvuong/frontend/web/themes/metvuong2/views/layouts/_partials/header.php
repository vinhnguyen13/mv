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
                setTimeout(function() {$('#search-kind').submit();},100);
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
    <div class="container inner-header">
        <ul class="pull-right list-menu">
            <li><a href="<?=Url::to(['ad/index', 'type'=>1]);?>">Buy</a></li>
            <li><a href="<?=Url::to(['ad/index', 'type'=>1]);?>">Rent</a></li>
            <!-- <li><a href="<?=Url::to(['ad/post']);?>">Sell</a></li> -->
            <li><a href="#" data-toggle="modal" data-target="#regis-listing">Sell</a></li>
            <li><a href="javascript:alert('Comming Soon !');">Market Insights</a></li>
            <?php if(Yii::$app->user->isGuest){?>
                <li class="link-signup"><a href="#" data-toggle="modal" data-target="#frmRegister">Sign up</a></li>
                <li class="link-login"><a href="#" data-toggle="modal" data-target="#frmLogin">Login</a></li>
                <li class="change-lang"><a href="<?=Url::current(['language-change'=>'en-US'])?>" class="lang-icon icon-en"></a></li>
                <li class="change-lang"><a href="<?=Url::current(['language-change'=>'vi-VN'])?>" class="lang-icon icon-vi"></a></li>
            <?php }else{?>
                <li class="user-loggin"><a href="<?=Url::to(['user-management/index'])?>">
                    <span class="avatar-user"><img src="<?=Yii::$app->user->identity->profile->getAvatarUrl();?>" alt="" width="40" height="40"></span>
                    <span class="name-user"><?=!empty(Yii::$app->user->identity->profile->name) ? Yii::$app->user->identity->profile->name : Yii::$app->user->identity->email;?></span>
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

<div class="modal fade regis-listing" id="regis-listing" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class=""></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="wrap-modal clearfix">
                    <div class="left-regis-listing">
                        <div class="txt-title">steps</div>
                        <ul class="list-steps">
                            <li class="active"><a href="#"><span>step 1</span>General Information</a></li>
                            <li class=""><a href="#"><span>step 2</span>Detali Information</a></li>
                            <li><a href="#"><span>step 3</span>Amenities</a></li>
                            <li><a href="#"><span>step 4</span>Upload Images</a></li>
                            <li><a href="#"><span>step 5</span>Preview & Post</a></li>
                        </ul>
                    </div>
                    <div class="right-regis-listing">
                        <div class="title-step">Welcome to your best listing experience online</div>
                        <form class="clearfix">
                            <div class="wrap-step step-listing-1">
                                <div><strong>Your property is at:</strong></div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <select class="form-control" name="" id="">
                                            <option value="" disabled selected>Chọn Tỉnh / Thành</option>
                                            <option value="">Hồ Chí Minh</option>
                                            <option value="">Hà Nội</option>
                                            <option value="">Đồng Nai</option>
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <select class="form-control" name="" id="">
                                            <option value="" disabled selected>Chọn Quận / Huyện</option>
                                            <option value="">Bình Chánh</option>
                                            <option value="">Tân Bình</option>
                                            <option value="">Tân Phú</option>
                                        </select>
                                        
                                    </div>
                                    <div class="col-xs-6">
                                        <select class="form-control" name="" id="">
                                            <option value="" disabled selected>Chọn Phường / Xã</option>
                                            <option value="">1</option>
                                            <option value="">2</option>
                                            <option value="">3</option>
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <input type="text" class="form-control" id="" placeholder="Địa Chỉ">
                                    </div>
                                    <div class="col-xs-3">
                                        <select name="" id="">
                                            <option value="" disabled selected>Level 24</option>
                                            <option value="">1</option>
                                            <option value="">2</option>
                                            <option value="">3</option>
                                        </select>
                                        
                                    </div>
                                    <div class="col-xs-3">
                                        <select name="" id="">
                                            <option value="" disabled selected>Unit 2401</option>
                                            <option value="">1</option>
                                            <option value="">2</option>
                                            <option value="">3</option>
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <select name="" id="">
                                            <option value="" disabled selected>Dự Án</option>
                                            <option value="">1</option>
                                            <option value="">2</option>
                                            <option value="">3</option>
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="mgT-30"><strong>Type of listing:</strong></div>
                                <div class="form-group row">
                                    <div class="col-xs-6">
                                        <select name="" id="">
                                            <option value="" disabled selected>Loại Bất Động Sản</option>
                                            <option value="">1</option>
                                            <option value="">2</option>
                                            <option value="">3</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-3 num-bedrooms">
                                        <input type="text" class="form-control" id=""> <span>Bedrooms</span>
                                    </div>
                                    <div class="col-xs-3 num-bathrooms">
                                        <input type="text" class="form-control" id=""> <span>Bathrooms</span>
                                    </div>
                                </div>
                                <div class="form-group dt-regis row">
                                    <div class="col-xs-6">
                                        <input type="text" class="form-control" id="" placeholder="Diện Tích">  
                                        <span>/ m2  </span>
                                    </div>
                                    <div class="col-xs-6">
                                        <input type="text" class="form-control" id="" placeholder="Diện Tích">
                                        <span>/ m2 Maintenance Fee</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-12 prices-regis">
                                        <div><input type="text" class="form-control" id="" placeholder="Giá"> 
                                        <span>Total or</span></div>
                                        <div><input type="text" class="form-control" id="" placeholder="Giá"> <span>/ m2</span></div>
                                    </div>    
                                </div>
                            </div>
                            <div class="wrap-step step-listing-2">
                                <div><strong>Detail Information</strong></div>
                                <div class="row">
                                    <div class="col-xs-4 checkbox-ui">
                                        <em class="fa fa-square-o"></em>
                                        <input type="checkbox">
                                        <label for="">Kitchen</label>
                                    </div>
                                    <div class="col-xs-4 checkbox-ui">
                                        <em class="fa fa-square-o"></em>
                                        <input type="checkbox">
                                        <label for="">Cooker</label>
                                    </div>
                                    <div class="col-xs-4 checkbox-ui">
                                        <em class="fa fa-square-o"></em>
                                        <input type="checkbox">
                                        <label for="">Hood</label>
                                    </div>
                                    <div class="col-xs-4 checkbox-ui">
                                        <em class="fa fa-square-o"></em>
                                        <input type="checkbox">
                                        <label for="">Microwave</label>
                                    </div>
                                    <div class="col-xs-4 checkbox-ui">
                                        <em class="fa fa-square-o"></em>
                                        <input type="checkbox">
                                        <label for="">Dishwasher</label>
                                    </div>
                                    <div class="col-xs-4 checkbox-ui">
                                        <em class="fa fa-square-o"></em>
                                        <input type="checkbox">
                                        <label for="">Balcony</label>
                                    </div>
                                    <div class="col-xs-4 checkbox-ui">
                                        <em class="fa fa-square-o"></em>
                                        <input type="checkbox">
                                        <label for="">Air Conditioning</label>
                                    </div>
                                    <div class="col-xs-4 checkbox-ui">
                                        <em class="fa fa-square-o"></em>
                                        <input type="checkbox">
                                        <label for="">Flooring</label>
                                    </div>
                                    <div class="col-xs-4 checkbox-ui">
                                        <em class="fa fa-square-o"></em>
                                        <input type="checkbox">
                                        <label for="">Wardrobe</label>
                                    </div>
                                    <div class="col-xs-4 checkbox-ui">
                                        <em class="fa fa-square-o"></em>
                                        <input type="checkbox">
                                        <label for="">Voice Intercom</label>
                                    </div>
                                    <div class="col-xs-4 checkbox-ui">
                                        <em class="fa fa-square-o"></em>
                                        <input type="checkbox">
                                        <label for="">Furnitures</label>
                                    </div>
                                    <div class="col-xs-4 checkbox-ui">
                                        <em class="fa fa-square-o"></em>
                                        <input type="checkbox">
                                        <label for="">Beds</label>
                                    </div>
                                    <div class="col-xs-4 checkbox-ui">
                                        <em class="fa fa-square-o"></em>
                                        <input type="checkbox">
                                        <label for="">Sofa</label>
                                    </div>
                                    <div class="col-xs-4 checkbox-ui">
                                        <em class="fa fa-square-o"></em>
                                        <input type="checkbox">
                                        <label for="">TV</label>
                                    </div>
                                    <div class="col-xs-4 checkbox-ui">
                                        <em class="fa fa-square-o"></em>
                                        <input type="checkbox">
                                        <label for="">Dining Set</label>
                                    </div>
                                </div>
                            </div>
                            <div class="wrap-step step-listing-3">
                                step 3
                            </div>
                            <div class="wrap-step step-listing-4">
                                <div><strong>Upload your listing images</strong></div>
                                <div class="wrap-img-upload row">
                                    <div class="col-xs-4">
                                        <div class="img-uploaded"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-1.jpg" alt=""></div>
                                        <input type="text" placeholder="Tabs...">
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="img-uploaded"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-1.jpg" alt=""></div>
                                        <input type="text" placeholder="Tabs...">
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="img-uploaded"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-1.jpg" alt=""></div>
                                        <input type="text" placeholder="Tabs...">
                                    </div>
                                </div>
                            </div>
                            <div class="wrap-step step-listing-5">
                                step 5
                            </div>
                            <div class="pull-right btn-bottom-step"><button class="btn-next-step">Next</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>