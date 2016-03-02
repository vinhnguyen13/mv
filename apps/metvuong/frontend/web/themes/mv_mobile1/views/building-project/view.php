<div class="title-fixed-wrap">
    <div class="detail-duan-moi">
        <div class="title-top">DEUSTCH HOUSE </div>
        <div class="wrap-duan-moi">
            <div class="gallery-detail swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="img-show">
                            <div>
                                <img src="http://file4.batdongsan.com.vn/resize/745x510/2016/01/21/20160121155233-cbe9.jpg" alt="Đường Trần Khắc Chân, Phường Tân Định,  Quận 1, Hồ Chí Minh">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="img-show">
                            <div>
                                <img src="http://file4.batdongsan.com.vn/resize/745x510/2016/01/21/20160121155233-cbe9.jpg" alt="Đường Trần Khắc Chân, Phường Tân Định,  Quận 1, Hồ Chí Minh">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="img-show">
                            <div>
                                <img src="http://file4.batdongsan.com.vn/resize/745x510/2016/01/21/20160121155233-cbe9.jpg" alt="Đường Trần Khắc Chân, Phường Tân Định,  Quận 1, Hồ Chí Minh">
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="img-show">
                            <div>
                                <img src="http://file4.batdongsan.com.vn/resize/745x510/2016/01/21/20160121155233-cbe9.jpg" alt="Đường Trần Khắc Chân, Phường Tân Định,  Quận 1, Hồ Chí Minh">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
            <div class="item infor-address-duan">
                <p>Chung cư cao cấp</p>
                <strong>Lancaster x</strong>
                <span class="icon address-icon"></span>21 Nguyễn Trung Ngạn, P. Bến Nghé, Q1
                <ul class="pull-right icons-detail">
                    <li><a href="#" class="icon icon-share-td"></a></li>
                    <li><a href="#" class="icon save-item" data-id="4115" data-url="/ad/favorite"></a></li>
                    <li><a href="#" class="icon icon-map-loca"></a></li>
                </ul>
            </div>
            <div class="item infor-time">
                <p><strong>Chủ đầu tư:</strong> ABC COMPANY</p>
                <p><strong>Kiến trúc sư:</strong> XYZ ARCHITECT</p>
                <p><strong>Nhà thầu thi công:</strong> DEF CONSTRUCTION CO</p>
                <p><strong>Ngày khởi công:</strong> 27/02/2016</p>
                <p><strong>Ngày dự kiến hoàn thành:</strong> 27/09/2018</p>
            </div>
            <div class="item detail-infor">
                <p class="title-attr-duan">Diễn tả chi tiết</p>
                <p>Mauris non tempor quam, et lacinia sapien. Mauris accumsan eros eget libero posuere vulputate. Etiam elit elit, elementum sed varius at, adipiscing vitae est. Sed nec felis pellentesque, lacinia dui sed, ultricies sapien. Pellentesque orci lectus, consectetur vel posuere posuere.</p>
            </div>
            <div class="item infor-attr">
                <p class="title-attr-duan">Thông tin dự án</p>
                <ul class="clearfix">
                    <li><strong>Mặt tiền:</strong>10m</li>
                    <li><strong>Tầng cao:</strong>19 Tầng</li>
                    <li><strong>Thang máy:</strong>4</li>
                </ul>
            </div>
            <div class="item tien-ich-duan">
                <p class="title-attr-duan">Tiện ích</p>
                <ul class="clearfix">
                    <li>
                        <div><p><span class="icon-ti icon-sport"></span>Sports</p></div>
                    </li>
                    <li>
                        <div><p><span class="icon-ti icon-sport"></span>Sports</p></div>
                    </li>
                    <li>
                        <div><p><span class="icon-ti icon-sport"></span>Sports</p></div>
                    </li>
                    <li>
                        <div><p><span class="icon-ti icon-sport"></span>Sports</p></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div id="popup-map" class="popup-common hide-popup">
    <div class="wrap-popup">
        <div class="inner-popup">
            <a href="#" class="btn-close-map">trở lại</a>
            <iframe width="600" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=place_id:ChIJT7lZ30cvdTER8skpPrOuvGs&key=AIzaSyDgukAnWQNq0fitebULUbottG5gvK64OCQ" allowfullscreen></iframe>
        </div>
    </div>
</div>

<div id="popup-share-social" class="popup-common hide-popup">
    <div class="wrap-popup">
        <div class="inner-popup">
            <a href="#" class="btn-close"><span class="icon icon-close"></span></a>
            <div class="wrap-body-popup">
                <span>Share on Social Network</span>
                <ul class="clearfix">
                    <li>
                        <a href="#">
                            <div class="circle"><div><span class="icon icon-face"></span></div></div>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="circle"><div><span class="icon icon-email-1"></span></div></div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            spaceBetween: 30
        });

        $('#popup-map').popupMobi({
            btnClickShow: ".icon-map-loca",
            closeBtn: "#popup-map .btn-close-map"
        });

        $('#popup-share-social').popupMobi({
            btnClickShow: ".icons-detail .icon-share-td",
            closeBtn: ".btn-close",
            styleShow: "center"
        });
    });
</script>