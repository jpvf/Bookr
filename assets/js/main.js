jQuery(function($){
	
	var message = $('.alert-message');
	if(message.length){
		if(!message.hasClass('sticky')){
			message.delay(2000).fadeTo('slow', 0, function(){
			    $(this).slideUp();
			});  
	    }
	}

	if ($("table.sortable-table").size() > 0) {
		$("table.sortable-table").tablesorter({ sortList: [[0,0]] });
	}
	
	$('.notification').twipsy({
		live: true,
		placement : 'below'
	});
});