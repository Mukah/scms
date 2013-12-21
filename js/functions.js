function show_alert(element, delay, finish){
	element.removeClass('hidden').fadeIn().delay(delay).fadeOut(finish);
}
function confirm_button(element){
	var old_text = element.html();
	element.fadeOut(function(){
		element.html(element.data('confirmation-text'))
		.data('confirmation', 'true');
	}).fadeIn(function(){
		element.delay(3000).fadeOut(function(){
			element.html(old_text)
			.data('confirmation', '');
		}).fadeIn();
	});
}