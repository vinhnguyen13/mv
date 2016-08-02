$(document).ready(function(){
	$('#finder_filter').change(function(e){
		var val = $(this).val();
		
		if(val == 2) {
			e.stopPropagation();
			$('#alias').show();
		} else {
			$('#alias').val('');
		}
	});
});