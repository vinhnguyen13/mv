<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,700|Noticia+Text:400italic,400,700,700italic' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="css/swiper.css" />
		
		<!-- Common css + js -->
		<link rel="stylesheet" href="css/main.css" />
		<script src="js/plugins/jquery.min.js"></script>
		<script src="js/main.js"></script>
		<!-- Common css + js -->
		
		<script src="js/plugins/swiper.jquery.min.js"></script>
		<script src="js/plugins/jquery.easing.js"></script>
		<script src="js/plugins/jquery.scrollspeed.js"></script>
		<script src="js/home.js"></script>
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASTv_J_7DuXskr5SaCZ_7RVEw7oBKiHi4&callback=initMap"></script>
	</head>
	<body>
		<header>
			<div id="top-bar" class="clear">
				<div id="branch-wrap">
					<ul>
						<li><a href="#">Lancaster Legacy</a></li>
						<li><a href="#">Lancaster Lê Thánh Tôn</a></li>
						<li><a href="#">Lancaster Nguyễn Trãi</a></li>
						<li><a href="#">Lancaster Hà Nội</a></li>
					</ul>
				</div>
				<a href="#" id="logo" class="left"><span class="logo"></span><span class="arrow"></span></a>
				<a href="#" class="menu-button" id="mobile-menu-button"></a>
				<div id="mobile-menu">
					<a href="#" class="book-now right">BOOK NOW</a>
					<a href="#" class="menu-button" id="menu-nav"></a>
					<div class="right nav">
						<ul class="menu clear">
							<li><a href="#">About Us</a></li>
							<li><a href="#">News</a></li>
							<li><a href="#">Contact Us</a></li>
						</ul>
						<i class="separator"></i>
						<ul class="langs clear">
							<li class="active"><a href="#">En</a></li>
							<li><a href="#">Vi</a></li>
						</ul>
					</div>
				</div>
			</div>
		</header>
		<main>
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
								        <div class="swiper-slide"><img src="images/living-room.jpg" /></div>
								        <div class="swiper-slide"><img src="images/kitchen.jpg" /></div>
								        <div class="swiper-slide"><img src="images/bathroom.jpg" /></div>
								        <div class="swiper-slide"><img src="images/bedroom.jpg" /></div>
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
								        <div class="swiper-slide"><img src="images/swiming-pool.jpg" /></div>
								        <div class="swiper-slide"><img src="images/fitness-center.jpg" /></div>
								        <div class="swiper-slide"><img src="images/healthy-care.jpg" /></div>
								        <div class="swiper-slide"><img src="images/skybar.jpg" /></div>
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
								        <div class="swiper-slide"><img src="images/north.jpg" /></div>
								        <div class="swiper-slide"><img src="images/east.jpg" /></div>
								        <div class="swiper-slide"><img src="images/south.jpg" /></div>
								        <div class="swiper-slide"><img src="images/west.jpg" /></div>
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
								        <div class="swiper-slide"><img src="images/restaurants.jpg" /></div>
								        <div class="swiper-slide"><img src="images/markets.jpg" /></div>
								        <div class="swiper-slide"><img src="images/shopping.jpg" /></div>
								        <div class="swiper-slide"><img src="images/entertainment.jpg" /></div>
								        <div class="swiper-slide"><img src="images/park.jpg" /></div>
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
											<th>MONTHLY RATES (VNĐ)</th>
											<th>DAILY RATES (VNĐ)</th>
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
														<div class="text">22 – 22 Bis Le Thanh Ton Street, Ben Nghe </div>
														<div class="label">PHONE</div>
														<div class="text">+ 84 8 3 8246666</div>
														<div class="label">FAX</div>
														<div class="text">+ 84 8 3 8299556 – 3 8298700</div>
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
		</main>
		<footer>
			<div class="container footer-top">
				<div class="left"><a href="#" id="logo-footer"></a></div>
				<div class="footer-right">
					<div class="right">
						<div class="lancasters-footer">
							<a href="#">LANCASTER LEGACY</a>
							<span class="f-seperator"></span>
							<a href="#">LANCASTER LE THANH TON</a>
							<span class="f-seperator"></span>
							<a href="#">LANCASTER NGUYEN TRAI</a>
							<span class="f-seperator"></span>
							<a href="#">LANCASTER HA NOI</a>
						</div>
						<div class="clear foot-nav">
							<div class="left">
								<ul class="menu clear">
									<li><a href="#">Về Chúng Tôi</a></li>
									<li><a href="#">Tin Tức</a></li>
									<li><a href="#">Liên Hệ</a></li>
									<li><a href="#">Newsletter</a></li>
								</ul>
							</div>
							<div class="right"><a href="#" class="book-now">BOOK NOW</a></div>
						</div>
					</div>
				</div>
			</div>
			<div class="footer-bottom">
				<div class="container">@ 2015 Lancaster. All rights serversed by TTG Group</div>
			</div>
		</footer>
	</body>
</html>