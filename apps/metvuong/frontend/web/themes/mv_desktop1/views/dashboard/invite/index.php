<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 6/28/2016 1:42 PM
 */
?>
<?php 
use yii\web\View;
$this->registerJsFile ( Yii::$app->view->theme->baseUrl . '/resources/js/swiper.jquery.min.js', ['position' => View::POS_END]);
?>
<div class="title-fixed-wrap container">
    <div class="invite-friend block-dash">
    	<div class="inner-dash">
	    	<div class="frm-invite">
	    		<p class="mgB-15"><span class="color-red d-ib mgR-5 font-600">Lưu ý:</span> Khi "Mời thêm bạn bè" thì keys của bạn sẽ được tặng thêm 10key.</p>
	    		<form action="">
	    			<div class="form-group">
						<label for="">Email:</label>
						<input type="email" class="form-control" id="" placeholder="Email">
					</div>
					<div class="form-group">
						<label for="">Nội dung:</label>
						<textarea name="" id="" class="form-control w-100"></textarea>
					</div>
	    		</form>
	    	</div>
	    	<div class="tbl-wrap clearfix">
	            <div class="thead clearfix">
	                <div class="w-10 pull-left"><span>ID</span></div>
	                <div class="w-30 pull-left"><span>Email bạn bè</span></div>
	                <div class="w-40 pull-left"><span>Nội dung</span></div>
	                <div class="w-20 pull-left"><span>Trạng thái</span></div>
	            </div>
				<div class="wrap-tr-each swiper-container">
					<div class="inner-tr clearfix swiper-wrapper">
						<div class="clearfix tbl-emu swiper-slide">
							<div class="w-10"><span>1</span></div>
							<div class="w-30"><span>demo@gmail.com</span></div>
							<div class="w-40"><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sollicitudin convallis nunc sit amet ullamcorper. Morbi libero tortor, blandit et maximus at, iaculis euismod dui.</span></div>
							<div class="w-20"><span>đang chờ</span></div>
						</div>
						<div class="clearfix tbl-emu swiper-slide">
							<div class="w-10"><span>1</span></div>
							<div class="w-30"><span>demo123@gmail.com</span></div>
							<div class="w-40"><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sollicitudin convallis nunc sit amet ullamcorper. Morbi libero tortor, blandit et maximus at, iaculis euismod dui.</span></div>
							<div class="w-20 color-cd font-600"><span>thành công</span></div>
						</div>
						<div class="clearfix tbl-emu swiper-slide">
							<div class="w-10"><span>1</span></div>
							<div class="w-30"><span>demo@gmail.com</span></div>
							<div class="w-40"><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sollicitudin convallis nunc sit amet ullamcorper. Morbi libero tortor, blandit et maximus at, iaculis euismod dui.</span></div>
							<div class="w-20"><span>đang chờ</span></div>
						</div>
						<div class="clearfix tbl-emu swiper-slide">
							<div class="w-10"><span>1</span></div>
							<div class="w-30"><span>demo123@gmail.com</span></div>
							<div class="w-40"><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sollicitudin convallis nunc sit amet ullamcorper. Morbi libero tortor, blandit et maximus at, iaculis euismod dui.</span></div>
							<div class="w-20 color-cd font-600"><span>thành công</span></div>
						</div>
						<div class="clearfix tbl-emu swiper-slide">
							<div class="w-10"><span>1</span></div>
							<div class="w-30"><span>demo@gmail.com</span></div>
							<div class="w-40"><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sollicitudin convallis nunc sit amet ullamcorper. Morbi libero tortor, blandit et maximus at, iaculis euismod dui.</span></div>
							<div class="w-20"><span>đang chờ</span></div>
						</div>
						<div class="clearfix tbl-emu swiper-slide">
							<div class="w-10"><span>1</span></div>
							<div class="w-30"><span>demo123@gmail.com</span></div>
							<div class="w-40"><span>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus sollicitudin convallis nunc sit amet ullamcorper. Morbi libero tortor, blandit et maximus at, iaculis euismod dui.</span></div>
							<div class="w-20 color-cd font-600"><span>thành công</span></div>
						</div>
					</div>
				</div>
				<div class="swiper-pagination"></div>
	        </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var swiper = undefined;
        $(window).on('resize', function () {
             var wWindow = $(window).outerWidth();
            if ( wWindow <= 500 && swiper == undefined ) {
                swiper = new Swiper('.wrap-tr-each.swiper-container', {
                    pagination: '.swiper-pagination',
                    paginationClickable: true,
                    spaceBetween: 0
                });        
            }else if ( wWindow > 500 && swiper != undefined ) {
                swiper.destroy();
                swiper = undefined;
                $('.swiper-wrapper').removeAttr('style');
                $('.swiper-slide').removeAttr('style');   
            }
        }).trigger('resize');
    });
</script>
