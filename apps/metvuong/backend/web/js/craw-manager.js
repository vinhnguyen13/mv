$(document).ready(function(){
	$('.toggle-column').change(function(){
		var exclude = getCookie('columns');
		
		exclude = exclude ? exclude.split(',') : [];
		
		var val = $(this).val();
		
		if(this.checked) {
			var index = exclude.indexOf(val);
			exclude.splice(index, 1);
	    } else {
	    	exclude.push(val);
	    }
		
		setCookie('columns', exclude.join(','));
	});
	
	$('#toggle').click(function(e){
		e.preventDefault();
		
		$('#filter_columns').toggleClass('show');
	});
	
	$(document).click(function(e){
		var target = $(e.target);
		
		if(target.closest('#columns-wrap').length == 0 && target.attr('id') != 'toggle') {
			$('#filter_columns').removeClass('show');
		}
	});
});

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
	
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}