<?php

use yii\web\View;
/* @var $this yii\web\View */
$this->registerCssFile('https://fonts.googleapis.com/css?family=Roboto:400,700|Noticia+Text:400italic,400,700,700italic', ['type' => 'text/css']);
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/swiper.css");

Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/plugins/swiper.jquery.min.js', ['position'=>View::POS_END]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/plugins/jquery.easing.js', ['position'=>View::POS_END]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/plugins/jquery.scrollspeed.js', ['position'=>View::POS_END]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/home.js', ['position'=>View::POS_END]);
Yii::$app->getView()->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&callback=initMap', ['position'=>View::POS_END, 'defer'=>'', 'async'=>'']);

$this->title = Yii::t('express','We offer exeptional amenities and renowned white - glove services');
?>
<div id="home-page">
    <div id="paralax-overlay">
        <div class="sk-cube-grid">
            <div class="sk-cube sk-cube1"></div>
            <div class="sk-cube sk-cube2"></div>
            <div class="sk-cube sk-cube3"></div>
            <div class="sk-cube sk-cube4"></div>
            <div class="sk-cube sk-cube5"></div>
            <div class="sk-cube sk-cube6"></div>
            <div class="sk-cube sk-cube7"></div>
            <div class="sk-cube sk-cube8"></div>
            <div class="sk-cube sk-cube9"></div>
        </div>
    </div>
    <div id="paralax-wrapper">
        <div id="paralax-nav">
            <ul class="items">
                <li class="active"><a href="#">THE BUILDING</a><div class="arrow-down"></div></li>
                <li><a href="#">APARTMENTS</a><div class="arrow-down"></div></li>
                <li><a href="#">AMENITIES</a><div class="arrow-down"></div></li>
                <li><a href="#">VIEWS</a><div class="arrow-down"></div></li>
                <li><a href="#">NEIGHBORHOOD</a><div class="arrow-down"></div></li>
                <li><a href="#">PRICING</a><div class="arrow-down"></div></li>
                <li><a href="#">LOCATION</a><div class="arrow-down"></div></li>
            </ul>
        </div>
        <div id="paralax-page">
            <div class="section section-building">
                <div class="container">
                    <div class="flyout">
                        <h2 class="noti">Lancaster Legacy offers you a sweeping panoramic view of the city skyline<span class="hr"></span></h2>
                        <p>Besides 109 ultra-luxury and graciously furnished apartments ranging from studios to penthouses, the building also features 6 floors of working space for setting up professional and supreme offices.</p>
                    </div>
                </div>
            </div>
            <div class="section section-swiper">
                <div class="swiper-group">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/living-room.jpg" /></div>
                            <div class="swiper-slide"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/kitchen.jpg" /></div>
                            <div class="swiper-slide"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/bathroom.jpg" /></div>
                            <div class="swiper-slide"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/bedroom.jpg" /></div>
                        </div>
                        <div class="swiper-button swiper-button-prev"></div>
                        <div class="swiper-button swiper-button-next"></div>
                    </div>
                    <div class="swiper-map">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button">LIVING ROOM</div></div>
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button">KITCHEN</div></div>
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button">BATHROOM</div></div>
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button">BEDROOM</div></div>
                        </div>
                    </div>
                    <div class="slide-contents">
                        <div class="slide-content">
                            <p class="bold">Living Room</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                        <div class="slide-content">
                            <p class="bold">Kitchen</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                        <div class="slide-content">
                            <p class="bold">Bathroom</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                        <div class="slide-content">
                            <p class="bold">Bedroom</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section section-swiper">
                <div class="swiper-group">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/swiming-pool.jpg" /></div>
                            <div class="swiper-slide"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/fitness-center.jpg" /></div>
                            <div class="swiper-slide"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/healthy-care.jpg" /></div>
                            <div class="swiper-slide"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/skybar.jpg" /></div>
                        </div>
                        <div class="swiper-button swiper-button-prev"></div>
                        <div class="swiper-button swiper-button-next"></div>
                    </div>
                    <div class="swiper-map">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button">SWIMMING POOL</div></div>
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button">FITNESS CENTER</div></div>
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button">HEALTHY CARE</div></div>
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button">SKYBAR</div></div>
                        </div>
                    </div>
                    <div class="slide-contents">
                        <div class="slide-content">
                            <p class="bold">Swimmin Pool</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                        <div class="slide-content">
                            <p class="bold">Fitness Center</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                        <div class="slide-content">
                            <p class="bold">Healthy Care</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                        <div class="slide-content">
                            <p class="bold">Skybar</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section section-swiper">
                <div class="swiper-group">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/north.jpg" /></div>
                            <div class="swiper-slide"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/east.jpg" /></div>
                            <div class="swiper-slide"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/south.jpg" /></div>
                            <div class="swiper-slide"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/west.jpg" /></div>
                        </div>
                        <div class="swiper-button swiper-button-prev"></div>
                        <div class="swiper-button swiper-button-next"></div>
                    </div>
                    <div class="swiper-map">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button">NORTH</div></div>
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button">EAST</div></div>
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button">SOUTH</div></div>
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button">WEST</div></div>
                        </div>
                    </div>
                    <div class="slide-contents">
                        <div class="slide-content">
                            <p class="bold">North</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                        <div class="slide-content">
                            <p class="bold">East</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                        <div class="slide-content">
                            <p class="bold">South</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                        <div class="slide-content">
                            <p class="bold">West</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section section-swiper">
                <div class="swiper-group">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/restaurants.jpg" /></div>
                            <div class="swiper-slide"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/markets.jpg" /></div>
                            <div class="swiper-slide"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/shopping.jpg" /></div>
                            <div class="swiper-slide"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/entertainment.jpg" /></div>
                            <div class="swiper-slide"><img src="<?=Yii::$app->view->theme->baseUrl?>/resources/images/park.jpg" /></div>
                        </div>
                        <div class="swiper-button swiper-button-prev"></div>
                        <div class="swiper-button swiper-button-next"></div>
                    </div>
                    <div class="swiper-map">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button">RESTAURANTS</div></div>
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button">MARKETS</div></div>
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button">SHOPPING</div></div>
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button">ENTERTAINMENT</div></div>
                            <div class="swiper-slide"><div class="arrow-down"></div><div class="arrow-up"></div><div class="slide-button">PARKS</div></div>
                        </div>
                    </div>
                    <div class="slide-contents">
                        <div class="slide-content">
                            <p class="bold">Restaurants</p>
                            <p class="restaurant-name">El Gaucho</p>
                            <p class="restaurant-address">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                            <p class="restaurant-name">Le Jardin</p>
                            <p class="restaurant-address">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                            <p class="restaurant-name">Red Wine Bar</p>
                            <p class="restaurant-address">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                            <p class="restaurant-name">Monocle</p>
                            <p class="restaurant-address">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                        </div>
                        <div class="slide-content">
                            <p class="bold">Markets</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                        <div class="slide-content">
                            <p class="bold">Shopping</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                        <div class="slide-content">
                            <p class="bold">Entertaiment</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                        <div class="slide-content">
                            <p class="bold">Parks</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section section-pricing">
                <div class="container">
                    <table>
                        <tbody>
                        <tr class="bgtitle">
                            <th>AREA (SQM)</th>
                            <th>KIND OF APATMENT</th>
                            <th>MONTHLY RATES (VN?)</th>
                            <th>DAILY RATES (VN?)</th>
                        </tr>
                        <tr>
                            <td>150</td>
                            <td>1-Bedroom</td>
                            <td>80,660,000</td>
                            <td>3,270,000</td>
                        </tr>
                        <tr>
                            <td>86</td>
                            <td>2-Bedroom</td>
                            <td>61,040,000</td>
                            <td>2,616,000</td>
                        </tr>
                        <tr>
                            <td>71</td>
                            <td>3-Bedroom</td>
                            <td>54,500,000</td>
                            <td>2,398,000</td>
                        </tr>
                        <tr>
                            <td>55</td>
                            <td>Penhouse</td>
                            <td>50,140,000</td>
                            <td>2,140,000</td>
                        </tr>
                        <tr>
                            <td>38</td>
                            <td>Studio</td>
                            <td>43,600,000</td>
                            <td>1,600,000</td>
                        </tr>
                        <tr>
                            <td>38</td>
                            <td>Studio</td>
                            <td>40,330,000</td>
                            <td>1,330,000</td>
                        </tr>
                        </tbody>
                    </table>
                    <div><a href="#" class="book-now">BOOK NOW</a></div>
                    <div><a href="#" class="phone noti"><i class="icon icon-phone"></i>Call 0903 090 909 for more infomation</a></div>
                </div>
            </div>
            <div class="section section-location">
                <div class="container">
                    <div class="clear">
                        <div class="left">
                            <ul class="lancaster-list">
                                <li>
                                    <a data-lat="10.753647" data-lng="106.711543" href="#" class="title noti">Lancaster Legacy</a>
                                    <div class="content-wrap">
                                        <div class="content">
                                            <div class="label">ADDRESS</div>
                                            <div class="text">78 Ton That Thuyet Street, Ward 16, District 4, HCMC, Viet Nam</div>
                                            <div class="label">PHONE</div>
                                            <div class="text">+ 84 8 3 8246810</div>
                                            <div class="label">FAX</div>
                                            <div class="text">+ 84 8 3 8298163</div>
                                            <div class="label">EMAIL</div>
                                            <div class="text">sales@trungthuygroup.vn</div>
                                            <div class="label">HOTLINE</div>
                                            <div class="text">0903 090 909</div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <a data-lat="10.780980" data-lng="106.704539" href="#" class="title noti">Lancaster Le Thanh Ton</a>
                                    <div class="content-wrap">
                                        <div class="content">
                                            <div class="label">ADDRESS</div>
                                            <div class="text">22 - 22 Bis Le Thanh Ton Street, Ben Nghe </div>
                                            <div class="label">PHONE</div>
                                            <div class="text">+ 84 8 3 8246666</div>
                                            <div class="label">FAX</div>
                                            <div class="text">+ 84 8 3 8299556 - 3 8298700</div>
                                            <div class="label">EMAIL</div>
                                            <div class="text">sales@trungthuygroup.vn</div>
                                            <div class="label">HOTLINE</div>
                                            <div class="text">0903 090 909</div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <a data-lat="10.764702" data-lng="106.686850" href="#" class="title noti">Lancaster Nguyen Trai</a>
                                    <div class="content-wrap">
                                        <div class="content">
                                            <div class="label">ADDRESS</div>
                                            <div class="text">230 Nguyen Trai Street, District 1, HCMC, Viet Nam</div>
                                            <div class="label">PHONE</div>
                                            <div class="text">+ 84 8 3 8246810</div>
                                            <div class="label">FAX</div>
                                            <div class="text">+ 84 8 3 8298163</div>
                                            <div class="label">EMAIL</div>
                                            <div class="text">sales@trungthuygroup.vn</div>
                                            <div class="label">HOTLINE</div>
                                            <div class="text">0903 090 909</div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <a data-lat="21.029205" data-lng="105.823676" href="#" class="title noti">Lancaster Ha Noi</a>
                                    <div class="content-wrap">
                                        <div class="content">
                                            <div class="label">ADDRESS</div>
                                            <div class="text">20 Nui Truc Street, Ba Dinh District, Ha Noi, Viet Nam</div>
                                            <div class="label">PHONE</div>
                                            <div class="text">+ 84 4 3 726 3865</div>
                                            <div class="label">FAX</div>
                                            <div class="text">+ 84 4 3 726 3864</div>
                                            <div class="label">EMAIL</div>
                                            <div class="text">sales.lhb@trungthuygroup.vn</div>
                                            <div class="label">HOTLINE</div>
                                            <div class="text">0939 44 2222</div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="location-right-wrap">
                            <div class="right">
                                <div class="social">
                                    <a href="#" class="icon-social icon-facebook"></a>
                                    <a href="#" class="icon-social icon-instagram"></a>
                                    <a href="#" class="icon-social icon-youtube"></a>
                                </div>
                                <div class="ratio-wrap" id="map-wrap">
                                    <div class="ratio"><div id="map"></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
