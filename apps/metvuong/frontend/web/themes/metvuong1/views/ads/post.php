<?php
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->title = Yii::t('express','We offer exeptional amenities and renowned white - glove services');
?>
<div class="wrap-dangtin">
    <div class="title-frm" clearfix>Đăng tin</div>
    <form action="" id="frm-post-tin">
        <div class="form-group inline-group col-xs-3">
            <label for="exampleInputEmail1">Bắt đầu</label>
            <select class="form-control">
                <option>27/11/2015</option>
                <option>27/11/2015</option>
                <option>27/11/2015</option>
                <option>27/11/2015</option>
                <option>27/11/2015</option>
            </select>
        </div>
        <div class="form-group inline-group col-xs-3">
            <label for="exampleInputEmail1">Kết thúc</label>
            <select class="form-control">
                <option>27/11/2015</option>
                <option>27/11/2015</option>
                <option>27/11/2015</option>
                <option>27/11/2015</option>
                <option>27/11/2015</option>
            </select>
        </div>
        <div class="form-group">
            <textarea class="form-control" rows="3" placeholder="Nội dung bài viết..."></textarea>
        </div>
        <div class="form-group mgB-35">
            <input type="file">
        </div>
        <div class="form-group">
            <div class="title-sub-frm">Thông tin cơ bản</div>
        </div>
        <div class="form-group inline-group col-xs-3">
            <select class="form-control">
                <option>Hình thức(*)</option>
            </select>
        </div>
        <div class="form-group inline-group col-xs-3">
            <select class="form-control">
                <option>Loại(*)</option>
            </select>
        </div>
        <div class="clearfix"></div>
        <div class="form-group inline-group col-xs-3">
            <select class="form-control">
                <option>Tỉnh/Thành phố(*)</option>
            </select>
        </div>
        <div class="form-group inline-group col-xs-3">
            <select class="form-control">
                <option>Quận/Huyện(*)</option>
            </select>
        </div>
        <div class="form-group inline-group col-xs-3">
            <select class="form-control">
                <option>Phường/Xã(*)</option>
            </select>
        </div>
        <div class="form-group inline-group col-xs-3">
            <select class="form-control">
                <option>Đường/Phố(*)</option>
            </select>
        </div>
        <div class="form-group inline-group col-xs-3">
            <select class="form-control">
                <option>Dự án</option>
            </select>
        </div>
        <div class="form-group inline-group col-xs-3">
            <input type="text" class="form-control" placeholder="Diện tích">
        </div>
        <div class="form-group inline-group col-xs-3">
            <input type="text" class="form-control" placeholder="Giá">
        </div>
        <div class="form-group inline-group col-xs-3">
            <input type="text" class="form-control" placeholder="Đơn vị">
        </div>
        <div class="clearfix"></div>
        <div class="form-group mgT-25">
            <div class="title-sub-frm">Thông tin mở rộng</div>
        </div>
        <div class="form-group inline-group col-xs-3">
            <select class="form-control">
                <option>Quận/Huyện(*)</option>
            </select>
        </div>
        <div class="form-group inline-group col-xs-3">
            <select class="form-control">
                <option>Phường/Xã(*)</option>
            </select>
        </div>
        <div class="form-group inline-group col-xs-3">
            <select class="form-control">
                <option>Đường/Phố(*)</option>
            </select>
        </div>
        <div class="form-group inline-group col-xs-3">
            <select class="form-control">
                <option>Dự án</option>
            </select>
        </div>
        <div class="form-group">
            <textarea class="form-control" rows="3" placeholder="Nội thất..."></textarea>
        </div>
        <div class="clearfix"></div>
        <div class="form-group mgT-25">
            <div class="title-sub-frm">Thông tin liện hệ</div>
        </div>
        <div class="form-group inline-group col-xs-3">
            <input type="text" class="form-control" placeholder="Tên liên hệ">
        </div>
        <div class="form-group inline-group col-xs-3">
            <input type="text" class="form-control" placeholder="Điện thoại">
        </div>
        <div class="form-group inline-group col-xs-3">
            <input type="text" class="form-control" placeholder="Di động(*)">
        </div>
        <div class="form-group inline-group col-xs-3">
            <input type="text" class="form-control" placeholder="Email">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Địa chỉ">
        </div>
        <div class="clearfix"></div>
        <div class="form-group mgT-25">
            <div class="title-sub-frm">Bản đồ</div>
        </div>
        <div class="form-group">
            <p>Để tăng độ tin cậy và tin rao được nhiều người quan tâm hơn, hãy sửa vị trí tin rao của bạn trên bản đồ bằng cách kéo icon<em class="fa fa-map-marker"></em>tới đúng vị trí của tin rao.</p>
            <iframe width="600" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=place_id:ChIJ0T2NLikpdTERKxE8d61aX_E&key=AIzaSyDgukAnWQNq0fitebULUbottG5gvK64OCQ" allowfullscreen></iframe>
        </div>
        <div class="text-center">
            <button type="button" class="btn btn-primary btn-common mgT-15">Đăng tin</button>
        </div>
    </form>
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
