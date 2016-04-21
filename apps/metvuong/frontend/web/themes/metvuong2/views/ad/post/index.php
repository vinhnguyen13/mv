<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 1/15/2016
 * Time: 10:07 AM
 */
?>
<div class="regis-listing" id="regis-listing">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="wrap-modal clearfix">
                    <div class="left-regis-listing">
                        <div class="txt-title">steps</div>
                        <ul class="list-steps">
                            <li class="active"><a href="#"><span>step 1</span>General Information</a></li>
                            <li class=""><a href="#"><span>step 2</span>Detail Information</a></li>
                            <li><a href="#"><span>step 3</span>Amenities</a></li>
                            <li><a href="#"><span>step 4</span>Upload Images</a></li>
                            <li><a href="#"><span>step 5</span>Preview & Post</a></li>
                        </ul>
                    </div>
                    <div class="right-regis-listing">
                        <div class="title-step">Welcome to your best listing experience online</div>
                        <form class="clearfix">
                            <div class="animated go hide-step wrap-step step-listing-1">
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
                            <div class="animated go hide-step wrap-step step-listing-2">
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
                            <div class="animated go hide-step wrap-step step-listing-3">
                                step 3
                            </div>
                            <div class="animated go hide-step wrap-step step-listing-4">
                                <div><strong>Upload your listing images</strong></div>
                                <div class="wrap-img-upload row">
                                    <div class="col-xs-4">
                                        <div class="img-uploaded"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-1.jpg" alt=""></div>
                                        <select class="form-control">
                                            <option>Nội Thất</option>
                                            <option>Ngoại Thất</option>
                                            <option>Bản Vẽ</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="img-uploaded"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-1.jpg" alt=""></div>
                                        <select class="form-control">
                                            <option>Nội Thất</option>
                                            <option>Ngoại Thất</option>
                                            <option>Bản Vẽ</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="img-uploaded"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/du-an-1.jpg" alt=""></div>
                                        <select class="form-control">
                                            <option>Nội Thất</option>
                                            <option>Ngoại Thất</option>
                                            <option>Bản Vẽ</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="animated go hide-step wrap-step step-listing-5">
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
