$(document).ready(function() {
	var animate_interval = 400;
	$('.animated').each(function(index, el) {
		
		setTimeout(function () {
			$(el).removeClass('animated');
		}, animate_interval);
		animate_interval += 200;
	});
});