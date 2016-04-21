var step;

$(document).ready(function(){
	var defaultCity = 1;
	var currentCity;
	
	step = $('.step');
	
	$('#category').change(function(){
		if($(this).val() == '324') {
			$('.show-cho-du-an').show();
			$('.show-cho-nha-rieng').hide();
		} else {
			$('.show-cho-du-an').hide();
			$('.show-cho-nha-rieng').show();
		}
	});
	
	$('.next').click(function(e){
		next();
		e.preventDefault();
	});
	$('.prev').click(function(e){
		prev();
		e.preventDefault();
	});
	
	for(index in dataCities) {
		if(defaultCity == index) {
			$('#city').append('<option selected="selected" value="' + index + '">' + dataCities[index]['name'] + '</option>');
			currentCity = dataCities[index];
			filterDistrict(index);
		} else
			$('#city').append('<option value="' + index + '">' + dataCities[index]['name'] + '</option>');
	}
	
	$('#city').change(function(){
		var val = $(this).val();
		currentCity = dataCities[val];
		filterDistrict(val);
	});
	
	$('#district').change(function(){
		var val = $(this).val();
		$('#ward').html('<option value="">Chọn Phường/Xã</option>');
		$('#street').html('<option value="">Chọn Đường</option>');
		var district = currentCity['districts'][val];
		var wards = district['wards'];
		var streets = district['streets'];
		for(var index in wards) {
			$('#ward').append('<option value="' + index + '">' + wards[index]['pre'] + ' ' + wards[index]['name'] + '</option>');
		}
		for(var index in streets) {
			$('#street').append('<option value="' + index + '">' + streets[index]['name'] + ' ' + streets[index]['name'] + '</option>');
		}
	});
	
	$('#du-an-i').keyup(function(){
		console.log('ss');
		if($(this).val() != '') {
			$('#list-du-an').show();
		} else {
			$('#list-du-an').hide();
		}
	});

	$('#list-du-an > div').click(function(){
		$('#du-an-i').val($(this).text());
		$('#list-du-an').hide();
		$('#thong-tin-du-an').show();
	});
	
	$('#tai-anh-len').click(function(){
		$('#pl').attr('src', 'https://cdn.photographylife.com/wp-content/uploads/2014/06/Nikon-D810-Image-Sample-6.jpg');
		$('.icon').show();
	});
	
	$('.owner').click(function(){
		if($(this).val() == '0') {
			$('#cty').hide();
		} else {
			$('#cty').show();
		}
	});
});

function filterDistrict(cityId) {
	var districts = dataCities[cityId]['districts'];
	
	$('#district').html('<option value="">Chọn Quận/Huyện</option>');
	$('#ward').html('<option value="">Chọn Phường/Xã</option>');
	$('#street').html('<option value="">Chọn Đường</option>');
	for(index in districts) {
		$('#district').append('<option value="' + index + '">' + districts[index]['name'] + '</option>');
	}
}

function next() {
	var i = activeStep();
	
	var next = i+1;
	
	if(window['validate' + next]()) {
		$('.prev').removeClass('disabled');
		
		step.eq(i).removeClass('active');
		
		if(next == step.length - 1) {
			$('.next').addClass('disabled');
		}
		
		step.eq(next).addClass('active');
	}
}

function prev() {
	var i = activeStep();
	
	var prev = i-1;
	
	
	$('.next').removeClass('disabled');
	
	step.eq(i).removeClass('active');
	
	if(prev == 0) {
		$('.prev').addClass('disabled');
	}
	
	step.eq(prev).addClass('active');
}

function activeStep() {
	var i;
	
	step.each(function(index, el){
		var self = $(el);
		
		if(self.hasClass('active')) {
			i = index;
			
			return false;
		}
	});
	
	return i;
}

function validate1() {
	if($('#category').val() == '0') {
		alert('chọn loại BĐS');
		return false;
	}
	
	return true;
}
function validate2() {
	
	return true;
}
function validate3() {
	return true;
}
function validate4() {
	return true;
}