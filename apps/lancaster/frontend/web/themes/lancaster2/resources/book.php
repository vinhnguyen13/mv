<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/select2.css" />
		<link rel="stylesheet" href="css/jquery-ui.css" />
		
		<!-- Common css + js -->
		<link rel="stylesheet" href="css/main.css" />
		<script src="js/plugins/jquery.min.js"></script>
		<script src="js/main.js"></script>
		<!-- Common css + js -->
		
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,700|Noticia+Text:400italic,400,700,700italic' rel='stylesheet' type='text/css'>
		<script src="js/plugins/select2.min.js"></script>
		<script src="js/plugins/jquery-ui.min.js"></script>
		<script src="js/book.js"></script>
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
			<div id="book-page">
				<div class="container">
					<div class="center">
						<h1 class="title">Booking</h1>
						<div class="form-wrap">
							<form>
								<div class="table">
									<div class="table-row">
										<label class="table-cell">Checkin date *</label>
										<div class="table-cell">
											<input class="date-picker text-field" type="text" readonly />
										</div>
									</div>
									<div class="table-row">
										<label class="table-cell">Checkout date *</label>
										<div class="table-cell">
											<input class="date-picker text-field" type="text" readonly />
										</div>
									</div>
									<div class="table-row">
										<label class="table-cell">Building *</label>
										<div class="table-cell">
											<input class="text-field" type="text" />
										</div>
									</div>
									<div class="table-row break">
										<div class="table-cell"><label>Apartment type *</label></div>
										<div class="table-cell">
											<select class="custom-select atype">
												<option>1-Bed</option>
												<option>2-Bed</option>
												<option>3-Bed</option>
												<option>Penhouse</option>
												<option>Studio</option>
											</select>
											<div class="break-on-mobile"></div>
											<label>Floorplan *</label><select class="custom-select">
												<option>21</option>
												<option>22</option>
												<option>23</option>
												<option>24</option>
											</select>
										</div>
									</div>
									<div class="table-row">
										<label class="table-cell">Full name *</label>
										<div class="table-cell">
											<input class="text-field" type="text" />
										</div>
									</div>
									<div class="table-row">
										<label class="table-cell">Phone number *</label>
										<div class="table-cell">
											<input class="text-field" type="text" />
										</div>
									</div>
									<div class="table-row">
										<label class="table-cell">Email *</label>
										<div class="table-cell">
											<input class="text-field" type="text" />
										</div>
									</div>
									<div class="table-row">
										<label class="table-cell">Address</label>
										<div class="table-cell">
											<input class="text-field" type="text" />
										</div>
									</div>
									<div class="table-row">
										<label class="table-cell">Passport No.</label>
										<div class="table-cell">
											<input class="text-field" type="text" />
										</div>
									</div>
									<div class="table-row">
										<label class="table-cell">Nationality</label>
										<div class="table-cell">
											<input class="text-field" type="text" />
										</div>
									</div>
									<div class="table-row">
										<label class="table-cell v-top">Infomation</label>
										<div class="table-cell">
											<textarea class="textarea-field" rows="4"></textarea>
										</div>
									</div>
									<div class="table-row">
										<div class="table-cell"></div>
										<div class="table-cell center">
											<input class="submit" type="submit" value="SUBMIT" />
										</div>
									</div>
								</div>
							</form>
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