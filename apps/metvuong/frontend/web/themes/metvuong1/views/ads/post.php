<?php
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->title = Yii::t('express','We offer exeptional amenities and renowned white - glove services');
?>
 <div class="wrap-dangtin">
                <div class="title-frm" clearfix>Đăng tin</div>
                <div class="wrap-step-post clearfix">
                    <form action="" id="frm-post-tin" class="form-horizontal">
                        <ul id="progressbar" class="clearfix">
                            <li class="active">
                                <div>
                                    <a href="#">1</a>
                                    <span class="line-process"></span>
                                    <div>Step 1</div>
                                </div>
                            </li>
                            <li class="step-center">
                                <div>
                                    <a href="#">2</a>
                                    <span class="line-process"></span>
                                    <div>Step 2</div>
                                </div>
                            </li>
                            <li class="text-left">
                                <div>
                                    <a href="#">3</a>
                                    <span class="line-process"></span>
                                    <div>Hoàn thành</div>
                                </div>
                            </li>
                        </ul>
                        <div class="fieldset clearfix" style="display:block;">
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Địa chỉ:</label>
                                <div class="col-sm-10 group-item-frm">
                                    <input type="email" class="form-control" id="" placeholder="Số nhà">
                                    <select class="form-control">
                                        <option selected>--Tên đường--</option>
                                        <option>Nguyễn Chung Ngạn</option>
                                        <option>Nguyễn Thị Minh Khai</option>
                                        <option>Lê Thánh Tôn</option>
                                    </select>
                                    <select class="form-control mgB-0">
                                        <option selected>--Phường/Xã--</option>
                                        <option>Phường 1</option>
                                        <option>Phường 2</option>
                                        <option>Phường 3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group text-inline">
                                <label for="" class="col-sm-2 control-label">Diện tích:</label>
                                <div class="col-sm-10">
                                    <div class="inline-group col-xs-6">
                                        <input type="email" class="form-control" id="">
                                    </div>
                                    <div class="inline-group col-xs-6 pdR-0">
                                        <span>m<sup>2</sup></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Giá:</label>
                                <div class="col-sm-10 group-item-frm">
                                    <div class="inline-group col-xs-6">
                                        <input type="email" class="form-control" id="">
                                    </div>
                                    <div class="inline-group col-xs-6 pdR-0">
                                        <select class="form-control">
                                        <option selected>--Đơn vị--</option>
                                        <option>Thỏa thuận</option>
                                        <option>Trăm nghìn/tháng</option>
                                        <option>Triệu/tháng</option>
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-common mgT-15 next action-button pull-right">Tiếp theo<em class="fa fa-chevron-right"></em></button>
                        </div>
                        <div class="fieldset clearfix" style="display:none;">
                            <div class="form-group">
                                <div class="title-sub-frm">Thông tin mở rộng</div>
                            </div>
                            <div class="form-group">
                                <div class="row row-group-inline">
                                    <div class="col-xs-3">
                                        <select class="form-control">
                                            <option>Quận/Huyện(*)</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-3">
                                        <select class="form-control">
                                            <option>Phường/Xã(*)</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-3">
                                        <select class="form-control">
                                            <option>Đường/Phố(*)</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-3">
                                        <select class="form-control">
                                            <option>Dự án</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" rows="3" placeholder="Nội thất..."></textarea>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <div class="title-sub-frm">Thông tin liện hệ</div>
                            </div>
                            <div class="form-group">
                                <div class="row row-group-inline">
                                    <div class="col-xs-3">
                                        <input type="text" class="form-control" placeholder="Tên liên hệ">
                                    </div>
                                    <div class="col-xs-3">
                                        <input type="text" class="form-control" placeholder="Điện thoại">
                                    </div>
                                    <div class="col-xs-3">
                                        <input type="text" class="form-control" placeholder="Di động(*)">
                                    </div>
                                    <div class="col-xs-3">
                                        <input type="text" class="form-control" placeholder="Email">
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Địa chỉ">
                            </div>
                            <div class="form-group">
                                <div class="title-sub-frm">Bản đồ</div>
                            </div>
                            <div class="form-group">
                                <p>Để tăng độ tin cậy và tin rao được nhiều người quan tâm hơn, hãy sửa vị trí tin rao của bạn trên bản đồ bằng cách kéo icon<em class="fa fa-map-marker"></em>tới đúng vị trí của tin rao.</p>
                                <iframe width="600" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=place_id:ChIJ0T2NLikpdTERKxE8d61aX_E&amp;key=AIzaSyDgukAnWQNq0fitebULUbottG5gvK64OCQ" allowfullscreen=""></iframe>
                            </div>
                            <button type="button" class="btn btn-primary btn-common mgT-15 next action-button pull-right">Tiếp theo<em class="fa fa-chevron-right"></em></button>
                            <button type="button" class="btn btn-primary btn-common mgT-15 previous action-button pull-right"><em class="fa fa-chevron-left"></em>Quay lại</button>
                        </div>
                        <div class="fieldset clearfix finish-post" style="display:none;">
                            text
                            <button type="button" class="btn btn-primary btn-common mgT-15 next action-button pull-right">Đăng bài</button>
                        </div>
                    </form>
                </div>
            </div>
<div class="social-share">
    <ul>
        <li><a href="#"><em class="fa fa-facebook"></em></a></li>
        <li><a href="#"><em class="fa fa-twitter"></em></a></li>
        <li><a href="#"><em class="fa fa-instagram"></em></a></li>
        <li><a href="#"><em class="fa fa-google-plus"></em></a></li>
        <li><a href="#"><em class="fa fa-youtube-play"></em></a></li>
        <li><a href="#"><em class="fa fa-pinterest"></em></a></li>
        <li><a href="#"><em class="fa fa-linkedin"></em></a></li>
    </ul>
</div>
